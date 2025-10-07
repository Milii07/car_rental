<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include $_SERVER['DOCUMENT_ROOT'] . '/new_project/db/db.php';

$cars_result = $mysqli->query("
SELECT c.id, c.model, c.vin, c.price_per_day, c.images,
COALESCE(b.name,'-') AS brand_name,
COALESCE(cat.name,'-') AS category_name
FROM cars c
LEFT JOIN brands b ON c.brand_id = b.id
LEFT JOIN categories cat ON c.category_id = cat.id
ORDER BY c.model ASC
");
$cars = $cars_result->fetch_all(MYSQLI_ASSOC);

$reservations_result = $mysqli->query("
SELECT r.car_id, r.start_date, r.end_date, cl.full_name AS client_name
FROM reservations r
JOIN clients cl ON r.client_id = cl.id
WHERE r.status != 'cancelled'
");
$reservations_all = $reservations_result->fetch_all(MYSQLI_ASSOC);

$reservations_by_car = [];
foreach ($reservations_all as $r) {
    $reservations_by_car[$r['car_id']][] = $r;
}

function getCarStatus($car_id, $reservations_by_car)
{
    if (isset($reservations_by_car[$car_id])) {
        foreach ($reservations_by_car[$car_id] as $res) {
            if (date('Y-m-d') >= $res['start_date'] && date('Y-m-d') <= $res['end_date']) {
                return [
                    'status' => 'E zënë',
                    'client_name' => $res['client_name'],
                    'start' => $res['start_date'],
                    'end' => $res['end_date']
                ];
            }
        }
    }
    return ['status' => 'E lirë', 'client_name' => null, 'start' => null, 'end' => null];
}

function getCarImages($images)
{
    if (empty($images)) return [];
    return explode(',', $images);
}

$reservations_result2 = $mysqli->query("
    SELECT r.*, r.time, c.model, c.vin,
    COALESCE(b.name, '-') AS brand_name,
    COALESCE(cat.name, '-') AS category_name,
    cl.full_name AS client_name
    FROM reservations r
    JOIN cars c ON r.car_id = c.id
    LEFT JOIN brands b ON c.brand_id = b.id
    LEFT JOIN categories cat ON c.category_id = cat.id
    JOIN clients cl ON r.client_id = cl.id
    ORDER BY r.id DESC
    ");
$reservations = $reservations_result2->fetch_all(MYSQLI_ASSOC);
