<?php
header('Content-Type: application/json');
include $_SERVER['DOCUMENT_ROOT'] . '/new_project_bk/db/db.php';

$response = ['success' => false, 'message' => 'Diçka shkoi keq.'];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Kërkesa nuk është POST.');
    }

    $id = $_POST['id'] ?? null;
    $model = $_POST['name'] ?? '';
    $price_per_day = $_POST['price'] ?? 0;
    $seats = $_POST['seats'] ?? 0;
    $transmission = $_POST['transmission'] ?? '';
    $type = $_POST['type'] ?? '';
    $rating = $_POST['rating'] ?? 0;

    if (!$id) throw new Exception('ID e makinës mungon.');

    $stmt = $mysqli->prepare("
        UPDATE cars 
        SET model=?, price_per_day=?, seating_capacity=?, transmission=?, body_type=?, rating=? 
        WHERE id=?
    ");
    if (!$stmt) throw new Exception($mysqli->error);

    $stmt->bind_param('sdssdis', $model, $price_per_day, $seats, $transmission, $type, $rating, $id);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Makina u përditësua me sukses!';
    } else {
        throw new Exception($stmt->error);
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
exit;
