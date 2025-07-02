<?php
require_once '../config/config.php';
session_start();
session_destroy();

setcookie("user_email", "", time() - 3600, "/"); // unset the cookie

header("Location: index.php");
