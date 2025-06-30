<?php
require_once '../config/config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$car = null;
if (isset($_GET['car_id'])) {
    $car_id = (int)$_GET['car_id'];
    $stmt = $pdo->prepare("SELECT * FROM cars WHERE id = :id AND status = 'available'");
    $stmt->bindValue(':id', $car_id, PDO::PARAM_INT);
    $stmt->execute();
    $car = $stmt->fetch();

    if (!$car) {
        $_SESSION['flash'] = 'Selected car is not available.';
        header('Location: cars.php');
        exit;
    }
} else {
    $_SESSION['flash'] = 'No car selected.';
    header('Location: cars.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $car_id = (int)$_POST['car_id'];
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];

    if (!$car_id || !$start || !$end || $start > $end) {
        $_SESSION['flash'] = 'Invalid dates.';
        header("Location: add_booking.php?car_id=$car_id");
        exit;
    }

    $stmt = $pdo->prepare('SELECT price_per_day FROM cars WHERE id=?');
    $stmt->execute([$car_id]);
    $price = $stmt->fetchColumn();
    $days = (new DateTime($start))->diff(new DateTime($end))->days + 1;
    $total = $days * $price;

    $stmt = $pdo->prepare('INSERT INTO bookings (user_id, car_id, start_date, end_date, total_cost, status) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->execute([$_SESSION['user_id'], $car_id, $start, $end, $total, 'pending']);
    header('Location: bookings.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>New Booking</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="styles.css" rel="stylesheet">
</head>
<body>
  <?php include 'navbar.php'; ?>
  <div class="container py-4">
    <h1 class="mb-4">Add Booking</h1>

  <form method="post" class="row g-3">
    <input type="hidden" name="car_id" value="<?php echo $car['id']; ?>">

    <div class="col-md-6">
      <label class="form-label">Car</label>
      <input type="text" class="form-control" value="<?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?>" disabled>
    </div>

    <div class="col-md-3">
      <label class="form-label">Start Date</label>
      <input name="start_date" type="date" class="form-control" required>
    </div>

    <div class="col-md-3">
      <label class="form-label">End Date</label>
      <input name="end_date" type="date" class="form-control" required>
    </div>

    <div class="btn-sv-cncl">
      <button class="btn btn-success">Save</button>
      <a href="cars.php" class="btn btn-secondary">Cancel</a>
    </div>
  </form>
  </div>
  <?php include 'footer.php'; ?>
</body>
</html>
