<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
require_once 'config/db.php';

// Total medicines
$total_medicines = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM medicines"))['total'];

// Total customers
$total_customers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM customers"))['total'];

// Total suppliers
$total_suppliers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM suppliers"))['total'];

// Today's sales total
$today_sales = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_amount) as total FROM sales WHERE DATE(sale_date) = CURDATE()"))['total'];
$today_sales = $today_sales ? $today_sales : 0;

// Total sales count
$total_sales = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM sales"))['total'];

// Low stock medicines
$low_stock = mysqli_query($conn, "SELECT * FROM medicines WHERE stock_qty <= min_threshold ORDER BY stock_qty ASC LIMIT 5");
$low_stock_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM medicines WHERE stock_qty <= min_threshold"))['total'];

// Expired medicines
$expired = mysqli_query($conn, "SELECT * FROM medicines WHERE expiry_date < CURDATE() LIMIT 5");
$expired_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM medicines WHERE expiry_date < CURDATE()"))['total'];

// Expiring soon (within 30 days)
$expiring_soon_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM medicines WHERE expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)"))['total'];

// Recent sales
$recent_sales = mysqli_query($conn, "SELECT s.*, u.name as cashier_name FROM sales s LEFT JOIN users u ON s.cashier_id = u.id ORDER BY s.sale_date DESC LIMIT 5");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Satota Pharmacy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f0f2f5; }
        .sidebar {
            min-height: 100vh;
            background: #2c3e50;
            padding-top: 20px;
        }
        .sidebar a {
            color: #ecf0f1;
            display: block;
            padding: 12px 20px;
            text-decoration: none;
            border-bottom: 1px solid #34495e;
        }
        .sidebar a:hover { background: #34495e; }
        .sidebar .brand {
            color: white;
            font-size: 16px;
            font-weight: bold;
            padding: 10px 20px 20px;
            border-bottom: 1px solid #34495e;
        }
        .main-content { padding: 20px; }
        .stat-card { border-radius: 10px; padding: 20px; color: white; margin-bottom: 20px; }
    </style>
</head>
<body>
<div class="container-fluid">
<div class="row">

    <!-- Sidebar -->
    <div class="col-md-2 sidebar p-0">
        <div class="brand">🏥 Satota Pharmacy</div>
        <a href="dashboard.php">📊 Dashboard</a>
        <a href="medicines/list.php">💊 Medicines</a>
        <a href="customers/list.php">👥 Customers</a>
        <a href="suppliers/list.php">🚚 Suppliers</a>
        <a href="sales/create.php">🛒 New Sale</a>
        <a href="sales/list.php">📋 Sales History</a>
		<a href="prescriptions/list.php">📝 Prescriptions</a>
        <a href="reports/index.php">📈 Reports</a>
        <a href="users/list.php">👤 Users</a>
        <a href="logout.php" style="color:#e74c3c; margin-top:20px;">🚪 Logout</a>
    </div>

    <!-- Main Content -->
    <div class="col-md-10 main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Dashboard</h4>
            <span>Welcome, <strong><?= $_SESSION['user_name'] ?></strong> | Role: <strong><?= $_SESSION['role'] ?></strong></span>
        </div>

        <!-- Stat Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="stat-card" style="background:#2ecc71;">
                    <h6>Today's Sales</h6>
                    <h3>৳<?= number_format($today_sales, 2) ?></h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card" style="background:#3498db;">
                    <h6>Total Medicines</h6>
                    <h3><?= $total_medicines ?></h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card" style="background:#9b59b6;">
                    <h6>Total Customers</h6>
                    <h3><?= $total_customers ?></h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card" style="background:#e67e22;">
                    <h6>Total Sales</h6>
                    <h3><?= $total_sales ?></h3>
                </div>
            </div>
        </div>

        <!-- Alert Cards -->
        <div class="row">
            <div class="col-md-4">
                <div class="card border-warning mb-3">
                    <div class="card-header bg-warning">⚠️ Low Stock Medicines (<?= $low_stock_count ?>)</div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <?php while ($m = mysqli_fetch_assoc($low_stock)): ?>
                            <tr>
                                <td><?= htmlspecialchars($m['name']) ?></td>
                                <td><?= $m['stock_qty'] ?> left</td>
                            </tr>
                            <?php endwhile; ?>
                        </table>
                    </div>
                    <div class="card-footer">
                        <a href="medicines/list.php" class="btn btn-sm btn-warning">View All</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-danger mb-3">
                    <div class="card-header bg-danger text-white">❌ Expired Medicines (<?= $expired_count ?>)</div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <?php while ($m = mysqli_fetch_assoc($expired)): ?>
                            <tr>
                                <td><?= htmlspecialchars($m['name']) ?></td>
                                <td class="text-danger"><?= $m['expiry_date'] ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </table>
                    </div>
                    <div class="card-footer">
                        <a href="medicines/list.php" class="btn btn-sm btn-danger">View All</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-info mb-3">
                    <div class="card-header bg-info text-white">⏰ Expiring Soon (<?= $expiring_soon_count ?>)</div>
                    <div class="card-body">
                        <p>Medicines expiring within 30 days.</p>
                    </div>
                    <div class="card-footer">
                        <a href="medicines/list.php" class="btn btn-sm btn-info">View All</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Sales -->
        <div class="card">
            <div class="card-header"><strong>Recent Sales</strong></div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Invoice #</th>
                            <th>Customer</th>
                            <th>Cashier</th>
                            <th>Payment</th>
                            <th>Total</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = mysqli_fetch_assoc($recent_sales)): ?>
                        <tr>
                            <td><?= str_pad($row['id'], 5, '0', STR_PAD_LEFT) ?></td>
                            <td><?= $row['customer_id'] ? 'Customer #' . $row['customer_id'] : 'Walk-in' ?></td>
                            <td><?= htmlspecialchars($row['cashier_name']) ?></td>
                            <td><?= strtoupper($row['payment_method']) ?></td>
                            <td>৳<?= number_format($row['total_amount'], 2) ?></td>
                            <td><?= date('d M Y', strtotime($row['sale_date'])) ?></td>
                            <td><a href="sales/invoice.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info">Invoice</a></td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
</div>
</body>
</html>