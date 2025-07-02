<?php
require_once '../config/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Redirect admin to their own profile page
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header('Location: admin_profile.php');
    exit;
}

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
  <style>
    body, html {
      height: 100%;
    }
  </style>
</head>
<body>
  <?php include 'navbar.php'; ?>
  
  <!-- Center content using flex -->
  <div class="d-flex align-items-center justify-content-center min-vh-100">
    <div class="container" style="max-width: 600px; margin: 40px 0 40px;">
      <h2 class="mb-4 text-center">Your Profile</h2>
      <div class="card shadow p-4">
        <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>

        <!-- Edit Profile Button -->
        <div class="mt-3">
          <a href="edit_profile.php" class="btn btn-success w-100">Edit Profile</a>
        </div>

        <!-- Change Password Button -->
        <div class="mt-3">
          <a href="change_password.php" class="btn btn-success w-100">Change Password</a>
        </div>

        <!-- License Upload Section -->
        <div class="mt-4">
          <h5>License Upload</h5>
          <?php
          $stmt = $pdo->prepare("SELECT license_image FROM users WHERE id = ?");
          $stmt->execute([$user_id]);
          $user_license = $stmt->fetch();

          if ($user_license && $user_license['license_image']) {
              echo "<p>✅ License Uploaded:</p>";
              echo "<img src='" . htmlspecialchars($user_license['license_image']) . "' width='200'><br>";
              echo "<p class='text-success'>You are allowed to book cars.</p>";
          } else {
              echo "<p class='text-danger'>❌ No license uploaded yet.</p>";
              echo "<p class='text-warning'>You must upload your license before making a booking.</p>";
          }
          ?>

          <!-- Upload Form -->
          <form action="upload_license.php" method="post" enctype="multipart/form-data" class="mt-3">
            <input type="file" name="license" accept="image/*" required class="form-control mb-2">
            <button type="submit" class="btn btn-success w-100">Upload License</button>
          </form>
        </div>

        <!-- Logout Button -->
        <div class="mt-4">
          <a href="logout.php" class="btn btn-danger w-100">Logout</a>
        </div>
      </div>
    </div>
  </div>
  <?php include 'footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
