<?php
require_once '../config/config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $pass  = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT id,password_hash,role FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($pass, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        header('Location: dashboard.php');
    } else {
        $_SESSION['flash'] = 'Invalid email or password.';
        header('Location: index.php');
    }
    exit;
}