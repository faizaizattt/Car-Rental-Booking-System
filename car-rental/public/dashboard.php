<?php
require_once '../config/config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$sql = "SELECT c.id, c.brand, c.model, c.img_link, c.seats, c.price_per_day, fuel_type
        FROM cars c 
        WHERE 1";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$cars = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Dashboard - Car Rental</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="styles.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<div>
  <?php include 'navbar.php'; ?>
</div>

<div class="header">
  <img src="images/Image2-view.jpg" alt="Header Image" class="header-pic">
  <h2>Welcome to CarRental</h2>
  <p>Need a car for your gateway or a quick trip in your area?
  <br>CarRental has everything you need to rent a car for your adventure trip.</p>
</div>
<div class="car-display">
  <h2 class="sub-title">Available Car Rental Deals</h2>
  <div class="car-scrollable">
    <?php foreach ($cars as $car): ?>
      <div class="car-item">
        <img src="<?php echo htmlspecialchars($car['img_link']); ?>" alt="Car Image" class="car-image">
        <h3><?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?></h3>
        <p>starting from RM<?php echo number_format($car['price_per_day'], 2); ?> per day</p>
        <p>
          <strong>Seats:</strong> <?php echo htmlspecialchars($car['seats']); ?><br>
          <strong>Fuel Type:</strong> <?php echo htmlspecialchars($car['fuel_type']); ?>
        </p>
        <p class="distance">0.3 km</p>
        <p><strong>Location:</strong> Student mall UTM, Skudai, Johor</p>
        <a href="bookings.php" class="book-btn">Book Now</a>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<div class="why-us">
  <h2 class="sub-title">Why us?</h2>
  <div class="row">
    <div class="col-md-6">
      <img src="images/Family-in-car.jpg" alt="Family In Car" class="img-fluid">
    </div>
    <div class="col-md-6">
      <h2 class="col-head">Reason why you should choose CarRental?</h2>
      <p>With CarRental, you don't have to rely on traditional sewa kereta services. Our car rental service is available 24/7 with a range of models to suit all your needs rent a car closest to you from your phone, and proceed with your booking.</p>
      <div class="features">
        <div class="feature-item">
          <h4 class="col-text">Available 24/7</h4>
          <p>Can book car at anytime.</p>
        </div>
        <div class="feature-item">
          <h4 class="col-text">Cost Effective</h4>
          <p>No additional charge for km travelled.</p>
        </div>
        <div class="feature-item">
          <h4 class="col-text">No Hidden Fees</h4>
          <p>Know exactly what you're paying</p>
        </div>
        <div class="feature-item">
          <h4 class="col-text">Commitment Free</h4>
          <p>Pay for a car only when you need it</p>
        </div>
      </div>
    </div>
  </div>
</div>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>