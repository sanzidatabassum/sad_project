<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
require_once '../config/db.php';

$result = mysqli_query($conn, "
    SELECT p.*, u.name as pharmacist_name 
    FROM prescriptions p 
    LEFT JOIN users u ON p.pharmacist_id = u.id 
    ORDER BY p.created_at DESC
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Prescriptions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Prescription Records</h3>
        <div>
            <a href="add.php" class="btn btn-success">+ Add Prescription</a>
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
                <th>Patient Name</th>
                <th>Doctor Name</th>
                <th>Pharmacist</th>
                <th>Notes</th>
                <th>Date</th>
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
                <td><?= htmlspecialchars($row['patient_name']) ?></td>
                <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                <td><?= $row['pharmacist_name'] ?? '-' ?></td>
                <td><?= htmlspecialchars($row['notes'] ?? $row['details']) ?></td>
                <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                <td>
                    <a href="view.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info">View</a>
                    <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
                       onclick="return confirm('Delete this prescription?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>