<?php
require_once 'php/db_config.php';

// Check if admin table exists
$result = $conn->query("SHOW TABLES LIKE 'admin'");
if ($result->num_rows === 0) {
    echo "<p style='color:red'>❌ admin table does NOT exist. Creating it now...</p>";
    $conn->query("CREATE TABLE `admin` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `full_name` varchar(100) NOT NULL,
        `mobile` varchar(20) NOT NULL,
        `email` varchar(100) NOT NULL UNIQUE,
        `password` varchar(255) NOT NULL,
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
    echo "<p style='color:green'>✅ Table created.</p>";
}

// Reset password
$email = 'pranto@gmail.com';
$hash  = password_hash('pranto123', PASSWORD_DEFAULT);

$check = $conn->prepare("SELECT id FROM admin WHERE email = ?");
$check->bind_param("s", $email); $check->execute();
$exists = $check->get_result()->fetch_assoc(); $check->close();

if ($exists) {
    $u = $conn->prepare("UPDATE admin SET password=? WHERE email=?");
    $u->bind_param("ss", $hash, $email); $u->execute(); $u->close();
    echo "<p style='color:green'>✅ Password reset for $email</p>";
} else {
    $i = $conn->prepare("INSERT INTO admin (full_name,mobile,email,password) VALUES ('Admin','01867610022',?,?)");
    $i->bind_param("ss", $email, $hash); $i->execute(); $i->close();
    echo "<p style='color:green'>✅ Admin account created for $email</p>";
}

echo "<p>Verify: " . (password_verify('pranto123', $hash) ? '<b style=color:green>✅ PASS</b>' : '<b style=color:red>❌ FAIL</b>') . "</p>";
echo "<hr><p style='color:red'>Delete this file after use!</p>";
echo "<p><a href='admin/login.html'><b>→ Go to Admin Login</b></a></p>";
$conn->close();
?>
