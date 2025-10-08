<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);


include $_SERVER['DOCUMENT_ROOT'] . '/new_project_bk/db/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['error'] = "Ju lutem plotësoni të gjitha fushat.";
        header("Location: /new_project_bk/views/auth/register.php");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Adresa e email-it nuk është e vlefshme.";
        header("Location: /new_project_bk/views/auth/register.php");
        exit;
    }

    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Fjalëkalimet nuk përputhen.";
        header("Location: /new_project_bk/views/auth/register.php");
        exit;
    }

    $strongPasswordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';
    if (!preg_match($strongPasswordPattern, $password)) {
        $_SESSION['error'] = "Fjalëkalimi duhet të ketë të paktën 8 karaktere, shkronja të mëdha, të vogla, numra dhe karaktere speciale.";
        header("Location: /new_project_bk/views/auth/register.php");
        exit;
    }

    $stmt = $mysqli->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $_SESSION['error'] = "Email ekziston tashmë.";
        $stmt->close();
        header("Location: /new_project_bk/views/auth/register.php");
        exit;
    }
    $stmt->close();


    $password_hash = password_hash($password, PASSWORD_DEFAULT);


    $stmt = $mysqli->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password_hash);
    if ($stmt->execute()) {
        $_SESSION['success'] = "Regjistrimi u krye me sukses! Mund të identifikohesh tani.";
        $stmt->close();
        header("Location: /new_project_bk/views/auth/login.php");
        exit;
    } else {
        $_SESSION['error'] = "Gabim gjatë regjistrimit: " . $stmt->error;
        $stmt->close();
        header("Location: /new_project_bk/views/auth/register.php");
        exit;
    }
} else {
    header("Location: /new_project_bk/views/auth/register.php");
    exit;
}
