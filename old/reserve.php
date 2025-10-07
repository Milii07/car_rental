<?php
session_start();
include 'db/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $car_id = (int)($_POST['car_id'] ?? 0);
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';
    $user_id = (int)($_SESSION['user_id'] ?? 1);

    if (!$start_date || !$end_date) {
        $_SESSION['message'] = "Duhet të zgjidhni datat!";
        header("Location: header.php");
        exit;
    }

    $diff = (strtotime($end_date) - strtotime($start_date)) / 86400;
    if ($diff < 1) {
        $_SESSION['message'] = "Data e mbarimit duhet të jetë pas datës së fillimit!";
        header("Location: header.php");
        exit;
    }

    $stmt = $mysqli->prepare("SELECT price_per_day FROM cars WHERE id = ?");
    $stmt->bind_param('i', $car_id);
    $stmt->execute();
    $stmt->bind_result($price_per_day);
    if (!$stmt->fetch()) {
        $_SESSION['message'] = "Makina nuk ekziston!";
        $stmt->close();
        header("Location: header.php");
        exit;
    }
    $stmt->close();

    $total_price = $price_per_day * $diff;

    $stmt = $mysqli->prepare("INSERT INTO reservations (user_id, car_id, start_date, end_date, total_price, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("iissd", $user_id, $car_id, $start_date, $end_date, $total_price);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Rezervimi u krye me sukses! Totali: €" . number_format($total_price, 2);
    } else {
        $_SESSION['message'] = "Gabim gjatë ruajtjes së rezervimit: " . $stmt->error;
    }

    $stmt->close();
    $mysqli->close();
    header("Location: header.php");
    exit;
}
