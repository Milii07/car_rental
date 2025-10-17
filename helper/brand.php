<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(__DIR__) . '/index.php';

if (isset($_POST['create'])) {
    $name = trim($_POST['name']);
    if (!empty($name)) {
        $stmt = $mysqli->prepare("INSERT INTO brands (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->close();
        $_SESSION['message'] = "Brand u shtua me sukses!";
    } else {
        $_SESSION['error'] = "Emri i brand-it nuk mund të jetë bosh!";
    }
    header("Location: " . BASE_URL . "views/general/brands/list.php");
    exit;
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = trim($_POST['name']);
    if (!empty($name)) {
        $stmt = $mysqli->prepare("UPDATE brands SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $id);
        $stmt->execute();
        $stmt->close();
        $_SESSION['message'] = "Brand u përditësua me sukses!";
    } else {
        $_SESSION['error'] = "Emri i brand-it nuk mund të jetë bosh!";
    }
    header("Location: " . BASE_URL . "views/general/brands/list.php");
    exit;
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $mysqli->prepare("DELETE FROM brands WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    $_SESSION['message'] = "Brand u fshi me sukses!";
    header("Location: " . BASE_URL . "views/general/brands/list.php");
    exit;
}

$result = $mysqli->query("SELECT * FROM brands ORDER BY id DESC");
