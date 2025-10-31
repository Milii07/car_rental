<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(__DIR__) . '/index.php';


if (!function_exists('getCarStatus')) {
    function getCarStatus($car_id, $mysqli)
    {
        $now = new DateTime();

        $stmt = $mysqli->prepare("
            SELECT r.*, cl.full_name
            FROM reservations r
            JOIN clients cl ON r.client_id = cl.id
            WHERE r.car_id = ? AND r.status != 'cancelled'
            ORDER BY r.start_date ASC, r.time ASC
        ");
        $stmt->bind_param("i", $car_id);
        $stmt->execute();
        $reservations = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        foreach ($reservations as $r) {
            if (empty($r['start_date']) || empty($r['end_date']) || $r['end_date'] === '0000-00-00') continue;

            $start = new DateTime($r['start_date'] . ' ' . $r['time']);
            $end = new DateTime(datetime: $r['end_date'] . ' 23:59:59');

            if ($now >= $start && $now <= $end) {
                $diff = $now->diff($end);
                return [
                    'status' => 'E zënë',
                    'client_name' => $r['full_name'],
                    'start_date' => $r['start_date'],
                    'end_date' => $r['end_date'],
                    'time' => $r['time'],
                    'remaining_days' => $diff->d,
                    'remaining_hours' => $diff->h,
                    'remaining_minutes' => $diff->i
                ];
            }
        }

        return [
            'status' => 'E lirë',
            'client_name' => null,
            'start_date' => null,
            'end_date' => null,
            'time' => null,
            'remaining_days' => 0,
            'remaining_hours' => 0,
            'remaining_minutes' => 0
        ];
    }
}



if (!function_exists('getCarImages')) {
    function getCarImages($images)
    {
        return $images ? explode(',', $images) : [];
    }
}


if (isset($_POST['create'])) {

    $client_id = $_POST['client_id'] ?? 0;
    $car_id = $_POST['car_id'] ?? 0;
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? $start_date;
    $time = $_POST['time'] ?? '';

    if (!$car_id || !$client_id || !$start_date || !$time) {
        $_SESSION['error'] = "Të gjitha fushat janë të detyrueshme!";
        header("Location: " . BASE_URL . "views/general/reservations/list.php");
        exit;
    }

    if ($start_date > $end_date) {
        $_SESSION['error'] = "Data e fillimit nuk mund të jetë pas datës së mbarimit!";
        header("Location: " . BASE_URL . "views/general/reservations/list.php");
        exit;
    }

    $stmt = $mysqli->prepare("SELECT full_name FROM clients WHERE id=?");
    $stmt->bind_param("i", $client_id);
    $stmt->execute();
    $client = $stmt->get_result()->fetch_assoc();
    $client_name = $client['full_name'] ?? '';
    $stmt->close();

    $check = $mysqli->prepare("
        SELECT r.id, cl.full_name
        FROM reservations r
        JOIN clients cl ON r.client_id = cl.id
        WHERE r.car_id=? AND r.status!='cancelled'
        AND NOT (
            CONCAT(r.end_date, ' ', r.time) <= CONCAT(?, ' ', ?)
            OR CONCAT(r.start_date, ' ', r.time) >= CONCAT(?, ' ', ?)
        )
        LIMIT 1
    ");
    $check->bind_param("issss", $car_id, $start_date, $time, $end_date, $time);
    $check->execute();
    $res_check = $check->get_result()->fetch_assoc();
    $check->close();

    if ($res_check) {
        $_SESSION['error'] = "Makina është rezervuar nga: " . htmlspecialchars($res_check['full_name']);
        header("Location: " . BASE_URL . "views/general/reservations/list.php");
        exit;
    }

    $stmtCar = $mysqli->prepare("SELECT brand_id, category_id, price_per_day FROM cars WHERE id=?");
    $stmtCar->bind_param("i", $car_id);
    $stmtCar->execute();
    $car = $stmtCar->get_result()->fetch_assoc();
    $stmtCar->close();

    $brand_id = $car['brand_id'] ?? 0;
    $category_id = $car['category_id'] ?? 0;
    $price_per_day = $car['price_per_day'] ?? 0;

    $startDateTime = new DateTime($start_date);
    $endDateTime = new DateTime($end_date);
    $days = $startDateTime->diff($endDateTime)->days + 1;
    $total_price = $price_per_day * $days;

    $stmt = $mysqli->prepare("INSERT INTO reservations 
        (client_id, client_name, car_id, brand_id, category_id, start_date, time, end_date, total_price, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");

    $stmt->bind_param("isiiisssd", $client_id, $client_name, $car_id, $brand_id, $category_id, $start_date, $time, $end_date, $total_price);
    $stmt->execute();
    $stmt->close();

    $_SESSION['message'] = "Rezervimi u krijua me sukses!";
    header("Location: " . BASE_URL . "views/general/reservations/list.php");
    exit;
}


if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $mysqli->prepare("DELETE FROM reservations WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    $_SESSION['message'] = "Rezervimi u fshi me sukses!";
    header("Location: " . BASE_URL . "views/general/reservations/list.php");
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'search_cars') {
    header('Content-Type: application/json; charset=utf-8');

    $pickup_date = $_POST['pickup_date'] ?? '';
    $pickup_time = $_POST['pickup_time'] ?? '';
    $dropoff_date = $_POST['dropoff_date'] ?? '';
    $dropoff_time = $_POST['dropoff_time'] ?? '';
    $service_type = $_POST['service_type'] ?? 'all';

    if (!$pickup_date || !$pickup_time || !$dropoff_date || !$dropoff_time) {
        echo json_encode(['error' => 'Të gjitha fushat janë të detyrueshme']);
        exit;
    }

    if (!$mysqli) {
        echo json_encode(['error' => 'Lidhja me bazën e të dhënave dështoi']);
        exit;
    }

    $pickup_datetime = $pickup_date . ' ' . $pickup_time;
    $dropoff_datetime = $dropoff_date . ' ' . $dropoff_time;
    $uploadDir = '/new_project_bk/uploads/cars/';

    $query = "
        SELECT *
        FROM cars c
        WHERE (? = 'all' OR c.service_type = ?)
        AND c.id NOT IN (
            SELECT r.car_id
            FROM reservations r
            WHERE r.status != 'cancelled'
            AND NOT (
                CONCAT(r.end_date, ' ', r.time) <= ?
                OR CONCAT(r.start_date, ' ', r.time) >= ?
            )
        )
        ORDER BY c.id DESC
    ";

    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        echo json_encode(['error' => $mysqli->error]);
        exit;
    }

    $stmt->bind_param("ssss", $service_type, $service_type, $pickup_datetime, $dropoff_datetime);
    $stmt->execute();
    $carsResult = $stmt->get_result();

    $availableCars = [];
    while ($car = $carsResult->fetch_assoc()) {
        $firstImage = '';
        if (!empty($car['images'])) {
            $imagesArray = array_filter(explode(',', $car['images']), fn($img) => trim($img) !== '');
            $firstImage = trim(reset($imagesArray));
        }

        $availableCars[] = [
            'id' => $car['id'],
            'brand' => $car['brand_id'],
            'category' => $car['category_id'],
            'model' => $car['model'],
            'price_per_day' => $car['price_per_day'],
            'image' => $uploadDir . $firstImage,
            'rating' => $car['rating'] ?? '4.5',
            'seats' => $car['seating_capacity'] ?? $car['seats'] ?? '5',
            'transmission' => $car['transmission'] ?? 'Manual',
            'type' => $car['body_type'] ?? $car['type'] ?? 'Sedan',
            'service_type' => $car['service_type'] ?? 'all'
        ];
    }

    echo json_encode($availableCars);
    exit;
}
