<?php
require_once '../config/config.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role']!=='admin') {
    header('Location: index.php'); exit;
}
$id=(int)($_GET['id'] ?? 0);
$car=$pdo->prepare('SELECT * FROM cars WHERE id=?'); $car->execute([$id]); $car=$car->fetch();
if (!$car){ header('Location: cars.php'); exit; }

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $brand=trim($_POST['brand']); $model=trim($_POST['model']); $price=(float)$_POST['price_per_day'];
    $seats=(int)$_POST['seats']; $fuel=trim($_POST['fuel_type']); $status=$_POST['status'];
    if ($brand&&$model&&$price>0) {
        $stmt=$pdo->prepare('UPDATE cars SET brand=?,model=?,price_per_day=?,seats=?,fuel_type=?,status=? WHERE id=?');
        $stmt->execute([$brand,$model,$price,$seats,$fuel,$status,$id]);
        header('Location: cars.php'); exit;
    }
    $_SESSION['flash']='Invalid input.';
}
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Edit Car</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="p-4">
<h3>Edit Car</h3>
<form method="post" class="row g-3">
  <div class="col-md-4"><label class="form-label">Brand</label><input name="brand" class="form-control" value="<?=htmlspecialchars($car['brand'])?>" required></div>
  <div class="col-md-4"><label class="form-label">Model</label><input name="model" class="form-control" value="<?=htmlspecialchars($car['model'])?>" required></div>
  <div class="col-md-4"><label class="form-label">Price/Day (RM)</label><input type="number" step="0.01" name="price_per_day" class="form-control" value="<?=$car['price_per_day']?>" required></div>
  <div class="col-md-2"><label class="form-label">Seats</label><input type="number" name="seats" class="form-control" value="<?=$car['seats']?>" required></div>
  <div class="col-md-3"><label class="form-label">Fuel Type</label><input name="fuel_type" class="form-control" value="<?=htmlspecialchars($car['fuel_type'])?>" required></div>
  <div class="col-md-3"><label class="form-label">Status</label>
    <select name="status" class="form-select">
      <?php foreach(['available','maintenance','unavailable'] as $s): ?>
        <option value="<?=$s?>" <?=$car['status']===$s?'selected':''?>><?=$s?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-12"><button class="btn btn-primary">Update</button> <a href="cars.php" class="btn btn-secondary">Back</a></div>
</form>
</body></html>