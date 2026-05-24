<?php
session_start();
require_once 'config/db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    $sql    = "SELECT * FROM users WHERE email = ?";
    $stmt   = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user   = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['role']      = $user['role'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pharmacy Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f0f2f5; }
        .login-box { max-width: 400px; margin: 100px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
<div class="login-box">
    <h4 class="text-center mb-4">Satota Pharmacy Login</h4>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
</div>
</body>
</html>