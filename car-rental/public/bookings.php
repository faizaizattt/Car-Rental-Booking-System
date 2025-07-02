<?php
require_once '../config/config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$isAdmin = $_SESSION['role'] === 'admin';
$uid = $_SESSION['user_id'];

// Query includes user name if admin
$sql = 'SELECT b.*, c.brand, c.model, c.img_link, u.name AS customer_name 
        FROM bookings b 
        JOIN cars c ON c.id = b.car_id 
        JOIN users u ON u.id = b.user_id';
$sql .= $isAdmin ? '' : ' WHERE b.user_id = ?';

$stmt = $pdo->prepare($sql);
$stmt->execute($isAdmin ? [] : [$uid]);
$bookings = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $isAdmin ? 'All Bookings' : 'Your Bookings' ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="styles.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container py-4">
  <h2 class="mb-4"><?= $isAdmin ? 'All Bookings' : 'Your Bookings' ?></h2>

  <?php if (empty($bookings)): ?>
    <p>No bookings found.</p>
  <?php else: ?>
    <?php foreach ($bookings as $b): ?>
      <div class="booking-card">
        <img src="<?= htmlspecialchars($b['img_link'] ?? 'default_car.png'); ?>" alt="Car Image">

        <div class="booking-info">
          <h5><?= htmlspecialchars($b['brand'] . ' ' . $b['model']); ?></h5>
          <div class="row">
            <?php if ($isAdmin): ?>
            <div class="col-md-4">
              <strong>Customer:</strong> <?= htmlspecialchars($b['customer_name']); ?>
            </div>
          <?php endif; ?>
            <div class="col-md-4">
              <strong>Pick-Up:</strong><br>
              <?= date('D, j M', strtotime($b['start_date'])); ?>
            </div>
            <div class="col-md-4">
              <strong>Drop-Off:</strong><br>
              <?= date('D, j M', strtotime($b['end_date'])); ?>
            </div>
          </div>

          <div class="mt-2">
            <strong>Total Cost:</strong> RM<?= number_format($b['total_cost'], 2); ?>
          </div>

          <?php if (!$isAdmin): ?>
            <div class="booking-actions">
              <?php if ($b['status'] === 'pending'): ?>
                <a href="edit_booking.php?id=<?= $b['id']; ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                <a href="payment.php?booking_id=<?= $b['id']; ?>" class="btn btn-sm btn-success">Pay Now</a>
                <a href="delete_booking.php?id=<?= $b['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Cancel this booking?');">Cancel</a>
              <?php elseif ($b['status'] === 'confirmed'): ?>
                <a href="receipt.php?booking_id=<?= $b['id']; ?>" class="btn btn-sm btn-success">View Receipt</a>
              <?php endif; ?>
            </div>
          <?php endif; ?>
        </div>

        <div class="booking-status <?= $b['status'] === 'confirmed' ? 'status-completed' : 'status-pending'; ?>">
          <?= $b['status'] === 'confirmed' ? 'Completed' : 'Pending'; ?>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
