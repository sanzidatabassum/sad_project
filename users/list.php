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

$result = mysqli_query($conn, "SELECT * FROM users ORDER BY name ASC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>User Management</h3>
        <div>
            <a href="add.php" class="btn btn-success">+ Add User</a>
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
                <th>Email</th>
                <th>Role</th>
                <th>Created At</th>
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
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><span class="badge bg-primary"><?= $row['role'] ?></span></td>
                <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                <td>
                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                    <?php if ($row['id'] != $_SESSION['user_id']): ?>
                        <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
                           onclick="return confirm('Delete this user?')">Delete</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>