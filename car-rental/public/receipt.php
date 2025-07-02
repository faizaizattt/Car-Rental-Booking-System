<?php
// receipt.php
session_start();

// Sample data - replace with real session or database data
$carName = $_SESSION['car_name'] ?? 'Toyota Vios';
$pickupDate = $_SESSION['pickup_date'] ?? '2025-07-05';
$returnDate = $_SESSION['return_date'] ?? '2025-07-07';
$totalPrice = $_SESSION['total_price'] ?? 320.00;

// For demonstration, create a random transaction ID
$transactionId = strtoupper(uniqid('TXN'));

?>

<!DOCTYPE html>
<html>
<head>
    <title>Receipt - Car Rental</title>
    <style>
        body {
            font-family: Arial;
            background-color: #f5f6fa;
            padding: 40px;
        }
        .receipt-container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px #ccc;
        }
        h2 {
            text-align: center;
        }
        .details {
            margin: 20px 0;
        }
        .details p {
            margin: 6px 0;
        }
        .print-btn {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-align: center;
            font-size: 16px;
        }
        .print-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="receipt-container">
    <h2>Payment Receipt</h2>
    <div class="details">
        <p><strong>Transaction ID:</strong> <?= htmlspecialchars($transactionId) ?></p>
        <p><strong>Car:</strong> <?= htmlspecialchars($carName) ?></p>
        <p><strong>Pickup Date:</strong> <?= htmlspecialchars($pickupDate) ?></p>
        <p><strong>Return Date:</strong> <?= htmlspecialchars($returnDate) ?></p>
        <p><strong>Total Paid:</strong> RM <?= number_format($totalPrice, 2) ?></p>
        <p><strong>Date:</strong> <?= date("Y-m-d H:i:s") ?></p>
    </div>

    <button class="print-btn" onclick="window.print()">Print Receipt</button>
</div>

</body>
</html>
