<?php
require_once 'config/db.php';

$result = mysqli_query($conn, "SELECT id, name, email, password, role FROM users");
echo "<h2>Users in database:</h2>";
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<pre>";
        print_r($row);
        echo "</pre><hr>";
    }
} else {
    echo "No users found!";
}
?>
