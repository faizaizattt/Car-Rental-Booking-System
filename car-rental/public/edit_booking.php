<?php
require_once '../config/config.php';
if (!isset($_SESSION['user_id'])) { header('Location: index.php'); exit; }

$id = (int)($_GET['id'] ?? 0);
$isAdmin = $_SESSION['role'] === 'admin';
$booking = $pdo->prepare('SELECT * FROM bookings WHERE id=?');
$booking->execute([$id]);
$booking = $booking->fetch();
if (!$booking || (!$isAdmin && $booking['user_id'] != $_SESSION['user_id'])) {
    header('Location: bookings.php'); exit;
}

$cars = $pdo->query("SELECT id, brand, model FROM cars WHERE status='available' OR id=".$booking['car_id'])->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $car_id = (int)$_POST['car_id'];
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];
    $status = $_POST['status'];

    if ($start && $end && $start <= $end) {
        $priceStmt = $pdo->prepare('SELECT price_per_day FROM cars WHERE id=?');
        $priceStmt->execute([$car_id]);
        $price = $priceStmt->fetchColumn();

        $days = (new DateTime($start))->diff(new DateTime($end))->days + 1;
        $total = $days * $price;

        $stmt = $pdo->prepare('UPDATE bookings SET car_id=?, start_date=?, end_date=?, total_cost=?, status=? WHERE id=?');
        $stmt->execute([$car_id, $start, $end, $total, $status, $id]);

        header('Location: bookings.php'); exit;
    }
    $_SESSION['flash'] = 'Invalid booking data.';
}
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Edit Booking</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="p-4">
<h3>Edit Booking</h3>
<form method="post" class="row g-3">
  <div class="col-md-4">
    <label class="form-label">Car</label>
    <select name="car_id" class="form-select">
      <?php foreach ($cars as $car): ?>
        <option value="<?=$car['id']?>" <?=$car['id']==$booking['car_id']?'selected':''?>>
          <?=htmlspecialchars($car['brand'].' '.$car['model'])?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-3">
    <label class="form-label">Start Date</label>
    <input type="date" name="start_date" class="form-control" value="<?=$booking['start_date']?>" required>
  </div>
  <div class="col-md-3">
    <label class="form-label">End Date</label>
    <input type="date" name="end_date" class="form-control" value="<?=$booking['end_date']?>" required>
  </div>
  <div class="col-md-2">
    <label class="form-label">Status</label>
    <select name="status" class="form-select">
      <?php foreach(['pending','confirmed','cancelled'] as $s): ?>
        <option value="<?=$s?>" <?=$booking['status']===$s?'selected':''?>><?=$s?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-12">
    <button class="btn btn-primary">Update</button>
    <a href="bookings.php" class="btn btn-secondary">Cancel</a>
  </div>
</form>
</body></html>