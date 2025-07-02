<?php
// confirmation.php
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment Confirmation</title>
    <style>
        .confirmation-container {
            font-family: Arial;
            padding: 40px;
            background-color: #f0f2f5;
            text-align: center;
        }
        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>
    <h2>Thank you!</h2>
    <p>Your payment was successful, and your car has been booked.</p>

    <a href="receipt.php" class="btn">View Receipt</a>
<?php include 'footer.php'; ?>
</body>
</html>
