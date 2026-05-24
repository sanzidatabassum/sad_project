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

$id = $_GET['id'];

// Prevent deleting yourself
if ($id == $_SESSION['user_id']) {
    header("Location: list.php?msg=Cannot delete your own account");
    exit();
}

$sql  = "DELETE FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

header("Location: list.php?msg=User deleted successfully");
exit();
?>