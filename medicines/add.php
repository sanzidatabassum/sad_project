<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
require_once '../config/db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name          = trim($_POST['name']);
    $generic_name  = trim($_POST['generic_name']);
    $category      = trim($_POST['category']);
    $unit_price    = trim($_POST['unit_price']);
    $stock_qty     = trim($_POST['stock_qty']);
    $min_threshold = trim($_POST['min_threshold']);
    $expiry_date   = trim($_POST['expiry_date']);

    if (empty($name) || empty($unit_price) || empty($stock_qty)) {
        $error = "Name, price and stock are required.";
    } else {
        $sql  = "INSERT INTO medicines (name, generic_name, category, unit_price, stock_qty, min_threshold, expiry_date) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssdiis", $name, $generic_name, $category, $unit_price, $stock_qty, $min_threshold, $expiry_date);
        mysqli_stmt_execute($stmt);
        header("Location: list.php?msg=Medicine added successfully");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Medicine</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4" style="max-width:600px">
    <h3>Add New Medicine</h3>
    <a href="list.php" class="btn btn-secondary mb-3">← Back to List</a>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label>Medicine Name *</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Generic Name</label>
            <input type="text" name="generic_name" class="form-control">
        </div>
        <div class="mb-3">
            <label>Category</label>
            <input type="text" name="category" class="form-control">
        </div>
        <div class="mb-3">
            <label>Unit Price (৳) *</label>
            <input type="number" step="0.01" name="unit_price" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Stock Quantity *</label>
            <input type="number" name="stock_qty" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Minimum Threshold (Low Stock Alert)</label>
            <input type="number" name="min_threshold" class="form-control" value="10">
        </div>
        <div class="mb-3">
            <label>Expiry Date</label>
            <input type="date" name="expiry_date" class="form-control">
        </div>
        <button type="submit" class="btn btn-success w-100">Add Medicine</button>
    </form>
</div>
</body>
</html>