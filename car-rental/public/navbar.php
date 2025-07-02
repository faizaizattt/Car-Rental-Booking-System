<?php
require_once '../config/config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Navigation Bar - Car Rental</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="styles.css" rel="stylesheet">
  <style>
    .profile-icon {
      width: 30px;
      height: 30px;
      border-radius: 50%;
      object-fit: cover;
      margin-left: 15px;
    }
  </style>
</head>
<body>
<?php
// Assume $_SESSION['role'] is set to 'admin' or 'customer'
$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'customer';
?>
<nav class="navbar">
  <div class="container-fluid">
    <a class="navbar-brand" href="dashboard.php"><b>CarRental</b></a>
    <?php if ($role === 'admin'): ?>
      <a class="nav-item" href="users.php"><b>Manage Users</b></a>
      <a class="nav-item" href="cars.php"><b>Manage Cars</b></a>
      <a class="nav-item" href="bookings.php"><b>All Bookings</b></a>
      <a class="nav-item" href="feedback.php"><b>View Feedback</b></a>
    <?php else: ?>
      <a class="nav-item" href="cars.php"><b>View Cars</b></a>
      <a class="nav-item" href="bookings.php"><b>My Bookings</b></a>
      <a class="nav-item" href="feedback.php"><b>Feedback</b></a>
    <?php endif; ?>

    <!-- Profile Icon Link -->
    <a class="nav-item" href="profile.php">
      <img src="images/profile.png" alt="Profile" class="profile-icon">
    </a>
  </div>
</nav>
</body>
</html>
