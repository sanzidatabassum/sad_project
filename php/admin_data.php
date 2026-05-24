<?php
session_start();
require_once 'db_config.php';

header('Content-Type: application/json');

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'dashboard_stats':
        getDashboardStats();
        break;
    case 'recent_orders':
        getRecentOrders();
        break;
    case 'all_orders':
        getAllOrders();
        break;
    case 'complete_order':
        completeOrder();
        break;
    case 'messages':
        getMessages();
        break;
    default:
        echo json_encode(['error' => 'Invalid action']);
}

function getDashboardStats() {
    global $conn;
    
    // Get total orders
    $total_orders = $conn->query("SELECT COUNT(*) as count FROM orders")->fetch_assoc()['count'];
    
    // Get total earnings
    $total_earnings = $conn->query("SELECT SUM(total_amount) as total FROM orders")->fetch_assoc()['total'] ?? 0;
    
    // Get pending orders
    $pending_orders = $conn->query("SELECT COUNT(*) as count FROM orders WHERE status = 'pending'")->fetch_assoc()['count'];
    
    // Get completed orders
    $completed_orders = $conn->query("SELECT COUNT(*) as count FROM orders WHERE status = 'completed'")->fetch_assoc()['count'];
    
    // Get monthly earnings for chart
    $monthly_earnings = $conn->query("
        SELECT DATE_FORMAT(order_date, '%Y-%m') as month, 
               SUM(total_amount) as total
        FROM orders
        WHERE order_date >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
        GROUP BY month
        ORDER BY month
    ")->fetch_all(MYSQLI_ASSOC);
    
    // Get delivery location distribution
    $location_stats = $conn->query("
        SELECT delivery_location, COUNT(*) as count
        FROM orders
        GROUP BY delivery_location
    ")->fetch_all(MYSQLI_ASSOC);
    
    echo json_encode([
        'total_orders' => $total_orders,
        'total_earnings' => $total_earnings,
        'pending_orders' => $pending_orders,
        'completed_orders' => $completed_orders,
        'monthly_earnings' => $monthly_earnings,
        'location_stats' => $location_stats
    ]);
}

function getRecentOrders() {
    global $conn;
    
    $result = $conn->query("
        SELECT id, customer_name, total_amount, status, order_date
        FROM orders
        ORDER BY order_date DESC
        LIMIT 10
    ");
    
    echo json_encode(['orders' => $result->fetch_all(MYSQLI_ASSOC)]);
}

function getAllOrders() {
    global $conn;
    
    $result = $conn->query("
        SELECT *
        FROM orders
        ORDER BY order_date DESC
    ");
    
    echo json_encode(['orders' => $result->fetch_all(MYSQLI_ASSOC)]);
}

function completeOrder() {
    global $conn;
    
    $order_id = $_POST['order_id'] ?? 0;
    
    $stmt = $conn->prepare("UPDATE orders SET status = 'completed' WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update order']);
    }
    
    $stmt->close();
}

function getMessages() {
    global $conn;
    
    $result = $conn->query("
        SELECT *
        FROM contact_messages
        ORDER BY created_at DESC
    ");
    
    echo json_encode(['messages' => $result->fetch_all(MYSQLI_ASSOC)]);
}
?> 