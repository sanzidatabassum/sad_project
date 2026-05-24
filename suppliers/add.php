<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
require_once '../config/db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name           = trim($_POST['name']);
    $contact_person = trim($_POST['contact_person']);
    $phone          = trim($_POST['phone']);
    $email          = trim($_POST['email']);
    $address        = trim($_POST['address']);

    if (empty($name) || empty($phone)) {
        $error = "Name and phone are required.";
    } else {
        $sql  = "INSERT INTO suppliers (name, contact_person, phone, email, address) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssss", $name, $contact_person, $phone, $email, $address);
        mysqli_stmt_execute($stmt);
        header("Location: list.php?msg=Supplier added successfully");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Supplier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4" style="max-width:600px">
    <h3>Add New Supplier</h3>
    <a href="list.php" class="btn btn-secondary mb-3">← Back to List</a>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label>Supplier Name *</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Contact Person</label>
            <input type="text" name="contact_person" class="form-control">
        </div>
        <div class="mb-3">
            <label>Phone *</label>
            <input type="text" name="phone" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control">
        </div>
        <div class="mb-3">
            <label>Address</label>
            <textarea name="address" class="form-control" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-success w-100">Add Supplier</button>
    </form>
</div>
</body>
</html>