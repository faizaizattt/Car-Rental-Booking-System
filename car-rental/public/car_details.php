<?php
require_once '../config/config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

if (!isset($_GET['id'])) {
    echo "Car ID not provided.";
    exit;
}

$car_id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM cars WHERE id = :id");
$stmt->bindValue(':id', $car_id, PDO::PARAM_INT);
$stmt->execute();
$car = $stmt->fetch();

if (!$car) {
    echo "Car not found.";
    exit;
}

$isAdmin = $_SESSION['role'] === 'admin';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?> Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="styles.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container py-5">
  <h1 class="mb-4">Car Details</h1>
  <div class="card car-details-card shadow-lg">
    <div class="row g-0">
      <div class="col-md-6 image-section">
        <img src="<?php echo htmlspecialchars($car['img_link']); ?>" class="img-fluid rounded-start car-image" alt="Car Image">
      </div>
      <div class="col-md-6">
        <div class="card-body">
          <h2 class="card-title"><?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?></h2>
          <ul class="list-group list-group-flush mt-3 car-info-list">
            <li class="list-group-item"><strong>Model:</strong> <?php echo htmlspecialchars($car['model']); ?></li>
            <li class="list-group-item"><strong>Brand:</strong> <?php echo htmlspecialchars($car['brand']); ?></li>
            <li class="list-group-item"><strong>Seats:</strong> <?php echo htmlspecialchars($car['seats']); ?> people</li>
            <li class="list-group-item"><strong>Fuel Type:</strong> <?php echo htmlspecialchars($car['fuel_type']); ?></li>
            <li class="list-group-item"><strong>Price per Day:</strong> RM<?php echo number_format($car['price_per_day'], 2); ?></li>
          </ul>
          <div class="mt-4 text-center">
            <?php if ($isAdmin): ?>
              <a href="cars.php" class="btn btn-secondary">Back to Car List</a>
            <?php else: ?>
              <a href="add_booking.php?car_id=<?php echo $car['id']; ?>" class="book-now-btn">Proceed to Booking</a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>

