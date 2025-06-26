<?php
require_once '../config/config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Load available cars only
$cars = $pdo->query("SELECT id,brand,model,price_per_day FROM cars WHERE status='available'")->fetchAll();

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $car_id = (int)$_POST['car_id'];
    $start  = $_POST['start_date'];
    $end    = $_POST['end_date'];

    // Simple validation (date logic etc.)
    if (!$car_id || !$start || !$end || $start>$end) {
        $_SESSION['flash']='Invalid dates.'; header('Location: add_booking.php'); exit;
    }

    // Get price
    $stmt = $pdo->prepare('SELECT price_per_day FROM cars WHERE id=?');
    $stmt->execute([$car_id]);
    $price = $stmt->fetchColumn();
    $days  = (new DateTime($start))->diff(new DateTime($end))->days + 1;
    $total = $days * $price;

    $stmt = $pdo->prepare('INSERT INTO bookings (user_id,car_id,start_date,end_date,total_cost,status) VALUES (?,?,?,?,?,?)');
    $stmt->execute([$_SESSION['user_id'],$car_id,$start,$end,$total,'pending']);
    header('Location: bookings.php');
    exit;
}
?>
<!DOCTYPE html>
<html><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>New Booking</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="p-4">
<h3>Add Booking</h3>
<form method="post" class="row g-3">
  <div class="col-md-6">
    <label class="form-label">Car</label>
    <select name="car_id" class="form-select" required>
      <option value="">Choose...</option>
      <?php foreach($cars as $c): ?>
        <option value="<?php echo $c['id']; ?>"><?php echo $c['brand'].' '.$c['model'].' – RM'.number_format($c['price_per_day'],2).'/day'; ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-3"><label class="form-label">Start</label><input name="start_date" type="date" class="form-control" required></div>
  <div class="col-md-3"><label class="form-label">End</label><input name="end_date" type="date" class="form-control" required></div>
  <div class="col-12"><button class="btn btn-primary">Save</button> <a href="bookings.php" class="btn btn-secondary">Cancel</a></div>
</form>
</body></html>