<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
require_once '../config/db.php';

$result = mysqli_query($conn, "SELECT s.*, u.name as cashier_name FROM sales s JOIN users u ON s.cashier_id = u.id ORDER BY s.created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sales List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Sales History</h3>
        <div>
            <a href="create.php" class="btn btn-success">+ New Sale</a>
            <a href="../dashboard.php" class="btn btn-secondary ms-2">Dashboard</a>
        </div>
    </div>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Invoice #</th>
                <th>Customer</th>
                <th>Cashier</th>
                <th>Payment</th>
                <th>Discount</th>
                <th>Total</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= str_pad($row['id'], 5, '0', STR_PAD_LEFT) ?></td>
                <td><?= $row['customer_name'] ? htmlspecialchars($row['customer_name']) : 'Walk-in' ?></td>
                <td><?= htmlspecialchars($row['cashier_name']) ?></td>
                <td><?= strtoupper($row['payment_method']) ?></td>
                <td>৳<?= number_format($row['discount'], 2) ?></td>
                <td>৳<?= number_format($row['total_amount'], 2) ?></td>
                <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                <td>
                    <a href="invoice.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info">View Invoice</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>