<?php
session_start();
header('Content-Type: application/json');
require_once 'db_config.php';

if (isset($_GET['logout'])) {
    session_destroy();
    echo json_encode(['success' => true]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        echo json_encode(['success' => false, 'message' => 'Email and password are required']);
        exit;
    }

    $stmt = $conn->prepare("SELECT id, full_name, password FROM admin WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin  = $result->fetch_assoc();
    $stmt->close();

    if (!$admin) {
        echo json_encode(['success' => false, 'field' => 'email', 'message' => 'No account found with this email address']);
    } elseif (!password_verify($password, $admin['password'])) {
        echo json_encode(['success' => false, 'field' => 'password', 'message' => 'Incorrect password']);
    } else {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_name']      = $admin['full_name'];
        echo json_encode(['success' => true]);
    }
}
?> 