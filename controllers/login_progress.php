<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_lifetime' => 86400,
        'cookie_path' => '/',
        'cookie_secure' => false,
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax'
    ]);
}

include_once $_SERVER['DOCUMENT_ROOT'] . '/new_project_bk/index.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "views/auth/login.php");
    exit;
}

if (isset($_POST['guest_login'])) {

    if (!isset($_SESSION['guest_id'])) {
        $_SESSION['guest_id'] = rand(100000, 999999);
    }

    unset($_SESSION['user_id']);
    unset($_SESSION['is_admin']);

    $_SESSION['username'] = "Guest #" . $_SESSION['guest_id'];

    error_log("Guest Login: guest_id=" . $_SESSION['guest_id']);

    header("Location: " . BASE_URL . "views/general/main_page/our_fleet.php");
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$remember = isset($_POST['remember']);

if (empty($email) || empty($password)) {
    $_SESSION['error'] = "Ju lutem plotësoni të gjitha fushat.";
    header("Location: " . BASE_URL . "views/auth/login.php");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Email-i nuk është i saktë.";
    header("Location: " . BASE_URL . "views/auth/login.php");
    exit;
}

$stmt = $mysqli->prepare("
    SELECT id, username, password, is_admin 
    FROM users 
    WHERE email = ? 
    LIMIT 1
");

if (!$stmt) {
    $_SESSION['error'] = "Gabim gjatë përpunimit të kërkesës.";
    header("Location: " . BASE_URL . "views/auth/login.php");
    exit;
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    $_SESSION['error'] = "Përdoruesi nuk u gjet.";
    header("Location: " . BASE_URL . "views/auth/login.php");
    exit;
}

if (!password_verify($password, $user['password'])) {
    $_SESSION['error'] = "Fjalëkalimi është i pasaktë.";
    header("Location: " . BASE_URL . "views/auth/login.php");
    exit;
}

unset($_SESSION['guest_id']);

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

header("Location: " . BASE_URL . "views/general/home/list.php");
exit;
