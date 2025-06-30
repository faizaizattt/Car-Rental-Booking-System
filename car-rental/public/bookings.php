<?php
require_once '../config/config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
if (isset($_GET['car_id'])) {
    $car_id = $_GET['car_id'];
    $stmt = $pdo->prepare("SELECT * FROM cars WHERE id = :id");
    $stmt->bindValue(':id', $car_id, PDO::PARAM_INT);
    $stmt->execute();
    $car = $stmt->fetch();
}

$isAdmin = $_SESSION['role']==='admin';
$uid = $_SESSION['user_id'];
$sql = 'SELECT b.*, c.brand, c.model FROM bookings b JOIN cars c ON c.id = b.car_id';
$sql .= $isAdmin ? '' : ' WHERE b.user_id = ?';
$stmt = $pdo->prepare($sql);
$stmt->execute($isAdmin?[]:[$uid]);
$bookings = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Bookings - Car Rental</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Your Bookings</h2>
    <a href="add_booking.php" class="btn btn-sm btn-primary">+ Add Booking</a>
  </div>
  <table class="table table-bordered table-sm">
    <thead><tr><th>#</th><th>Car</th><th>Start</th><th>End</th><th>Total (RM)</th><th>Status</th><th>Action</th></tr></thead>
    <tbody>
      <?php foreach ($bookings as $b): ?>
      <tr>
        <td><?php echo $b['id']; ?></td>
        <td><?php echo htmlspecialchars($b['brand'].' '.$b['model']); ?></td>
        <td><?php echo $b['start_date']; ?></td>
        <td><?php echo $b['end_date']; ?></td>
        <td><?php echo number_format($b['total_cost'],2); ?></td>
        <td><span class="badge bg-<?php echo $b['status']==='confirmed'?'success':'secondary'; ?>"><?php echo $b['status']; ?></span></td>
        <td>
          <a href="edit_booking.php?id=<?php echo $b['id']; ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
          <a href="delete_booking.php?id=<?php echo $b['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Cancel this booking?');">Del</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include 'footer.php'; ?>
</body>
</html>