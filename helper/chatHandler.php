<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once __DIR__ . '/../db/db.php';
header('Content-Type: application/json');

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, true);

if ($input === null) {
    echo json_encode(['reply' => 'Gabim: JSON jo i vlefshëm']);
    exit;
}

$message = trim(strtolower($input['message'] ?? $_POST['message'] ?? ''));
$response = ['reply' => 'Më fal, nuk kuptova.'];


function isCarAvailable($mysqli, $car_id, $start_date, $end_date, $brand_id = null, $category_id = null, $time = null)
{
    $sql = "
        SELECT * FROM reservations 
        WHERE car_id = ? AND status='pending' AND 
        (
            (start_date <= ? AND end_date >= ?) 
            OR (start_date <= ? AND end_date >= ?) 
            OR (start_date >= ? AND end_date <= ?)
        )
    ";

    $params = [$car_id, $start_date, $start_date, $end_date, $end_date, $start_date, $end_date];
    $types = "sssssss";

    if ($brand_id !== null) {
        $sql .= " AND brand_id = ?";
        $params[] = $brand_id;
        $types .= "i";
    }

    if ($category_id !== null) {
        $sql .= " AND category_id = ?";
        $params[] = $category_id;
        $types .= "i";
    }

    if ($time !== null) {
        $sql .= " AND time = ?";
        $params[] = $time;
        $types .= "s";
    }

    $stmt = $mysqli->prepare($sql);
    if (!$stmt) return false;

    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows === 0;
}



function reserveCar($mysqli, $car_id, $days)
{
    $user_id = $_SESSION['user_id'] ?? '';
    if (empty($user_id)) {
        return [
            'success' => false,
            'message' => 'Përdoruesi nuk është i kyçur.'
        ];
    }

    $stmtClient = $mysqli->prepare("SELECT id, full_name FROM clients WHERE user_id = ? LIMIT 1");
    $stmtClient->bind_param("i", $user_id);
    $stmtClient->execute();
    $clientResult = $stmtClient->get_result();
    $client = $clientResult->fetch_assoc();
    $stmtClient->close();

    if (!$client) {
        return [
            'success' => false,
            'message' => 'Nuk u gjet klienti për këtë përdorues.'
        ];
    }

    $client_id = $client['id'];
    $client_name = $client['full_name'];

    $startDateTime = new DateTime();
    $endDateTime = (clone $startDateTime)->modify("+$days days");

    $startStr = $startDateTime->format('Y-m-d H:i:s');
    $endStr = $endDateTime->format('Y-m-d H:i:s');

    try {
        $stmt = $mysqli->prepare("
            INSERT INTO reservations
            (client_id, client_name, car_id, start_date, end_date, status)
            VALUES (?, ?, ?, ?, ?, 'pending')
        ");
        $stmt->bind_param("iisss", $client_id, $client_name, $car_id, $startStr, $endStr);
        $stmt->execute();
        $stmt->close();

        $now = new DateTime();
        $secondsLeft = $endDateTime->getTimestamp() - $now->getTimestamp();

        if ($secondsLeft <= 0) {
            $timeLeft = "0 ditë, 0 orë, 0 min";
        } else {
            $fullDaysLeft = ceil($secondsLeft / 86400);
            $hoursLeft = floor(($secondsLeft % 86400) / 3600);
            $minutesLeft = floor(($secondsLeft % 3600) / 60);
            $timeLeft = "$fullDaysLeft ditë ($hoursLeft orë, $minutesLeft min)";
        }

        return [
            'success' => true,
            'message' => "Rezervimi u bë me sukses nga $startStr deri më $endStr. Koha e mbetur: $timeLeft"
        ];
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => 'Gabim gjatë rezervimit: ' . $e->getMessage()
        ];
    }
}









preg_match('/(\d+)\s*dite/', $message, $dayMatch);
$days = isset($dayMatch[1]) ? (int)$dayMatch[1] : 1;


$isToday = strpos($message, 'sot') !== false;


