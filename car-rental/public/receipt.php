<?php
require_once '../config/config.php';
// Check user login
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

// Fetch booking info
$sql = 'SELECT b.*, c.brand, c.model FROM bookings b 
        JOIN cars c ON c.id = b.car_id
        WHERE b.id = ? AND b.user_id = ?';
$stmt = $pdo->prepare($sql);
$stmt->execute([$bookingId, $userId]);
$booking = $stmt->fetch();

if (!$booking) {
    die('Booking not found.');
}

// Generate a transaction ID for display
$transactionId = strtoupper(uniqid('TXN'));
?>
<!DOCTYPE html>
<html>
<head>
    <title>Receipt - Car Rental</title>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="receipt-container">
    <h2>Payment Receipt</h2>
    <div class="details">
        <p><strong>Transaction ID:</strong> <?= htmlspecialchars($transactionId) ?></p>
        <p><strong>Car:</strong> <?= htmlspecialchars($booking['brand'] . ' ' . $booking['model']) ?></p>
        <p><strong>Pickup Date:</strong> <?= date('D, j M Y', strtotime($booking['start_date'])) ?></p>
        <p><strong>Return Date:</strong> <?= date('D, j M Y', strtotime($booking['end_date'])) ?></p>
        <p><strong>Total Paid:</strong> RM <?= number_format($booking['total_cost'], 2) ?></p>
        <p><strong>Date Issued:</strong> <?= date('Y-m-d H:i:s') ?></p>
    </div>

    <button class="print-btn" onclick="window.print()">Print Receipt</button>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
