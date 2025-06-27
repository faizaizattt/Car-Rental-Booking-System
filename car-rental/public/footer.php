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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Footer Bar</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="styles.css" rel="stylesheet">
</head>
<body>
<div class="footer-links">
  <div class="container">
    <div class="row">
      <div class="col-md-3">
        <h4>More information</h4>
        <ul>
          <li><a>Contact Us</a></li>
          <li><a>Print Invoice Copy</a></li>
          <li><a>Find a Rental Location</a></li>
          <li><a>Call Us</a></li>
        </ul>
      </div>
      <div class="col-md-3">
        <h4>Business center</h4>
        <ul>
          <li><a>Corporate Account</a></li>
          <li><a>Affiliate</a></li>
          <li><a>Our Operators</a></li>
          <li><a>Travel Agents</a></li>
        </ul>
      </div>
      <div class="col-md-3">
        <h4>CarRental Mobility Group</h4>
        <ul>
          <li><a>CarRental Rent a Car</a></li>
          <li><a>Goldcar</a></li>
          <li><a>CarRental On Demand</a></li>
          <li><a>Careers</a></li>
        </ul>
      </div>
      <div class="col-md-3">
        <h4>Legal Information</h4>
        <ul>
          <li><a>Terms and Conditions</a></li>
          <li><a>Deposit Policy</a></li>
          <li><a>Privacy Policy</a></li>
          <li><a>Damage Management Policy</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>
<footer>
  <p>© CarRental Mobility Sdn Bhd 2025</p>
</footer>
</body>
</html>