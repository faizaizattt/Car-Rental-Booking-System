<?php
// payment.php

// Start session if not already started
// session_start();

// Sample data (replace with actual session or GET/POST data)
$carName = $_SESSION['car_name'] ?? 'Toyota Vios';
$pickupDate = $_SESSION['pickup_date'] ?? '2025-07-05';
$returnDate = $_SESSION['return_date'] ?? '2025-07-07';
$totalPrice = $_SESSION['total_price'] ?? 320.00;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // In a real system, here you'd validate and process the payment.
    // For now, just redirect to a confirmation page
    header('Location: confirmation.php?success=1');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment Page - Car Rental</title>
    <style>
        /* .container {
            font-family: Arial;
            background-color: #f0f2f5;
            padding: 40px;
        } */
        .payment-container {
            max-width: 1000px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px #ccc;
            margin-top: 40px;
        }
        .payment-container h2 {
            text-align: center;
        }
        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 10px;
            margin: 12px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        button {
            background-color: #0d5a46;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        .summary {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="payment-container">
    <h2>Confirm and Pay</h2>
    <div class="summary">
        <p><strong>Car:</strong> <?= htmlspecialchars($carName) ?></p>
        <p><strong>Pickup Date:</strong> <?= htmlspecialchars($pickupDate) ?></p>
        <p><strong>Return Date:</strong> <?= htmlspecialchars($returnDate) ?></p>
        <p><strong>Total Price:</strong> RM <?= number_format($totalPrice, 2) ?></p>
    </div>

    <form method="POST">
        <label>Cardholder Name</label>
        <input type="text" name="card_name" required>

        <label>Card Number</label>
        <input type="text" name="card_number" maxlength="16" required>

        <label>Expiry Date (MM/YY)</label>
        <input type="text" name="expiry_date" placeholder="MM/YY" required>

        <label>CVV</label>
        <input type="number" name="cvv" maxlength="3" required>

        <button type="submit">Pay Now</button>
    </form>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
