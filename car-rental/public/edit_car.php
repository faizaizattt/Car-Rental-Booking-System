<?php
require_once '../config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: cars.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ?");
$stmt->execute([$id]);
$car = $stmt->fetch();

if (!$car) {
    echo "Car not found.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $seats = $_POST['seats'];
    $price = $_POST['price'];
    $fuel = $_POST['fuel'];
    $img = $_POST['img'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE cars SET brand=?, model=?, seats=?, price_per_day=?, fuel_type=?, img_link=?, status=? WHERE id=?");
    $stmt->execute([$brand, $model, $seats, $price, $fuel, $img, $status, $id]);

    header('Location: cars.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Car</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
  <?php include 'navbar.php'; ?>
  <div class="container py-5">
    <div class="card shadow p-4" style="max-width: 600px; margin: auto;">
      <h2 class="mb-4 text-center">Edit Car</h2>

      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Brand</label>
          <input type="text" name="brand" class="form-control" value="<?= htmlspecialchars($car['brand']) ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Model</label>
          <input type="text" name="model" class="form-control" value="<?= htmlspecialchars($car['model']) ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Seats</label>
          <input type="number" name="seats" class="form-control" value="<?= $car['seats'] ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Price Per Day (RM)</label>
          <input type="number" step="0.01" name="price" class="form-control" value="<?= $car['price_per_day'] ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Fuel Type</label>
          <input type="text" name="fuel" class="form-control" value="<?= htmlspecialchars($car['fuel_type']) ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Image URL</label>
          <input type="text" name="img" class="form-control" value="<?= htmlspecialchars($car['img_link']) ?>" required>
        </div>

        <div class="mb-3 text-center">
          <label class="form-label d-block">Preview:</label>
          <img src="<?= htmlspecialchars($car['img_link']) ?>" alt="Car Image" class="img-fluid" style="max-height: 200px;">
        </div>

        <div class="mb-3">
          <label class="form-label">Status</label>
          <select name="status" class="form-select" required>
            <option value="available" <?= $car['status'] === 'available' ? 'selected' : '' ?>>Available</option>
            <option value="unavailable" <?= $car['status'] === 'unavailable' ? 'selected' : '' ?>>Unavailable</option>
          </select>
        </div>

        <button type="submit" class="btn btn-success w-100">Update Car</button>
      </form>
    </div>
  </div>
  <?php include 'footer.php'; ?>
</body>
</html>
