<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once $_SERVER['DOCUMENT_ROOT'] . '/new_project_bk/index.php';

if (isset($_SESSION['user_id'])) {
    $stmt = $mysqli->prepare("UPDATE users SET remember_token = NULL WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $stmt->close();
    }
}

session_unset();
session_destroy();

setcookie(
    "remember_me",
    "",
    [
        'expires' => time() - 3600,
        'path' => '/',
        'httponly' => true,
        'secure' => isset($_SERVER['HTTPS']),
        'samesite' => 'Lax'
    ]
);

header("Location: " . BASE_URL . "views/auth/login.php");
exit;
