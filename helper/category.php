<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(__DIR__) . '/index.php';

function categoryExists($mysqli, $name, $excludeId = null)
{
    if ($excludeId) {
        $stmt = $mysqli->prepare("SELECT id FROM categories WHERE name = ? AND id <> ? LIMIT 1");
        $stmt->bind_param("si", $name, $excludeId);
    } else {
        $stmt = $mysqli->prepare("SELECT id FROM categories WHERE name = ? LIMIT 1");
        $stmt->bind_param("s", $name);
    }
    $stmt->execute();
    $stmt->store_result();
    $exists = $stmt->num_rows > 0;
    $stmt->close();
    return $exists;
}

if (isset($_POST['create'])) {
    $name = trim($_POST['name']);

    if (empty($name)) {
        $_SESSION['error'] = "Emri i kategorisë nuk mund të jetë bosh!";
    } elseif (categoryExists($mysqli, $name)) {
        $_SESSION['error'] = "Kategoria me këtë emër ekziston!";
    } else {
        $stmt = $mysqli->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->close();
        $_SESSION['message'] = "Kategoria u shtua me sukses!";
    }

    header("Location: " . GENERAL_URL . "categories/list.php");
    exit;
}

if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $name = trim($_POST['name']);

    if (empty($name)) {
        $_SESSION['error'] = "Emri i kategorisë nuk mund të jetë bosh!";
    } elseif (categoryExists($mysqli, $name, $id)) {
        $_SESSION['error'] = "Kategoria me këtë emër ekziston!";
    } else {
        $stmt = $mysqli->prepare("UPDATE categories SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $id);
        $stmt->execute();
        $stmt->close();
        $_SESSION['message'] = "Kategoria u përditësua me sukses!";
    }

    header("Location: " . GENERAL_URL . "categories/list.php");
    exit;
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $mysqli->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    $_SESSION['message'] = "Kategoria u fshi me sukses!";

    header("Location: " . GENERAL_URL . "categories/list.php");
    exit;
}

$result = $mysqli->query("SELECT * FROM categories ORDER BY id DESC");
