<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
require_once '../config/db.php';

$result = mysqli_query($conn, "SELECT * FROM medicines ORDER BY medicine_name ASC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Medicines</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Medicine List</h3>
        <div>
            <a href="add.php" class="btn btn-success">+ Add Medicine</a>
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
                <th>Generic Name</th>
                <th>Category</th>
                <th>Unit Price</th>
                <th>Stock</th>
                <th>Expiry Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $i = 1;
        while ($row = mysqli_fetch_assoc($result)):
            $low_stock = $row['stock_qty'] <= $row['min_threshold'];
            $expiry    = strtotime($row['expiry_date']);
            $soon      = $expiry <= strtotime('+30 days');
            $expired   = $expiry < strtotime('today');
        ?>
            <tr class="<?= $expired ? 'table-danger' : ($low_stock ? 'table-warning' : '') ?>">
                <td><?= $i++ ?></td>
                <td><?= htmlspecialchars($row['medicine_name']) ?></td>
                <td><?= htmlspecialchars($row['generic_name']) ?></td>
                <td><?= htmlspecialchars($row['category']) ?></td>
                <td>৳<?= number_format($row['unit_price'], 2) ?></td>
                <td><?= $row['stock_qty'] ?></td>
                <td><?= $row['expiry_date'] ?></td>
                <td>
                    <?php if ($expired): ?>
                        <span class="badge bg-danger">Expired</span>
                    <?php elseif ($soon): ?>
                        <span class="badge bg-warning text-dark">Expiring Soon</span>
                    <?php elseif ($low_stock): ?>
                        <span class="badge bg-warning text-dark">Low Stock</span>
                    <?php else: ?>
                        <span class="badge bg-success">OK</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                    <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
                       onclick="return confirm('Delete this medicine?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>