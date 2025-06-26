<?php
require_once '../config/config.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role']!=='admin') {
    header('Location: index.php'); exit;
}
$id=(int)($_GET['id'] ?? 0);
$pdo->prepare('DELETE FROM cars WHERE id=?')->execute([$id]);
header('Location: cars.php');