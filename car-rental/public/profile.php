<?php
require_once '../config/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$sql = "SELECT name, email FROM users WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    echo "User not found.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="styles.css" rel="stylesheet">
</head>
<body>
  <?php include 'navbar.php'; ?>
  
  <div class="container mt-5">
    <h2 class="mb-4">Your Profile</h2>
    <div class="card shadow p-4" style="max-width: 500px;">
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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
