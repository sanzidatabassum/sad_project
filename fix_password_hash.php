<?php
require_once 'php/db_config.php';

// Get current password from DB
$result = $conn->query("SELECT id, email, password FROM admin LIMIT 5");
echo "<h3>Current admin records:</h3><table border=1 cellpadding=5>";
echo "<tr><th>ID</th><th>Email</th><th>Password (stored)</th><th>Is bcrypt?</th></tr>";
while ($row = $result->fetch_assoc()) {
    $isBcrypt = str_starts_with($row['password'], '$2y$');
    echo "<tr><td>{$row['id']}</td><td>{$row['email']}</td><td>" . htmlspecialchars(substr($row['password'],0,30)) . "...</td><td>" . ($isBcrypt ? '✅ Yes' : '❌ No - plain text!') . "</td></tr>";
}
echo "</table>";

// Re-hash all passwords that are plain text
$result2 = $conn->query("SELECT id, password FROM admin");
$fixed = 0;
while ($row = $result2->fetch_assoc()) {
    if (!str_starts_with($row['password'], '$2y$')) {
        $hash = password_hash($row['password'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE admin SET password=? WHERE id=?");
        $stmt->bind_param("si", $hash, $row['id']);
        $stmt->execute();
        $stmt->close();
        $fixed++;
    }
}

if ($fixed > 0) {
    echo "<p style='color:green'>✅ Fixed $fixed admin password(s) — now bcrypt hashed.</p>";
} else {
    echo "<p style='color:blue'>ℹ️ All passwords already hashed. No changes needed.</p>";
}

echo "<p><a href='admin/login.html'><b>→ Go to Admin Login</b></a></p>";
echo "<p style='color:red'>Delete this file after use!</p>";
$conn->close();
?>
