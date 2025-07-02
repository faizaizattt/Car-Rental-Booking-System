<?php
require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && md5($password) === $user['password_hash']) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        if (isset($_POST['remember'])) {
            setcookie("user_email", $email, time() + (86400 * 7), "/"); // valid for 7 days
        }
        $_SESSION['welcome'] = "Welcome back, " . $user['email'] . "!";
        header('Location: index.php');
        exit;
    } else {
        $_SESSION['flash'] = 'Invalid email or password';
        header('Location: index.php');
        exit;
    }
} ?>
<?php if (isset($_COOKIE['user_email'])): ?>
    <p class="text-center mt-3">Welcome back, <?php echo htmlspecialchars($_COOKIE['user_email']); ?>!</p>
<?php endif; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
</body>
</html>
