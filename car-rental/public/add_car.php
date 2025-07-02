<?php
require_once '../config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
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

    $stmt = $pdo->prepare("INSERT INTO cars (brand, model, seats, price_per_day, fuel_type, img_link, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$brand, $model, $seats, $price, $fuel, $img, $status]);

    header('Location: cars.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Car</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
  <?php include 'navbar.php'; ?>
  <div class="container py-5">
    <div class="card shadow p-4" style="max-width: 600px; margin: auto;">
      <h2 class="mb-4 text-center">Add New Car</h2>

      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Brand</label>
          <input type="text" name="brand" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Model</label>
          <input type="text" name="model" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Seats</label>
          <input type="number" name="seats" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Price Per Day (RM)</label>
          <input type="number" step="0.01" name="price" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Fuel Type</label>
          <input type="text" name="fuel" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Image URL</label>
          <input type="text" name="img" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Status</label>
          <select name="status" class="form-select" required>
            <option value="available">Available</option>
            <option value="unavailable">Unavailable</option>
          </select>
        </div>

        <button type="submit" class="btn btn-success w-100">Add Car</button>
      </form>
    </div>
  </div>
  <?php include 'footer.php'; ?>
</body>
</html>
