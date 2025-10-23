<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once $_SERVER['DOCUMENT_ROOT'] . '/new_project_bk/index.php';
include '../../db/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {
        $_SESSION['error'] = "Ju lutem vendosni email-in.";
        header("Location: ../auth/login.php?role=client");
        exit;
    }

    $stmt = $mysqli->prepare("SELECT id, full_name FROM clients WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($client = $result->fetch_assoc()) {

        $_SESSION['client_id'] = $client['id'];
        $_SESSION['client_name'] = $client['full_name'];

        header("Location: " . BASE_URL . "views/general/home/list.php");
        exit;
    } else {
        $_SESSION['error'] = "Email-i nuk u gjet si klient.";
        header("Location: ../auth/login.php?role=client");
        exit;
    }
} else {
    header("Location: ../auth/login.php?role=client");
    exit;
}
