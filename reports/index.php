<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
require_once '../config/db.php';

// Date filters
$from = isset($_GET['from']) ? $_GET['from'] : date('Y-m-01');
$to   = isset($_GET['to'])   ? $_GET['to']   : date('Y-m-d');

// Sales report
$sales_result = mysqli_query($conn, "
    SELECT s.*, u.name as cashier_name 
    FROM sales s 
    JOIN users u ON s.cashier_id = u.id 
    WHERE DATE(s.created_at) BETWEEN '$from' AND '$to'
    ORDER BY s.created_at DESC
");

// Total revenue in range
$revenue = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT SUM(total_amount) as total FROM sales 
    WHERE DATE(created_at) BETWEEN '$from' AND '$to'
"))['total'];
$revenue = $revenue ? $revenue : 0;

// Total sales count in range
$sales_count = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) as total FROM sales 
    WHERE DATE(created_at) BETWEEN '$from' AND '$to'
"))['total'];

// Top selling medicines
$top_medicines = mysqli_query($conn, "
    SELECT m.name, SUM(si.qty) as total_qty, SUM(si.subtotal) as total_revenue
    FROM sale_items si
    JOIN medicines m ON si.medicine_id = m.id
    JOIN sales s ON si.sale_id = s.id
    WHERE DATE(s.created_at) BETWEEN '$from' AND '$to'
    GROUP BY si.medicine_id
    ORDER BY total_qty DESC
    LIMIT 5
");

// Low stock report
$low_stock = mysqli_query($conn, "
    SELECT * FROM medicines 
    WHERE stock_qty <= min_threshold 
    ORDER BY stock_qty ASC
");

// Expired medicines
$expired = mysqli_query($conn, "
    SELECT * FROM medicines 
    WHERE expiry_date < CURDATE()
    ORDER BY expiry_date ASC
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print { .no-print { display:none; } }
        body { background: #f0f2f5; }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Reports & Analytics</h3>
        <div class="no-print">
            <a href="../dashboard.php" class="btn btn-secondary">Dashboard</a>
            <button onclick="window.print()" class="btn btn-primary ms-2">Print Report</button>
        </div>
    </div>

    <!-- Date Filter -->
    <div class="card mb-4 no-print">
        <div class="card-body">
            <form method="GET" class="row align-items-end">
                <div class="col-md-4">
                    <label>From Date</label>
                    <input type="date" name="from" class="form-control" value="<?= $from ?>">
                </div>
                <div class="col-md-4">
                    <label>To Date</label>
                    <input type="date" name="to" class="form-control" value="<?= $to ?>">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h6>Total Revenue</h6>
                    <h3>৳<?= number_format($revenue, 2) ?></h3>
                    <small><?= $from ?> to <?= $to ?></small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h6>Total Transactions</h6>
                    <h3><?= $sales_count ?></h3>
                    <small><?= $from ?> to <?= $to ?></small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h6>Average Sale Value</h6>
                    <h3>৳<?= $sales_count > 0 ? number_format($revenue / $sales_count, 2) : '0.00' ?></h3>
                    <small><?= $from ?> to <?= $to ?></small>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Selling Medicines -->
    <div class="card mb-4">
        <div class="card-header bg-dark text-white"><strong>Top Selling Medicines</strong></div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Medicine</th>
                        <th>Qty Sold</th>
                        <th>Revenue</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $i = 1;
                while ($row = mysqli_fetch_assoc($top_medicines)):
                ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= $row['total_qty'] ?></td>
                        <td>৳<?= number_format($row['total_revenue'], 2) ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Sales Report -->
    <div class="card mb-4">
        <div class="card-header bg-dark text-white"><strong>Sales Transactions</strong></div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Invoice #</th>
                        <th>Customer</th>
                        <th>Cashier</th>
                        <th>Payment</th>
                        <th>Discount</th>
                        <th>Total</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = mysqli_fetch_assoc($sales_result)): ?>
                    <tr>
                        <td><?= str_pad($row['id'], 5, '0', STR_PAD_LEFT) ?></td>
                        <td><?= $row['customer_name'] ? htmlspecialchars($row['customer_name']) : 'Walk-in' ?></td>
                        <td><?= htmlspecialchars($row['cashier_name']) ?></td>
                        <td><?= strtoupper($row['payment_method']) ?></td>
                        <td>৳<?= number_format($row['discount'], 2) ?></td>
                        <td>৳<?= number_format($row['total_amount'], 2) ?></td>
                        <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Low Stock Report -->
    <div class="card mb-4">
        <div class="card-header bg-warning"><strong>⚠️ Low Stock Report</strong></div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Medicine</th>
                        <th>Category</th>
                        <th>Current Stock</th>
                        <th>Min Threshold</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = mysqli_fetch_assoc($low_stock)): ?>
                    <tr class="table-warning">
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['category']) ?></td>
                        <td><?= $row['stock_qty'] ?></td>
                        <td><?= $row['min_threshold'] ?></td>
                        <td><span class="badge bg-warning text-dark">Low Stock</span></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Expired Medicines Report -->
    <div class="card mb-4">
        <div class="card-header bg-danger text-white"><strong>❌ Expired Medicines Report</strong></div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Medicine</th>
                        <th>Category</th>
                        <th>Stock</th>
                        <th>Expiry Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = mysqli_fetch_assoc($expired)): ?>
                    <tr class="table-danger">
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['category']) ?></td>
                        <td><?= $row['stock_qty'] ?></td>
                        <td><?= $row['expiry_date'] ?></td>
                        <td><span class="badge bg-danger">Expired</span></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
</body>
</html>