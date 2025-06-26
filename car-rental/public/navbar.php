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
</head>
<body>
<nav class="navbar">
    <div class="container-fluid">
      <a class="navbar-brand" href="dashboard.php"><b>CarRental</b></a>
        <a class="nav-item" href="cars.php"><b>View Cars</b></a>
        <a class="nav-item" href="bookings.php"><b>Cars Bookings</b></a>
        <a class="nav-item" href="logout.php">Logout</a>
    </div>
</nav>
</body>
</html>