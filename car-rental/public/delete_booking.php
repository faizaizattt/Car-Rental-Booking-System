<?php
require_once '../config/config.php';
if (!isset($_SESSION['user_id'])) { header('Location: index.php'); exit; }

$id = (int)($_GET['id'] ?? 0);
$isAdmin = $_SESSION['role'] === 'admin';
$booking = $pdo->prepare('SELECT * FROM bookings WHERE id=?');
$booking->execute([$id]);
$booking = $booking->fetch();

if ($booking && ($isAdmin || $booking['user_id'] == $_SESSION['user_id'])) {
    $pdo->prepare('DELETE FROM bookings WHERE id=?')->execute([$id]);
}

header('Location: bookings.php');