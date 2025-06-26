<?php
require_once '../config/config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
$isAdmin = $_SESSION['role'] === 'admin';
$sql = 'SELECT * FROM cars';
if (isset($_GET['q']) && $_GET['q'] !== '') {
    $q = '%' . $_GET['q'] . '%';
    $sql .= ' WHERE brand LIKE ? OR model LIKE ?';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$q,$q]);
} else {
    $stmt = $pdo->query($sql);
}
$cars = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Cars - Car Rental</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="styles.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php';?>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Cars View</h2>
    <?php if ($isAdmin): ?>
      <a href="add_car.php" class="btn btn-sm btn-primary">+ Add Car</a>
    <?php endif; ?>
  </div>
  <form class="row mb-3">
    <div class="col-md-4"><input type="text" name="q" placeholder="Search brand or model" class="form-control" value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>"></div>
    <div class="col-md-2"><button class="btn btn-secondary">Search</button></div>
  </form>
  <table class="table table-striped table-hover">
    <thead><tr><th>#</th><th>Brand</th><th>Model</th><th>Seats</th><th>Fuel</th><th>Price/Day</th><th>Status</th><?php if ($isAdmin) echo '<th>Action</th>'; ?></tr></thead>
    <tbody>
      <?php foreach ($cars as $c): ?>
        <tr>
          <td><?php echo $c['id']; ?></td>
          <td><?php echo htmlspecialchars($c['brand']); ?></td>
          <td><?php echo htmlspecialchars($c['model']); ?></td>
          <td><?php echo $c['seats']; ?></td>
          <td><?php echo htmlspecialchars($c['fuel_type']); ?></td>
          <td>RM <?php echo number_format($c['price_per_day'],2); ?></td>
          <td><span class="badge bg-<?php echo $c['status']==='available'?'success':'warning'; ?>"><?php echo $c['status']; ?></span></td>
          <?php if ($isAdmin): ?>
          <td>
            <a href="edit_car.php?id=<?php echo $c['id']; ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
            <a href="delete_car.php?id=<?php echo $c['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this car?');">Del</a>
          </td>
          <?php endif; ?>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
</body>
</html>