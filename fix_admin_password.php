<?php
require_once 'php/db_config.php';

$email    = 'pranto@gmail.com';
$password = 'pranto123';
$hash     = password_hash($password, PASSWORD_DEFAULT);

// Update or insert admin
$stmt = $conn->prepare("SELECT id FROM admin WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$exists = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($exists) {
    $stmt = $conn->prepare("UPDATE admin SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $hash, $email);
    $stmt->execute();
    $stmt->close();
    echo "<p style='color:green'>✅ Password updated for <b>$email</b></p>";
} else {
    $stmt = $conn->prepare("INSERT INTO admin (full_name, mobile, email, password) VALUES ('Admin','01867610022',?,?)");
    $stmt->bind_param("ss", $email, $hash);
    $stmt->execute();
    $stmt->close();
    echo "<p style='color:green'>✅ Admin account created for <b>$email</b></p>";
}

echo "<p>Hash: <code>$hash</code></p>";
echo "<p>Verify test: " . (password_verify($password, $hash) ? '<span style=color:green>PASS</span>' : '<span style=color:red>FAIL</span>') . "</p>";
echo "<p style='color:red'><b>Delete this file after running!</b></p>";
echo "<p><a href='admin/login.html'>Go to Admin Login</a></p>";
$conn->close();
?>
