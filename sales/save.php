<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
require_once '../config/db.php';

$cashier_id     = $_SESSION['user_id'];
$customer_id    = !empty($_POST['customer_id']) ? $_POST['customer_id'] : NULL;
$customer_name  = trim($_POST['customer_name']);
$payment_method = $_POST['payment_method'];
$discount       = floatval($_POST['discount']);
$total_amount   = floatval($_POST['total_amount']);

$medicine_ids = $_POST['medicine_id'];
$qtys         = $_POST['qty'];
$unit_prices  = $_POST['unit_price'];
$subtotals    = $_POST['subtotal'];

// Validate at least one medicine selected
$valid = false;
foreach ($medicine_ids as $mid) {
    if (!empty($mid)) { $valid = true; break; }
}
if (!$valid) {
    die("Error: No medicine selected. <a href='create.php'>Go back</a>");
}

// Insert into sales table
$sql  = "INSERT INTO sales (cashier_id, customer_id, customer_name, payment_method, discount, total_amount) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "iissdd", $cashier_id, $customer_id, $customer_name, $payment_method, $discount, $total_amount);
mysqli_stmt_execute($stmt);
$sale_id = mysqli_insert_id($conn);

// Insert each medicine into sale_items and deduct stock
for ($i = 0; $i < count($medicine_ids); $i++) {
    if (empty($medicine_ids[$i])) continue;

    $mid       = $medicine_ids[$i];
    $qty       = intval($qtys[$i]);
    $uprice    = floatval($unit_prices[$i]);
    $subtotal  = floatval($subtotals[$i]);

    // Insert sale item
    $sql2  = "INSERT INTO sale_items (sale_id, medicine_id, qty, unit_price, subtotal) VALUES (?, ?, ?, ?, ?)";
    $stmt2 = mysqli_prepare($conn, $sql2);
    mysqli_stmt_bind_param($stmt2, "iiidd", $sale_id, $mid, $qty, $uprice, $subtotal);
    mysqli_stmt_execute($stmt2);

    // Deduct stock from medicines table
    $sql3  = "UPDATE medicines SET stock_qty = stock_qty - ? WHERE id = ?";
    $stmt3 = mysqli_prepare($conn, $sql3);
    mysqli_stmt_bind_param($stmt3, "ii", $qty, $mid);
    mysqli_stmt_execute($stmt3);
}

header("Location: invoice.php?id=" . $sale_id);
exit();
?>