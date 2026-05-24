<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
require_once '../config/db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pharmacist_id = $_SESSION['user_id'];
    $patient_name  = trim($_POST['patient_name']);
    $doctor_name   = trim($_POST['doctor_name']);
    $notes         = trim($_POST['notes']);

    if (empty($patient_name)) {
        $error = "Patient name is required.";
    } else {
        $sql  = "INSERT INTO prescriptions (pharmacist_id, patient_name, doctor_name, notes) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "isss", $pharmacist_id, $patient_name, $doctor_name, $notes);
        mysqli_stmt_execute($stmt);
        header("Location: list.php?msg=Prescription added successfully");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Prescription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4" style="max-width:600px">
    <h3>Add New Prescription</h3>
    <a href="list.php" class="btn btn-secondary mb-3">← Back to List</a>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label>Patient Name *</label>
            <input type="text" name="patient_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Doctor Name</label>
            <input type="text" name="doctor_name" class="form-control">
        </div>
        <div class="mb-3">
            <label>Prescription Notes / Medicines Prescribed</label>
            <textarea name="notes" class="form-control" rows="5" 
                      placeholder="Write medicines, dosage, instructions..."></textarea>
        </div>
        <div class="mb-3">
            <label>Pharmacist</label>
            <input type="text" class="form-control" value="<?= $_SESSION['user_name'] ?>" disabled>
            <small class="text-muted">Automatically assigned to logged-in user</small>
        </div>
        <button type="submit" class="btn btn-success w-100">Save Prescription</button>
    </form>
</div>
</body>
</html>