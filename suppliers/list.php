<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
require_once '../config/db.php';

$result = mysqli_query($conn, "SELECT * FROM suppliers ORDER BY supplier_name ASC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Suppliers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Supplier List</h3>
        <div>
            <a href="add.php" class="btn btn-success">+ Add Supplier</a>
            <a href="../dashboard.php" class="btn btn-secondary ms-2">Dashboard</a>
        </div>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success"><?= $_GET['msg'] ?></div>
    <?php endif; ?>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Contact Person</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Address</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $i = 1;
        while ($row = mysqli_fetch_assoc($result)):
        ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= htmlspecialchars($row['supplier_name']) ?></td>
                <td>-</td>
                <td><?= htmlspecialchars($row['phone']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['address']) ?></td>
                <td>
                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                    <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
                       onclick="return confirm('Delete this supplier?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>