if (strpos($message, 'brand') !== false) {
    $result = $mysqli->query("SELECT DISTINCT brand FROM cars");
    $brands = [];
    while ($row = $result->fetch_assoc()) $brands[] = $row['brand'];
    $response['reply'] = "Brendet e disponueshme: " . implode(", ", $brands);
} elseif (strpos($message, 'kategori') !== false) {
    $result = $mysqli->query("SELECT DISTINCT name FROM categories");
    $categories = [];
    while ($row = $result->fetch_assoc()) $categories[] = $row['name'];
    $response['reply'] = "Kategoritë e disponueshme: " . implode(", ", $categories);
} elseif (strpos($message, 'rezervo') !== false) {
    preg_match('/rezervo\s+(.+)/', $message, $m);
    if (isset($m[1])) {
        $carModel = trim($m[1]);
        $stmt = $mysqli->prepare("SELECT cars.id, brand_id, model, price_per_day,brands.name as brand_name FROM cars JOIN brands on brands.id = cars.brand_id  WHERE model LIKE ?");
        $likeModel = "%$carModel%";
        $stmt->bind_param("s", $likeModel);
        $stmt->execute();
        $car = $stmt->get_result()->fetch_assoc();
        if ($car) {
            if (isCarAvailable($mysqli, $car['id'], date('Y-m-d'), date('Y-m-d', strtotime("+" . ($days - 1) . " days")))) {
                if (reserveCar($mysqli, $car['id'], $days)) {
                    $response['reply'] = "Rezervimi për {$car['brand_name']} {$car['model']} për $days ditë u krye me sukses!";
                } else {
                    $response['reply'] = "Gabim gjatë rezervimit. Provoni përsëri.";
                }
            } else {
                $response['reply'] = "Makina {$car['brand_name']} {$car['model']} është e zënë për periudhën e kërkuar.";
            }
        } else {
            $response['reply'] = "Nuk u gjet makina '$carModel'.";
        }
    }
} else {

    $stmt = $mysqli->query("SELECT cars.id, cars.brand_id, cars.model, cars.category_id, cars.price_per_day, brands.name as brand_name
    FROM cars join brands on cars.brand_id = brands.id");
    $carFound = null;
    while ($row = $stmt->fetch_assoc()) {

        if (strpos($message, strtolower(string: $row['brand_name'])) !== false && strpos($message, strtolower($row['model'])) !== false) {

            $carFound = $row;
            break;
        } elseif (strpos($message, strtolower($row['model'])) !== false) {
            $carFound = $row;
            break;
        }
    }

    if ($carFound) {
        if ($isToday) {
            $today = date('Y-m-d');
            if (isCarAvailable($mysqli, $carFound['id'], $today, $today)) {
                $response['reply'] = "Po, makina {$carFound['brand_name']} {$carFound['model']} është e lirë sot. Shkruaj 'rezervo {$carFound['model']}' për ta rezervuar.";
                $response['reserve_car_id'] = $carFound['id'];
            } else {
                $stmt2 = $mysqli->prepare("SELECT MAX(end_date) as until_date FROM reservations WHERE car_id = ? AND status='pending'");
                $stmt2->bind_param("i", $carFound['id']);
                $stmt2->execute();
                $row2 = $stmt2->get_result()->fetch_assoc();
                $response['reply'] = "Makina {$carFound['brand_name']} {$carFound['model']} është e zënë deri më {$row2['until_date']}.";
            }
        } else {
            $startDate = date('Y-m-d');
            $endDate = date('Y-m-d', strtotime("+" . ($days - 1) . " days"));

            if (isCarAvailable($mysqli, $carFound['id'], $startDate, $endDate)) {
                $total = $carFound['price_per_day'] * $days;
                $response['reply'] = "Makina {$carFound['brand_name']} {$carFound['model']} është e lirë për periudhën $startDate deri $endDate. Çmimi është {$carFound['price_per_day']}€/ditë, totali {$total}€. Shkruaj 'rezervo {$carFound['model']}' për ta rezervuar.";
                $response['reserve_car_id'] = $carFound['id'];
            } else {
                $stmt2 = $mysqli->prepare("SELECT MAX(end_date) as until_date FROM reservations WHERE car_id = ? AND status='pending'");
                $stmt2->bind_param("i", $carFound['id']);
                $stmt2->execute();
                $row2 = $stmt2->get_result()->fetch_assoc();
                $response['reply'] = "Makina {$carFound['brand_name']} {$carFound['model']} është e zënë deri më {$row2['until_date']}.";
            }
        }
    } else {
        $result = $mysqli->query("SELECT brand_id,model,price_per_day,brands.name as brand_name FROM cars JOIN brands on brands.id = cars.brand_id");
        $cars = [];
        while ($row = $result->fetch_assoc()) $cars[] = "{$row['brand_name']} {$row['model']} - {$row['price_per_day']}€/ditë";
        $response['reply'] = "Makinat: " . implode(", ", $cars);
    }
}


echo json_encode($response);
exit;
