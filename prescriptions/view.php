<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
require_once '../config/db.php';

$id   = $_GET['id'];
$sql  = "SELECT p.*, u.name as pharmacist_name FROM prescriptions p JOIN users u ON p.pharmacist_id = u.id WHERE p.id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$row  = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$row) {
    header("Location: list.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Prescription #<?= $id ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print { .no-print { display:none; } }
        body { background: #f0f2f5; }
        .prescription-box { max-width:650px; margin:30px auto; background:white; padding:30px; border-radius:10px; }
    </style>
</head>
<body>
<div class="prescription-box">
    <div class="text-center mb-4">
        <h4>Satota Pharmacy & Departmental Store</h4>
        <p>Shop 1-3, Vai Vai Plaza, Road 12/A, Sector 10, Uttara, Dhaka</p>
        <p>Phone: 01621840038</p>
        <hr>
        <h5>PRESCRIPTION RECORD #<?= str_pad($row['id'], 5, '0', STR_PAD_LEFT) ?></h5>
    </div>

    <table class="table table-bordered">
        <tr>
            <td width="40%"><strong>Patient Name</strong></td>
            <td><?= htmlspecialchars($row['patient_name']) ?></td>
        </tr>
        <tr>
            <td><strong>Doctor Name</strong></td>
            <td><?= htmlspecialchars($row['doctor_name']) ?></td>
        </tr>
        <tr>
            <td><strong>Pharmacist</strong></td>
            <td><?= htmlspecialchars($row['pharmacist_name']) ?></td>
        </tr>
        <tr>
            <td><strong>Date</strong></td>
            <td><?= date('d M Y, h:i A', strtotime($row['created_at'])) ?></td>
        </tr>
        <tr>
            <td><strong>Prescription Notes</strong></td>
            <td><?= nl2br(htmlspecialchars($row['notes'])) ?></td>
        </tr>
    </table>

    <div class="text-center mt-4 no-print">
        <button onclick="window.print()" class="btn btn-primary">Print</button>
        <a href="list.php" class="btn btn-secondary ms-2">← Back to List</a>
    </div>
</div>
</body>
</html>