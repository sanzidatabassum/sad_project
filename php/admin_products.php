<?php
session_start();
require_once 'db_config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['admin_logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']); exit;
}

$action = $_REQUEST['action'] ?? '';

switch ($action) {
    case 'list':
        $r = $conn->query("SELECT * FROM products ORDER BY id DESC");
        echo json_encode(['success' => true, 'products' => $r->fetch_all(MYSQLI_ASSOC)]);
        break;

    case 'categories':
        $r = $conn->query("SELECT DISTINCT category FROM products ORDER BY category");
        $cats = array_column($r->fetch_all(MYSQLI_ASSOC), 'category');
        echo json_encode(['success' => true, 'categories' => $cats]);
        break;

    case 'add':
    case 'edit':
        $id          = intval($_POST['id'] ?? 0);
        $name        = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price       = floatval($_POST['price'] ?? 0);
        $image       = trim($_POST['image'] ?? '');
        $category    = trim($_POST['category'] ?? '');
        $status      = $_POST['status'] ?? 'available';
        $rating      = floatval($_POST['rating'] ?? 0);

        if (!$name || !$price || !$image || !$category) {
            echo json_encode(['success' => false, 'message' => 'Required fields missing']); break;
        }

        if ($action === 'add') {
            $stmt = $conn->prepare("INSERT INTO products (name,description,price,image,category,status,rating) VALUES (?,?,?,?,?,?,?)");
            $stmt->bind_param("ssdsssd", $name, $description, $price, $image, $category, $status, $rating);
        } else {
            $stmt = $conn->prepare("UPDATE products SET name=?,description=?,price=?,image=?,category=?,status=?,rating=? WHERE id=?");
            $stmt->bind_param("ssdsssdi", $name, $description, $price, $image, $category, $status, $rating, $id);
        }
        $stmt->execute();
        echo json_encode(['success' => $stmt->affected_rows >= 0, 'id' => $conn->insert_id ?: $id]);
        $stmt->close();
        break;

    case 'delete':
        $id = intval($_POST['id'] ?? 0);
        $stmt = $conn->prepare("DELETE FROM products WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        echo json_encode(['success' => $stmt->affected_rows > 0]);
        $stmt->close();
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
$conn->close();
?>
