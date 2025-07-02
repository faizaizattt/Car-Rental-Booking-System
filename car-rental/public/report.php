<?php
require_once '../config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$reportType = $_GET['type'] ?? 'booking';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-4">
    <h2 class="mb-4">Generate Report</h2>

    <!-- Report Type Selection -->
    <form method="get" class="mb-4">
        <label for="type" class="form-label">Select Report Type:</label>
        <select name="type" id="type" class="form-select w-25 d-inline-block" onchange="this.form.submit()">
            <option value="user" <?= $reportType == 'user' ? 'selected' : '' ?>>User Report</option>
            <option value="booking" <?= $reportType == 'booking' ? 'selected' : '' ?>>Booking Report</option>
            <option value="car" <?= $reportType == 'car' ? 'selected' : '' ?>>Car Report</option>
            <option value="feedback" <?= $reportType == 'feedback' ? 'selected' : '' ?>>Feedback Report</option>
        </select>
    </form>

    <?php
    if ($reportType == 'user') {
        $stmt = $pdo->query("SELECT id, name, email, role, created_at FROM users");
        $data = $stmt->fetchAll();
    ?>
        <h4>User Report</h4>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Created At</th></tr>
            </thead>
            <tbody>
                <?php foreach ($data as $row): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= $row['role'] ?></td>
                    <td><?= $row['created_at'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php
    } elseif ($reportType == 'booking') {
        $stmt = $pdo->query("SELECT b.id, u.name AS user_name, c.brand, c.model, b.start_date, b.end_date, b.total_cost
                             FROM bookings b
                             JOIN users u ON b.user_id = u.id
                             JOIN cars c ON b.car_id = c.id
                             ORDER BY b.start_date DESC");
        $data = $stmt->fetchAll();
    ?>
        <h4>Booking Report</h4>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr><th>ID</th><th>User</th><th>Car</th><th>Start Date</th><th>End Date</th><th>Total Cost (RM)</th></tr>
            </thead>
            <tbody>
                <?php foreach ($data as $row): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['user_name']) ?></td>
                    <td><?= htmlspecialchars($row['brand'] . ' ' . $row['model']) ?></td>
                    <td><?= $row['start_date'] ?></td>
                    <td><?= $row['end_date'] ?></td>
                    <td><?= number_format($row['total_cost'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php
    } elseif ($reportType == 'car') {
        $stmt = $pdo->query("SELECT id, brand, model, price_per_day, seats, fuel_type, status FROM cars");
        $data = $stmt->fetchAll();
    ?>
        <h4>Car Report</h4>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr><th>ID</th><th>Brand</th><th>Model</th><th>Price/Day (RM)</th><th>Seats</th><th>Fuel</th><th>Status</th></tr>
            </thead>
            <tbody>
                <?php foreach ($data as $row): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['brand']) ?></td>
                    <td><?= htmlspecialchars($row['model']) ?></td>
                    <td><?= number_format($row['price_per_day'], 2) ?></td>
                    <td><?= $row['seats'] ?></td>
                    <td><?= $row['fuel_type'] ?></td>
                    <td><?= ucfirst($row['status']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php
    } elseif ($reportType == 'feedback') {
        $stmt = $pdo->query("SELECT f.id, u.name AS user_name, f.rating, f.comment, f.created_at 
                             FROM feedback f 
                             JOIN users u ON f.user_id = u.id 
                             ORDER BY f.created_at DESC");
        $data = $stmt->fetchAll();
    ?>
        <h4>Feedback Report</h4>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr><th>ID</th><th>User</th><th>Rating</th><th>Comment</th><th>Date</th></tr>
            </thead>
            <tbody>
                <?php foreach ($data as $row): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['user_name']) ?></td>
                    <td><?= $row['rating'] ?></td>
                    <td><?= htmlspecialchars($row['comment']) ?></td>
                    <td><?= $row['created_at'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php } ?>

    <div class="text-end mt-3">
        <button onclick="printReport()" class="btn btn-secondary">Print Report</button>
    </div>
    <script>
    function printReport() {
        // Get the report title and table
        const container = document.querySelector('.container');
        // Find the first <h4> (report title) and the first <table> after it
        const title = container.querySelector('h4');
        const table = container.querySelector('table');
        if (!title || !table) {
            window.print(); // fallback
            return;
        }
        // Create a new window for printing
        const printWindow = window.open('', '', 'width=900,height=700');
        printWindow.document.write(`
            <html>
            <head>
                <title>Print Report</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            </head>
            <body>
                <div class="container mt-4">
                    ${title.outerHTML}
                    ${table.outerHTML}
                </div>
            </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.focus();
        printWindow.onload = function() {
            printWindow.print();
            printWindow.close();
        };
    }
    </script>
</div>
<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
