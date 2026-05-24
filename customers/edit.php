<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
require_once '../config/db.php';

$id   = $_GET['id'];
$sql  = "SELECT * FROM customers WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$row  = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$row) {
    header("Location: list.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name    = trim($_POST['name']);
    $phone   = trim($_POST['phone']);
    $address = trim($_POST['address']);

    $sql  = "UPDATE customers SET name=?, phone=?, address=? WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssi", $name, $phone, $address, $id);
    mysqli_stmt_execute($stmt);
    header("Location: list.php?msg=Customer updated successfully");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4" style="max-width:600px">
    <h3>Edit Customer</h3>
    <a href="list.php" class="btn btn-secondary mb-3">← Back to List</a>

    <form method="POST">
        <div class="mb-3">
            <label>Customer Name *</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($row['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($row['phone']) ?>">
        </div>
        <div class="mb-3">
            <label>Address</label>
            <textarea name="address" class="form-control" rows="3"><?= htmlspecialchars($row['address']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary w-100">Update Customer</button>
    </form>
</div>
</body>
</html>