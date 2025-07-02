<?php
// payment.php
require_once '../config/config.php';

// session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$userId = $_SESSION['user_id'];

// Validate booking_id
if (!isset($_GET['booking_id']) || !is_numeric($_GET['booking_id'])) {
    die('Invalid booking ID.');
}

$bookingId = (int)$_GET['booking_id'];

// Load booking data
$sql = 'SELECT b.*, c.brand, c.model FROM bookings b 
        JOIN cars c ON c.id = b.car_id
        WHERE b.id = ? AND b.user_id = ?';
$stmt = $pdo->prepare($sql);
$stmt->execute([$bookingId, $userId]);
$booking = $stmt->fetch();

if (!$booking) {
    die('Booking not found.');
}

// If form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Normally, process payment here
    // Update booking status to confirmed
    $update = $pdo->prepare('UPDATE bookings SET status = "confirmed" WHERE id = ?');
    $update->execute([$bookingId]);

    // Redirect to receipt page
    header('Location: receipt.php?booking_id=' . $bookingId);
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment - Car Rental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container py-5">
    <h2>Confirm Payment</h2>

    <div class="card mb-4">
        <div class="text-start">
            <div class="card-body">
                <h5><?php echo htmlspecialchars($booking['brand'] . ' ' . $booking['model']); ?></h5>
                <p><strong>Pick-Up:</strong> <?php echo date('D, j M Y', strtotime($booking['start_date'])); ?></p>
                <p><strong>Drop-Off:</strong> <?php echo date('D, j M Y', strtotime($booking['end_date'])); ?></p>
                <p><strong>Total:</strong> RM<?php echo number_format($booking['total_cost'], 2); ?></p>
            </div>

        </div>
    </div>

    <form method="POST">
        <div class="text-start">
            <div class="mb-3">
                <label>Cardholder Name</label>
                <input type="text" name="card_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Card Number</label>
                <input type="text" name="card_number" class="form-control" required maxlength="16">
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Expiry Date (MM/YY)</label>
                    <input type="text" name="expiry" class="form-control" required placeholder="MM/YY">
                </div>
                <div class="col-md-6 mb-3">
                    <label>CVV</label>
                    <input type="text" name="cvv" class="form-control" required maxlength="3">
                </div>
            </div>
            <button class="btn btn-success" type="submit">Pay Now</button>
        </div>
    </form>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
