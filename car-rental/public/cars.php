<?php
require_once '../config/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$uid = $_SESSION['user_id'];
$isAdmin = $_SESSION['role'] === 'admin';

// Retrieve filters
$search = $_GET['search'] ?? '';
$seats = $_GET['seats'] ?? '';
$sort = $_GET['sort'] ?? 'brand ASC';
$status = $_GET['status'] ?? '';

// Build SQL query
$sql = "SELECT c.id, c.brand, c.model, c.img_link, c.seats, c.price_per_day, fuel_type, c.status
        FROM cars c 
        WHERE 1";

if ($search) {
    $sql .= " AND (c.brand LIKE :search OR c.model LIKE :search)";
}
if ($seats) {
    $sql .= " AND c.seats = :seats";
}
if (!$isAdmin) {
    $sql .= " AND c.status = 'available'";
} elseif ($status === 'available' || $status === 'unavailable') {
    $sql .= " AND c.status = :status";
}

$sql .= " ORDER BY $sort";

$stmt = $pdo->prepare($sql);

// Bind params
if ($search) {
    $stmt->bindValue(':search', "%$search%");
}
if ($seats) {
    $stmt->bindValue(':seats', $seats);
}
if ($isAdmin && ($status === 'available' || $status === 'unavailable')) {
    $stmt->bindValue(':status', $status);
}

$stmt->execute();
$cars = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Car Listings</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="styles.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container py-4">
  <h2>List of Cars</h2>
  <!-- Filter Form -->
  <form method="GET" class="row g-3 mb-4">
    <div class="col-md-3">
      <label for="search" class="form-label">Search</label>
      <input type="text" id="search" name="search" class="form-control" placeholder="Brand or model" value="<?= htmlspecialchars($search) ?>">
    </div>

    <div class="col-md-2">
      <label for="seats" class="form-label">Seats</label>
      <select id="seats" name="seats" class="form-select">
        <option value="">All</option>
        <option value="4" <?= $seats == 4 ? 'selected' : '' ?>>4</option>
        <option value="5" <?= $seats == 5 ? 'selected' : '' ?>>5</option>
        <option value="6" <?= $seats == 6 ? 'selected' : '' ?>>6</option>
        <option value="7" <?= $seats == 7 ? 'selected' : '' ?>>7</option>
      </select>
    </div>

    <?php if ($isAdmin): ?>
    <div class="col-md-2">
      <label for="status" class="form-label">Status</label>
      <select id="status" name="status" class="form-select">
        <option value="">All</option>
        <option value="available" <?= $status === 'available' ? 'selected' : '' ?>>Available</option>
        <option value="unavailable" <?= $status === 'unavailable' ? 'selected' : '' ?>>Unavailable</option>
      </select>
    </div>
    <?php endif; ?>

    <div class="col-md-3">
      <label for="sort" class="form-label">Sort By</label>
      <select id="sort" name="sort" class="form-select">
        <option value="brand ASC" <?= $sort === 'brand ASC' ? 'selected' : '' ?>>Brand (A-Z)</option>
        <option value="price_per_day ASC" <?= $sort === 'price_per_day ASC' ? 'selected' : '' ?>>Price (Low-High)</option>
      </select>
    </div>

    <div class="col-md-2 d-flex align-items-end">
      <button type="submit" class="btn btn-primary">Apply</button>
    </div>
    <div class="col-md-2 d-flex align-items-end">
      <?php if ($isAdmin): ?>
        <a href="add_car.php" class="btn btn-success mb-3">+ Add New Car</a>
      <?php endif; ?>
    </div>
  </form>

  <!-- Car Cards -->
  <div class="row">
    <?php if (empty($cars)): ?>
      <p>No cars found.</p>
    <?php else: ?>
      <?php foreach ($cars as $car): ?>
        <div class="col-md-4">
          <div class="card mb-4 shadow-sm">
            <img src="<?= htmlspecialchars($car['img_link']) ?>" class="card-img-top">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($car['brand']) ?> <?= htmlspecialchars($car['model']) ?></h5>
              <ul class="list-unstyled">
                <li><strong>Seats:</strong> <?= $car['seats'] ?></li>
                <li><strong>Fuel Type:</strong> <?= htmlspecialchars($car['fuel_type']) ?></li>
                <li><strong>Price/Day:</strong> RM<?= number_format($car['price_per_day'], 2) ?></li>
                <li><strong>Status:</strong> 
                  <span class="badge <?= $car['status'] === 'available' ? 'bg-success' : 'bg-danger' ?>">
                    <?= ucfirst($car['status']) ?>
                  </span>
                </li>
              </ul>
              <a href="car_details.php?id=<?= $car['id'] ?>" class="btn-info">View</a>
              <?php if ($isAdmin): ?>
                <a href="edit_car.php?id=<?= $car['id'] ?>" class="btn-info">Edit</a>
                <a href="delete_car.php?id=<?= $car['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this car?')">Delete</a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
