<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
if ($_SESSION['role'] != 'admin') {
    header("Location: ../dashboard.php");
    exit();
}
require_once '../config/db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role     = trim($_POST['role']);

    if (empty($name) || empty($email) || empty($password) || empty($role)) {
        $error = "All fields are required.";
    } else {
        // Check if email already exists
        $check = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($check, "s", $email);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0) {
            $error = "Email already exists.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $sql    = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
            $stmt   = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $hashed, $role);
            mysqli_stmt_execute($stmt);
            header("Location: list.php?msg=User added successfully");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4" style="max-width:600px">
    <h3>Add New User</h3>
    <a href="list.php" class="btn btn-secondary mb-3">← Back to List</a>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label>Full Name *</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email *</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Password *</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Role *</label>
            <select name="role" class="form-control" required>
                <option value="">-- Select Role --</option>
                <option value="admin">Admin</option>
                <option value="pharmacist">Pharmacist</option>
                <option value="cashier">Cashier</option>
                <option value="inventory">Inventory</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success w-100">Add User</button>
    </form>
</div>
</body>
</html>