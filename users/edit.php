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

$id   = $_GET['id'];
$sql  = "SELECT * FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$row  = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$row) {
    header("Location: list.php");
    exit();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $role     = trim($_POST['role']);
    $password = trim($_POST['password']);

    if (empty($name) || empty($email) || empty($role)) {
        $error = "Name, email and role are required.";
    } else {
        if (!empty($password)) {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $sql    = "UPDATE users SET name=?, email=?, role=?, password=? WHERE id=?";
            $stmt   = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssssi", $name, $email, $role, $hashed, $id);
        } else {
            $sql  = "UPDATE users SET name=?, email=?, role=? WHERE id=?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sssi", $name, $email, $role, $id);
        }
        mysqli_stmt_execute($stmt);
        header("Location: list.php?msg=User updated successfully");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4" style="max-width:600px">
    <h3>Edit User</h3>
    <a href="list.php" class="btn btn-secondary mb-3">← Back to List</a>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label>Full Name *</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($row['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Email *</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($row['email']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Role *</label>
            <select name="role" class="form-control" required>
                <option value="admin"      <?= $row['role']=='admin'      ? 'selected':'' ?>>Admin</option>
                <option value="pharmacist" <?= $row['role']=='pharmacist' ? 'selected':'' ?>>Pharmacist</option>
                <option value="cashier"    <?= $row['role']=='cashier'    ? 'selected':'' ?>>Cashier</option>
                <option value="inventory"  <?= $row['role']=='inventory'  ? 'selected':'' ?>>Inventory</option>
            </select>
        </div>
        <div class="mb-3">
            <label>New Password <small class="text-muted">(leave blank to keep current)</small></label>
            <input type="password" name="password" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary w-100">Update User</button>
    </form>
</div>
</body>
</html>