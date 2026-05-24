<?php
session_start();
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.html');
} else {
    header('Location: login.html');
}
exit;
?>
