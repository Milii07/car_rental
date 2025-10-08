<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


include $_SERVER['DOCUMENT_ROOT'] . '/new_project_bk/db/db.php';

if (isset($_POST['create'])) {
    $name = trim($_POST['name']);
    if (!empty($name)) {
        $stmt = $mysqli->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->close();
        $_SESSION['message'] = "Kategoria u shtua me sukses!";
    } else {
        $_SESSION['error'] = "Emri i kategorisë nuk mund të jetë bosh!";
    }
    header("Location: list.php");
    exit;
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = trim($_POST['name']);
    if (!empty($name)) {
        $stmt = $mysqli->prepare("UPDATE categories SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $id);
        $stmt->execute();
        $stmt->close();
        $_SESSION['message'] = "Kategoria u përditësua me sukses!";
    } else {
        $_SESSION['error'] = "Emri i kategorisë nuk mund të jetë bosh!";
    }
    header("Location: /new_project_bk/views/general/categories/list.php");
    exit;
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $mysqli->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    $_SESSION['message'] = "Kategoria u fshi me sukses!";
    header("Location: /new_project_bk/views/general/categories/list.php");
    exit;
}

$result = $mysqli->query("SELECT * FROM categories ORDER BY id DESC");
