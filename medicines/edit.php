<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
require_once '../config/db.php';

$id  = $_GET['id'];
$sql = "SELECT * FROM medicines WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$row) {
    header("Location: list.php");
    exit();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name          = trim($_POST['name']);
    $generic_name  = trim($_POST['generic_name']);
    $category      = trim($_POST['category']);
    $unit_price    = trim($_POST['unit_price']);
    $stock_qty     = trim($_POST['stock_qty']);
    $min_threshold = trim($_POST['min_threshold']);
    $expiry_date   = trim($_POST['expiry_date']);

    $sql  = "UPDATE medicines SET name=?, generic_name=?, category=?, unit_price=?, stock_qty=?, min_threshold=?, expiry_date=? WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssdiisi", $name, $generic_name, $category, $unit_price, $stock_qty, $min_threshold, $expiry_date, $id);
    mysqli_stmt_execute($stmt);
    header("Location: list.php?msg=Medicine updated successfully");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Medicine</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4" style="max-width:600px">
    <h3>Edit Medicine</h3>
    <a href="list.php" class="btn btn-secondary mb-3">← Back to List</a>

    <form method="POST">
        <div class="mb-3">
            <label>Medicine Name *</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($row['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Generic Name</label>
            <input type="text" name="generic_name" class="form-control" value="<?= htmlspecialchars($row['generic_name']) ?>">
        </div>
        <div class="mb-3">
            <label>Category</label>
            <input type="text" name="category" class="form-control" value="<?= htmlspecialchars($row['category']) ?>">
        </div>
        <div class="mb-3">
            <label>Unit Price (৳) *</label>
            <input type="number" step="0.01" name="unit_price" class="form-control" value="<?= $row['unit_price'] ?>" required>
        </div>
        <div class="mb-3">
            <label>Stock Quantity *</label>
            <input type="number" name="stock_qty" class="form-control" value="<?= $row['stock_qty'] ?>" required>
        </div>
        <div class="mb-3">
            <label>Minimum Threshold</label>
            <input type="number" name="min_threshold" class="form-control" value="<?= $row['min_threshold'] ?>">
        </div>
        <div class="mb-3">
            <label>Expiry Date</label>
            <input type="date" name="expiry_date" class="form-control" value="<?= $row['expiry_date'] ?>">
        </div>
        <button type="submit" class="btn btn-primary w-100">Update Medicine</button>
    </form>
</div>
</body>
</html>