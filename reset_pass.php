<?php
require_once 'php/db_config.php';
$hash = password_hash('pranto123', PASSWORD_DEFAULT);
$conn->query("UPDATE admin SET password='$hash' WHERE email='pranto@gmail.com'");
echo $conn->affected_rows > 0
    ? "<p style='color:green;font-size:20px'>✅ Password reset! <a href='admin/login.html'>Login now</a></p>"
    : "<p style='color:red'>❌ No row updated. Check email in DB.</p>";
echo "<p style='color:red'>Delete this file after logging in!</p>";
?>
