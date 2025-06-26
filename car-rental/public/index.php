<?php
require_once '../config/config.php';
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Car Rental - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body>
<nav class="navbar">
    <div class="container-fluid">
      <a class="navbar-brand"><b>CarRental</b></a>
    </div>
</nav>
<div class="d-flex align-items-center justify-content-center vh-100 bg-light">
<div class="login">
    <div class="login-header">
        <h2>Let's get started now!</h2>
    </div>
    <div class="card shadow p-4" style="min-width:330px;">
        <h3 class="login-text text-center mb-3">Car Rental Login</h3>
        <?php if (isset($_SESSION['flash'])): ?>
            <div class="alert alert-danger py-2"><?php echo $_SESSION['flash']; unset($_SESSION['flash']); ?></div>
        <?php endif; ?>
        <form action="login.php" method="post">
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email Address" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <button class="btn btn-success w-100">Login</button>
            <p class="mt-3 text-center small">No account?
                <a href="register.php">Register</a>
            </p>
        </form>
    </div>
</div>
</div>
<footer>
  <p>© CarRental Mobility Sdn Bhd 2025</p>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>