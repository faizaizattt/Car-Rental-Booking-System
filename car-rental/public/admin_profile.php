<?php
require_once '../config/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch admin user data
$sql = "SELECT name, email FROM users WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    echo "Admin user not found.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="styles.css" rel="stylesheet">
  <style>
    body, html {
      height: 100%;
    }
  </style>
</head>
<body>
  <?php include 'navbar.php'; ?>
  
  <div class="d-flex align-items-center justify-content-center min-vh-100">
    <div class="container" style="max-width: 600px;">
      <h2 class="mb-4 text-center">Admin Profile</h2>
      <div class="card shadow p-4">
        <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>

        <!-- Edit Profile Button -->
        <div class="mt-3">
          <a href="edit_profile.php" class="btn btn-primary w-100">Edit Profile</a>
        </div>

        <!-- Change Password Button -->
        <div class="mt-3">
          <a href="change_password.php" class="btn btn-warning w-100">Change Password</a>
        </div>

        <!-- Logout Button -->
        <div class="mt-4">
          <a href="logout.php" class="btn btn-danger w-100">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
