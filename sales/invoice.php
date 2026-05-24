<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
require_once '../config/db.php';

$sale_id = $_GET['id'];

$sql  = "SELECT s.*, u.name as cashier_name FROM sales s JOIN users u ON s.cashier_id = u.id WHERE s.id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $sale_id);
mysqli_stmt_execute($stmt);
$sale = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

$sql2  = "SELECT si.*, m.name as medicine_name FROM sale_items si JOIN medicines m ON si.medicine_id = m.id WHERE si.sale_id = ?";
$stmt2 = mysqli_prepare($conn, $sql2);
mysqli_stmt_bind_param($stmt2, "i", $sale_id);
mysqli_stmt_execute($stmt2);
$items = mysqli_stmt_get_result($stmt2);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Invoice #<?= $sale_id ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print { .no-print { display: none; } }
        body { background: #f0f2f5; }
        .invoice-box { max-width: 700px; margin: 30px auto; background: white; padding: 30px; border-radius: 10px; }
    </style>
</head>
<body>
<div class="invoice-box">
    <div class="text-center mb-4">
        <h4>Satota Pharmacy & Departmental Store</h4>
        <p>Shop 1-3, Vai Vai Plaza, Road 12/A, Sector 10, Uttara, Dhaka</p>
        <p>Phone: 01621840038</p>
        <hr>
        <h5>INVOICE #<?= str_pad($sale_id, 5, '0', STR_PAD_LEFT) ?></h5>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <strong>Customer:</strong>
            <?= $sale['customer_name'] ? htmlspecialchars($sale['customer_name']) : 'Walk-in Customer' ?>
        </div>
        <div class="col-md-6 text-end">
            <strong>Date:</strong> <?= date('d M Y, h:i A', strtotime($sale['created_at'])) ?><br>
            <strong>Cashier:</strong> <?= htmlspecialchars($sale['cashier_name']) ?><br>
            <strong>Payment:</strong> <?= strtoupper($sale['payment_method']) ?>
        </div>
    </div>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Medicine</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $i = 1;
        while ($item = mysqli_fetch_assoc($items)):
        ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= htmlspecialchars($item['medicine_name']) ?></td>
                <td><?= $item['qty'] ?></td>
                <td>৳<?= number_format($item['unit_price'], 2) ?></td>
                <td>৳<?= number_format($item['subtotal'], 2) ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
        <tfoot>
            <tr><td colspan="4" class="text-end">Discount</td><td>৳<?= number_format($sale['discount'], 2) ?></td></tr>
            <tr class="table-success"><td colspan="4" class="text-end"><strong>Total</strong></td><td><strong>৳<?= number_format($sale['total_amount'], 2) ?></strong></td></tr>
        </tfoot>
    </table>

    <div class="text-center mt-4 no-print">
        <button onclick="window.print()" class="btn btn-primary">Print Invoice</button>
        <a href="create.php" class="btn btn-success ms-2">New Sale</a>
        <a href="list.php" class="btn btn-secondary ms-2">View All Sales</a>
    </div>

    <div class="text-center mt-4">
        <p><em>Thank you for your purchase!</em></p>
    </div>
</div>
</body>
</html>