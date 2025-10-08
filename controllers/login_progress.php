<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Ju lutem plotësoni të gjitha fushat.";
        header("Location: ../views/auth/login.php");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Email-i nuk është i saktë.";
        header("Location: ../views/auth/login.php");
        exit;
    }

    $stmt = $mysqli->prepare("SELECT id, username, password, is_admin FROM users WHERE email = ? LIMIT 1");
    if (!$stmt) {
        $_SESSION['error'] = "Gabim gjatë përpunimit të kërkesës.";
        header("Location: ../views/auth/login.php");
        exit;
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = $user['is_admin'];

            if ($remember) {
                $token = bin2hex(random_bytes(16));
                $hashedToken = password_hash($token, PASSWORD_DEFAULT);

                $stmtToken = $mysqli->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
                if ($stmtToken) {
                    $stmtToken->bind_param("si", $hashedToken, $user['id']);
                    $stmtToken->execute();
                    $stmtToken->close();
                }

                setcookie(
                    'remember_me',
                    $user['id'] . ':' . $token,
                    [
                        'expires' => time() + (30 * 24 * 60 * 60),
                        'path' => '/',
                        'httponly' => true,
                        'secure' => isset($_SERVER['HTTPS']),
                        'samesite' => 'Lax'
                    ]
                );
            }

            header("Location: ../views/general/home/list.php");
            exit;
        } else {
            $_SESSION['error'] = "Fjalëkalimi është i pasaktë.";
        }
    } else {
        $_SESSION['error'] = "Përdoruesi nuk u gjet.";
    }

    $stmt->close();
    header("Location: ../views/auth/login.php");
    exit;
} else {
    header("Location: ../views/auth/login.php");
    exit;
}
