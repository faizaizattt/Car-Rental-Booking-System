<?php
require_once '../config/config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$uid = $_SESSION['user_id'];
$isAdmin = $_SESSION['role'] === 'admin';

// Retrieve filters from request
$search = $_GET['search'] ?? '';
$seats = $_GET['seats'] ?? '';
$sort = $_GET['sort'] ?? 'brand ASC';

// Construct the SQL query with filters and sorting
$sql = "SELECT c.id, c.brand, c.model, c.img_link, c.seats, c.price_per_day, fuel_type
        FROM cars c 
        WHERE 1";

if ($search) {
    $sql .= " AND (c.brand LIKE :search OR c.model LIKE :search)";
}
if ($seats) {
    $sql .= " AND c.seats = :seats";
}

$sql .= " ORDER BY $sort";

$stmt = $pdo->prepare($sql);

// Bind parameters
if ($search) {
    $stmt->bindValue(':search', "%$search%");
}
if ($seats) {
    $stmt->bindValue(':seats', $seats);
}

$stmt->execute();
$cars = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Car Listings</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="styles.css" rel="stylesheet">
</head>
<body>
<div>
  <?php include 'navbar.php'; ?>
</div>
<div class="container py-4">
  <h1>Available Cars</h1>
  <form method="GET" id="filterForm" class="row g-3">
    <div class="col-md-3">
      <label for="search" class="form-label">Search</label>
      <input type="text" id="search" name="search" class="form-control" placeholder="Search by brand or model">
    </div>
    <div class="col-md-2">
      <label for="seats" class="form-label">Seats</label>
      <select id="seats" name="seats" class="form-select">
        <option value="">All</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
      </select>
    </div>
    <div class="col-md-2">
      <label for="sort" class="form-label">Sort By</label>
      <select id="sort" name="sort" class="form-select">
        <option value="brand ASC">Brand (A-Z)</option>
        <option value="price_per_day ASC">Price (Low-High)</option>
      </select>
    </div>
    <div class="col-md-3">
      <button type="submit" class="btn btn-success">Filter</button>
    </div>
  </form>

  <div class="mt-4">
    <div class="row">
      <?php foreach ($cars as $car): ?>
        <div class="col-md-6 mb-3">
          <div class="card">
            <div class="card-body">
              <img src="<?php echo htmlspecialchars($car['img_link']); ?>" class="card-img-top">
              <h5 class="card-title">
                <?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?></h5>
              <p class="card-text">
                <strong>Seats:</strong> <?php echo htmlspecialchars($car['seats']); ?><br>
                <strong>Price/Day:</strong> RM<?php echo number_format($car['price_per_day'], 2); ?><br>
                <strong>Fuel Type:</strong> <?php echo htmlspecialchars($car['fuel_type']); ?>
              </p>
              <a href="bookings.php" class="btn btn-success">Book Now</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
</body>
</html>
