<?php
require_once 'db_config.php';

header('Content-Type: application/json');

$query = $_GET['query'] ?? '';

if (empty($query)) {
    echo json_encode([]);
    exit;
}

try {
    $search = "%{$query}%";
    
    $stmt = $conn->prepare("
        SELECT id, name, price, image, category 
        FROM products 
        WHERE (name LIKE ? 
        OR description LIKE ? 
        OR category LIKE ?)
        AND status = 'available'
        ORDER BY 
            CASE 
                WHEN name LIKE ? THEN 1
                WHEN category LIKE ? THEN 2
                ELSE 3
            END,
            name ASC
        LIMIT 10
    ");
    
    $stmt->bind_param("sssss", $search, $search, $search, $search, $search);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $products = $result->fetch_all(MYSQLI_ASSOC);
    
    // Format prices
    foreach ($products as &$product) {
        $product['price'] = number_format($product['price'], 2);
    }
    
    echo json_encode($products);
    
} catch (Exception $e) {
    error_log("Search error: " . $e->getMessage());
    echo json_encode([]);
}

$stmt->close();
$conn->close();
?> 