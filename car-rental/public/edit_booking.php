<?php
require_once '../config/config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$id = (int)($_GET['id'] ?? 0);
$isAdmin = $_SESSION['role'] === 'admin';

// Get the booking
$stmt = $pdo->prepare('SELECT b.*, c.brand, c.model FROM bookings b JOIN cars c ON c.id = b.car_id WHERE b.id = ?');
$stmt->execute([$id]);
$booking = $stmt->fetch();

if (!$booking || (!$isAdmin && $booking['user_id'] != $_SESSION['user_id'])) {
    header('Location: bookings.php');
    exit;
}

// Load the car details
$car_id = $booking['car_id'];
$stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ?");
$stmt->execute([$car_id]);
$car = $stmt->fetch();

if (!$car) {
    $_SESSION['flash'] = 'Car not found.';
    header('Location: bookings.php');
    exit;
}

// Fetch all available cars
$stmt = $pdo->prepare("SELECT * FROM cars WHERE status = 'available'");
$stmt->execute();
$cars = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];
    $car_id = $_POST['car_id'];

    if ($start && $end && $start <= $end) {
        $priceStmt = $pdo->prepare('SELECT price_per_day FROM cars WHERE id = ?');
        $priceStmt->execute([$car_id]);
        $price = $priceStmt->fetchColumn();

        $days = (new DateTime($start))->diff(new DateTime($end))->days + 1;
        $total = $days * $price;

        $stmt = $pdo->prepare('UPDATE bookings SET car_id=?, start_date=?, end_date=?, total_cost=? WHERE id=?');
        $stmt->execute([$car_id, $start, $end, $total, $id]);

        header('Location: bookings.php');
        exit;
    }

    $_SESSION['flash'] = 'Invalid booking dates.';
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Edit Booking</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="styles.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container py-4">
  <h1 class="mb-4">Edit Booking</h1>

  <form method="post" class="row g-3">
    <input type="hidden" name="car_id" value="<?php echo $car['id']; ?>">

    <div class="col-md-6">
    <label class="form-label">Car</label>
      <select name="car_id" class="form-select" required>
        <option value="">Select a car</option>
        <?php foreach ($cars as $c): ?>
          <option value="<?php echo $c['id']; ?>" <?php echo $c['id'] == $car['id'] ? 'selected' : ''; ?>>
            <?php echo htmlspecialchars($c['brand'] . ' ' . $c['model'] . ' - RM' . $c['price_per_day'] . '/day'); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="col-md-3">
      <label class="form-label">Start Date</label>
      <input name="start_date" type="date" class="form-control" value="<?php echo $booking['start_date']; ?>" required>
    </div>

    <div class="col-md-3">
      <label class="form-label">End Date</label>
      <input name="end_date" type="date" class="form-control" value="<?php echo $booking['end_date']; ?>" required>
    </div>

    <div class="btn-sv-cncl">
      <button class="btn btn-success">Update</button>
      <a href="bookings.php" class="btn btn-secondary">Cancel</a>
    </div>
  </form>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
