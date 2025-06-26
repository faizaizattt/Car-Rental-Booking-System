<?php
require_once '../config/config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $pass  = $_POST['password'] ?? '';

    if (!$name || !$email || strlen($pass) < 6) {
        $_SESSION['flash'] = 'Invalid input — please try again.';
        header('Location: register.php');
        exit;
    }

    $hash = password_hash($pass, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users (name,email,password_hash) VALUES (?,?,?)');
    try {
        $stmt->execute([$name,$email,$hash]);
        $_SESSION['flash'] = 'Registration successful — please log in.';
        header('Location: index.php');
    } catch (PDOException $e) {
        $_SESSION['flash'] = 'Email already registered.';
        header('Location: register.php');
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Register - Car Rental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body ></body>
<nav class="navbar">
    <div class="container-fluid">
      <a class="navbar-brand"><b>CarRental</b></a>
    </div>
</nav>
<div class="d-flex align-items-center justify-content-center vh-100 bg-light">
<div class="login">
    <div class="login-header">
        <h2>Create Your New Account!</h2>
    </div>
    <div>
        <div class="card shadow p-4" style="min-width:330px;">
            <h3 class="login-text text-center mb-3">Create Account</h3>
            <?php if (isset($_SESSION['flash'])): ?>
                <div class="alert alert-danger py-2"><?php echo $_SESSION['flash']; unset($_SESSION['flash']); ?></div>
                <?php endif; ?>
                <form method="post">
                <div class="mb-3"><input class="form-control" name="name" placeholder="Full name" required></div>
                <div class="mb-3"><input type="email" class="form-control" name="email" placeholder="Email" required></div>
                <div class="mb-3"><input type="password" class="form-control" name="password" placeholder="Password (min 6)" required></div>
                <button class="btn btn-success w-100">Register</button>
                <p class="mt-3 text-center small">Have an account? <a href="index.php">Login</a></p>
            </form>
        </div>
    </div>
</div>
</div>
<footer>
  <p>© CarRental Mobility Sdn Bhd 2025</p>
</footer>
</body>
</html>