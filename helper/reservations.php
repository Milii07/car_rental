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
