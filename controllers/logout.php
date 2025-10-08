<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../db/db.php';
include '../layout/layout.php';

if (isset($_SESSION['user_id'])) {
    $stmt = $mysqli->prepare("UPDATE users SET remember_token = NULL WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $stmt->close();
}

session_unset();
session_destroy();

setcookie("remember_me", "", time() - 3600, "/");

header("Location: ../views/auth/login.php");
exit;
