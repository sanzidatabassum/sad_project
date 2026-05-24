<?php
require_once 'db_config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$result = $conn->query("SELECT id, name, description, price, image, category, status, rating FROM products ORDER BY id ASC");

if (!$result) {
    echo json_encode(['success' => false, 'message' => $conn->error]);
    exit;
}

$products = $result->fetch_all(MYSQLI_ASSOC);

// Cast types for JS
foreach ($products as &$p) {
    $p['id']     = (int)$p['id'];
    $p['price']  = (float)$p['price'];
    $p['rating'] = (float)$p['rating'];
}

echo json_encode(['success' => true, 'products' => $products]);

$conn->close();
?>
