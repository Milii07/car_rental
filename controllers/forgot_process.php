<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../db/db.php';
include '../views/layout/layout.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = "Kjo faqe duhet të aksesohet me POST.";
    header("Location: ../../auth/forgot.php");
    exit;
}

$email = trim($_POST['email'] ?? '');

if (empty($email)) {
    $_SESSION['error'] = "Ju lutem shkruani emailin e përdoruesit.";
    header("Location: ../../auth/forgot.php");
    exit;
}

$stmt = $mysqli->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
if (!$stmt) {
    $_SESSION['error'] = "Gabim gjatë përgatitjes së query-së: " . $mysqli->error;
    header("Location: ../../auth/forgot.php");
    exit;
}
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    $token = bin2hex(random_bytes(16));
    $expires = date("Y-m-d H:i:s", time() + 3600);

    $stmt_delete = $mysqli->prepare("DELETE FROM password_resets WHERE user_id = ?");
    if ($stmt_delete) {
        $stmt_delete->bind_param("i", $user['id']);
        $stmt_delete->execute();
        $stmt_delete->close();
    }


    $stmt_insert = $mysqli->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)");
    if ($stmt_insert) {
        $stmt_insert->bind_param("iss", $user['id'], $token, $expires);
        $stmt_insert->execute();
        $stmt_insert->close();
    }

    header("Location: ../views/auth/reset_password.php?token=" . urlencode($token));
    exit;
} else {
    $_SESSION['error'] = "Përdoruesi nuk u gjet.";
    header("Location:  ../../auth/forgot.php");
    exit;
}
