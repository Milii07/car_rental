<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include $_SERVER['DOCUMENT_ROOT'] . '/new_project/db/db.php';

/**
 * Merr të gjitha skedarët e makinave nga dosja uploads/cars
 */
function getCarFiles()
{
    $carDir = $_SERVER['DOCUMENT_ROOT'] . '/new_project/uploads/cars';
    $carFiles = glob($carDir . "/*.{jpg,png,jpeg,webp}", GLOB_BRACE);

    $carFiles = array_filter($carFiles, function ($file) {
        return basename($file)[0] !== '.';
    });

    return array_unique($carFiles);
}

/**
 * Sinkronizon makinat nga dosja uploads/cars në DB
 */
function syncCarsToDB($mysqli)
{
    $uploadsDir = $_SERVER['DOCUMENT_ROOT'] . '/new_project/uploads/cars/';
    $files = glob($uploadsDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);

    foreach ($files as $file) {
        $fileName = basename($file);
        $carName = pathinfo($file, PATHINFO_FILENAME);
        $images = '/uploads/cars/' . $fileName;

        // Kontrollo nëse makina ekziston
        $stmt = $mysqli->prepare("SELECT id FROM cars WHERE images = ?");
        $stmt->bind_param("s", $images);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) continue;

        // Fut makina në DB
        $price = rand(50, 300);
        $transmission = 'Automatic';
        $year = date('Y');

        $stmtInsert = $mysqli->prepare("INSERT INTO cars (model, images, price_per_day, transmission, year) VALUES (?, ?, ?, ?, ?)");
        $stmtInsert->bind_param("ssdsi", $carName, $images, $price, $transmission, $year);
        $stmtInsert->execute();
        $stmtInsert->close();
    }
}

/**
 * Merr statusin e një makine sipas rezervimeve
 */
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
        if (empty($res['start_date']) || empty($res['end_date']) || $res['end_date'] == '0000-00-00') {
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
                'time' => $res['time'] ?? null
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

/**
 * Merr të gjitha makinat nga DB
 */
function getAllCars($mysqli)
{
    return $mysqli->query("
        SELECT c.id, c.model, c.vin, c.price_per_day,
               COALESCE(b.name, '-') AS brand_name,
               COALESCE(cat.name, '-') AS category_name,
               c.images, c.transmission, c.year
        FROM cars c
        LEFT JOIN brands b ON c.brand_id=b.id
        LEFT JOIN categories cat ON c.category_id=cat.id
    ")->fetch_all(MYSQLI_ASSOC);
}

/**
 * Merr makinat e lira (të cilat nuk janë të rezervuara)
 */
function getAvailableCars($mysqli)
{
    $cars = getAllCars($mysqli);
    $availableCars = [];
    foreach ($cars as $car) {
        $status = getCarStatus($car['id'], $mysqli);
        if ($status['status'] == 'E lirë') {
            $availableCars[] = $car;
        }
    }
    return $availableCars;
}
