<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include $_SERVER['DOCUMENT_ROOT'] . '/new_project_bk/db/db.php';

// Funksioni për statusin e makinës
if (!function_exists('getCarStatus')) {
    function getCarStatus($car_id, $mysqli)
    {
        $today = new DateTime();

        $stmt = $mysqli->prepare("
            SELECT r.*, cl.full_name
            FROM reservations r
            JOIN clients cl ON r.client_id = cl.id
            WHERE r.car_id = ? AND r.status != 'cancelled'
            ORDER BY r.start_date ASC
        ");
        $stmt->bind_param("i", $car_id);
        $stmt->execute();
        $results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        foreach ($results as $res) {
            if (empty($res['start_date']) || empty($res['end_date']) || $res['end_date'] === '0000-00-00') {
                continue;
            }

            $start = new DateTime($res['start_date']);
            $end = new DateTime($res['end_date']);

            if ($today >= $start && $today <= $end) {
                return [
                    'status' => 'E zënë',
                    'client_name' => $res['full_name'],
                    'start_date' => $res['start_date'],
                    'end_date' => $res['end_date'],
                    'time' => $res['time']
                ];
            }
        }

        return [
            'status' => 'E lirë',
            'client_name' => null,
            'start_date' => null,
            'end_date' => null,
            'time' => null
        ];
    }
}

// Funksioni për marrjen e imazheve të makinës
if (!function_exists('getCarImages')) {
    function getCarImages($images)
    {
        return $images ? explode(',', $images) : [];
    }
}

// Funksioni për marrjen e makinave të lira
if (!function_exists('getAvailableCars')) {
    function getAvailableCars($mysqli)
    {
        $cars = $mysqli->query("
            SELECT c.id, c.model, c.vin, c.price_per_day,
                   COALESCE(b.name, '-') AS brand_name,
                   COALESCE(cat.name, '-') AS category_name
            FROM cars c
            LEFT JOIN brands b ON c.brand_id=b.id
            LEFT JOIN categories cat ON c.category_id=cat.id
        ")->fetch_all(MYSQLI_ASSOC);

        $availableCars = [];
        foreach ($cars as $car) {
            $status = getCarStatus($car['id'], $mysqli);
            if ($status['status'] === 'E lirë') {
                $availableCars[] = $car;
            }
        }
        return $availableCars;
    }
}

// Merr të gjitha makinat dhe klientët për forms
$cars = $mysqli->query("
    SELECT c.id, c.model, c.vin, c.price_per_day,
           COALESCE(b.name, '-') AS brand_name,
           COALESCE(cat.name, '-') AS category_name
    FROM cars c
    LEFT JOIN brands b ON c.brand_id = b.id
    LEFT JOIN categories cat ON c.category_id = cat.id
")->fetch_all(MYSQLI_ASSOC);

$clients = $mysqli->query("SELECT id, full_name FROM clients ORDER BY full_name ASC")->fetch_all(MYSQLI_ASSOC);

// Merr të gjitha rezervimet
$reservations = $mysqli->query("
    SELECT r.*, c.model, c.vin, 
           COALESCE(b.name, '-') AS brand_name, 
           COALESCE(cat.name, '-') AS category_name, 
           cl.full_name AS client_name
    FROM reservations r
    JOIN cars c ON r.car_id = c.id
    LEFT JOIN brands b ON c.brand_id = b.id
    LEFT JOIN categories cat ON c.category_id = cat.id
    JOIN clients cl ON r.client_id = cl.id
    ORDER BY r.id DESC
")->fetch_all(MYSQLI_ASSOC);

// Krijimi i rezervimit
if (isset($_POST['create'])) {

    $client_id = $_POST['client_id'] ?? 0;
    $car_id = $_POST['car_id'] ?? 0;
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? $start_date;
    $time = $_POST['time'] ?? '';

    if (!$car_id || !$client_id || !$start_date) {
        $_SESSION['error'] = "Të gjitha fushat janë të detyrueshme!";
        header("Location: /new_project_bk/views/general/reservations/list.php");
        exit;
    }

    if ($start_date > $end_date) {
        $_SESSION['error'] = "Data e fillimit nuk mund të jetë pas datës së mbarimit!";
        header("Location: /new_project_bk/views/general/reservations/list.php");
        exit;
    }

    // Merr emrin e klientit
    $stmt = $mysqli->prepare("SELECT full_name FROM clients WHERE id=?");
    $stmt->bind_param("i", $client_id);
    $stmt->execute();
    $client = $stmt->get_result()->fetch_assoc();
    $client_name = $client['full_name'] ?? '';
    $stmt->close();

    // Kontrollo nëse makina është e lirë
    $check = $mysqli->prepare("
        SELECT r.id, cl.full_name 
        FROM reservations r
        JOIN clients cl ON r.client_id = cl.id
        WHERE r.car_id=? AND r.status!='cancelled'
        AND NOT (DATE(r.end_date) < ? OR DATE(r.start_date) > ?)
        LIMIT 1
    ");
    $check->bind_param("iss", $car_id, $start_date, $end_date);
    $check->execute();
    $res_check = $check->get_result()->fetch_assoc();
    $check->close();

    if ($res_check) {
        $_SESSION['error'] = "Makina është rezervuar nga: " . htmlspecialchars($res_check['full_name']);
        header("Location: /new_project_bk/views/general/reservations/list.php");
        exit;
    }

    // Merr të dhënat e makinës
    $stmtCar = $mysqli->prepare("SELECT brand_id, category_id, price_per_day FROM cars WHERE id=?");
    $stmtCar->bind_param("i", $car_id);
    $stmtCar->execute();
    $car = $stmtCar->get_result()->fetch_assoc();
    $stmtCar->close();

    $brand_id = $car['brand_id'] ?? 0;
    $category_id = $car['category_id'] ?? 0;
    $price_per_day = $car['price_per_day'] ?? 0;

    // Llogarit total_price
    $days = max(1, (abs(strtotime($end_date) - strtotime($start_date)) / 86400) + 1);
    $total_price = $price_per_day * $days;

    var_dump($car_id);
    // Fut rezervimin
    $stmt = $mysqli->prepare("
        INSERT INTO reservations 
        (client_id, client_name, car_id, brand_id, category_id, start_date, time, end_date, total_price, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')
    ");
    $stmt->bind_param("isiiisssd", $client_id, $client_name, $car_id, $brand_id, $category_id, $start_date, $time, $end_date, $total_price);
    $stmt->execute();
    $stmt->close();

    $_SESSION['message'] = "Rezervimi u krijua me sukses!";
    header("Location: /new_project_bk/views/general/reservations/list.php");
    exit;
}

// Fshirja e rezervimit
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $mysqli->prepare("DELETE FROM reservations WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    $_SESSION['message'] = "Rezervimi u fshi me sukses!";
    header("Location: /new_project_bk/views/general/reservations/list.php");
    exit;
}
