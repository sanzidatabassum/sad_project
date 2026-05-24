<?php
// Add error logging at the top of the file
ini_set('display_errors', 1);
ini_set('log_errors', 1);
error_reporting(E_ALL);
error_log("Order submission started");

require_once 'db_config.php';

header('Content-Type: application/json');

try {
    error_log("POST data: " . print_r($_POST, true));

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Validate required fields
    $required_fields = ['name', 'phone', 'address', 'delivery', 'cart_items'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            error_log("Missing field: " . $field);
            throw new Exception("Missing required field: {$field}");
        }
    }

    // Get form data
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $address = trim($_POST['address']);
    $delivery_location = $_POST['delivery'];
    $cart_items = json_decode($_POST['cart_items'], true);
    $subtotal = floatval($_POST['subtotal']);
    $delivery_charge = floatval($_POST['delivery_charge']);
    $total = floatval($_POST['total']);

    error_log("Parsed data - Name: $name, Phone: $phone, Email: $email");
    error_log("Cart items: " . print_r($cart_items, true));

    if (empty($cart_items)) {
        throw new Exception('Cart is empty');
    }

    // Start transaction
    $conn->begin_transaction();

    // Prepare and execute the order insertion
    $sql = "INSERT INTO orders (customer_name, phone, email, address, delivery_location, subtotal, delivery_charge, total_amount, order_date) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Database error: " . $conn->error);
        throw new Exception('Failed to prepare order statement: ' . $conn->error);
    }

    $stmt->bind_param("sssssddd", $name, $phone, $email, $address, $delivery_location, $subtotal, $delivery_charge, $total);
    
    if (!$stmt->execute()) {
        error_log("Execute error: " . $stmt->error);
        throw new Exception('Failed to insert order: ' . $stmt->error);
    }

    $order_id = $stmt->insert_id;
    error_log("Order ID created: " . $order_id);
    
    // Insert order items
    $items_sql = "INSERT INTO order_items (order_id, product_id, product_name, quantity, price) VALUES (?, ?, ?, ?, ?)";
    $items_stmt = $conn->prepare($items_sql);
    
    if (!$items_stmt) {
        error_log("Items statement error: " . $conn->error);
        throw new Exception('Failed to prepare items statement: ' . $conn->error);
    }

    foreach ($cart_items as $item) {
        $items_stmt->bind_param("iisid", $order_id, $item['id'], $item['name'], $item['quantity'], $item['price']);
        if (!$items_stmt->execute()) {
            error_log("Item insert error: " . $items_stmt->error);
            throw new Exception('Failed to insert order item: ' . $items_stmt->error);
        }
    }

    // Commit transaction
    $conn->commit();
    error_log("Order completed successfully");
    
    echo json_encode([
        'success' => true, 
        'message' => 'Order placed successfully!',
        'orderId' => $order_id
    ]);

} catch (Exception $e) {
    error_log("Error occurred: " . $e->getMessage());
    // Rollback transaction if started
    if (isset($conn) && $conn->connect_errno === 0) {
        $conn->rollback();
    }
    
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
} finally {
    // Close statements and connection
    if (isset($items_stmt)) $items_stmt->close();
    if (isset($stmt)) $stmt->close();
    if (isset($conn)) $conn->close();
}
?> 