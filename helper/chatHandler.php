<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once __DIR__ . '/../db/db.php';
header('Content-Type: application/json');

$rawInput = file_get_contents('php://input');
$input = json_decode($rawInput, true);
if ($input === null) $input = $_POST;

$response = ['reply' => "Më fal, nuk kuptova."];

function isCarAvailable($mysqli, $car_id, $start_date, $end_date)
{
    $sql = "
        SELECT * FROM reservations 
        WHERE car_id = ? AND status='pending' AND 
        (
            (start_date <= ? AND end_date >= ?) 
            OR (start_date <= ? AND end_date >= ?) 
            OR (start_date >= ? AND end_date <= ?)
        )
        ORDER BY end_date DESC
        LIMIT 1
    ";
    $stmt = $mysqli->prepare($sql);
    if (!$stmt) return false;
    $stmt->bind_param("sssssss", $car_id, $start_date, $start_date, $end_date, $end_date, $start_date, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows === 0 ? true : $result->fetch_assoc();
}

function reserveCar($mysqli, $car_id, $days)
{
    $user_id = $_SESSION['user_id'] ?? '';
    if (empty($user_id)) return ['success' => false, 'message' => 'Përdoruesi nuk është i kyçur.'];

    $stmtClient = $mysqli->prepare("SELECT id, full_name FROM clients WHERE user_id = ? LIMIT 1");
    $stmtClient->bind_param("i", $user_id);
    $stmtClient->execute();
    $clientResult = $stmtClient->get_result();
    $client = $clientResult->fetch_assoc();
    $stmtClient->close();
    if (!$client) return ['success' => false, 'message' => 'Nuk u gjet klienti për këtë përdorues.'];

    $client_id = $client['id'];
    $client_name = $client['full_name'];

    $startDateTime = new DateTime();
    $endDateTime = (clone $startDateTime)->modify("+$days days");

    $start_date = $startDateTime->format('Y-m-d');
    $time = $startDateTime->format('H:i:s');
    $end_date = $endDateTime->format('Y-m-d');

    try {
        $stmt = $mysqli->prepare("
            INSERT INTO reservations
            (client_id, client_name, car_id, start_date, time, end_date, status)
            VALUES (?, ?, ?, ?, ?, ?, 'pending')
        ");
        $stmt->bind_param("iissss", $client_id, $client_name, $car_id, $start_date, $time, $end_date);
        $stmt->execute();
        $stmt->close();

        $secondsLeft = $endDateTime->getTimestamp() - $startDateTime->getTimestamp();
        $fullDaysLeft = floor($secondsLeft / 86400);
        $hoursLeft = floor(($secondsLeft % 86400) / 3600);
        $minutesLeft = floor(($secondsLeft % 3600) / 60);
        $timeLeft = "$fullDaysLeft ditë ($hoursLeft orë, $minutesLeft min)";

        return [
            'success' => true,
            'message' => "Rezervimi u krye me sukses nga $start_date $time deri më $end_date. Koha e mbetur: $timeLeft.<br><br>
    <strong>Faleminderit që zgjodhët Auto Future Block.</strong> Ju urojmë një eksperiencë të shkëlqyer dhe udhëtim të sigurt me makinën tuaj!"
        ];
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Gabim gjatë rezervimit: ' . $e->getMessage()];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' || (isset($input['event']) && $input['event'] === 'open')) {
    $user_id = $_SESSION['user_id'] ?? null;
    $name = null;
    if ($user_id) {
        $stmt = $mysqli->prepare("SELECT full_name FROM clients WHERE user_id = ? LIMIT 1");
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $name = $row['full_name'] ?? null;
    }
    $replyName = $name
        ? "Përshëndetje, $name! Mirë se erdhët në Auto Future Block. Si mund t'ju ndihmoj sot?"
        : "Përshëndetje! Mirë se erdhët në Auto Future Block. Si mund t'ju ndihmoj sot?";
    echo json_encode(['reply' => $replyName]);
    exit;
}

if (!empty($input['action']) && $input['action'] === 'reserve') {
    $start = $input['start_date'] ?? '';
    $end = $input['end_date'] ?? '';
    if ($start && $end && !empty($_SESSION['last_car_id'])) {
        $car_id = $_SESSION['last_car_id'];
        $startDateTime = new DateTime($start);
        $endDateTime = new DateTime($end);
        $days = $endDateTime->diff($startDateTime)->days + 1;

        $availability = isCarAvailable($mysqli, $car_id, $start, $end);
        if ($availability === true) {
            $result = reserveCar($mysqli, $car_id, $days);
            $response['reply'] = $result['message'];
        } else {
            $nextAvailableDate = date('Y-m-d', strtotime($availability['end_date'] . ' +1 day'));
            $response['reply'] = "Makina është e zënë në këto data ({$availability['start_date']} deri më {$availability['end_date']}). Mund ta rezervoni vetëm pas kësaj periudhe.";
            $response['next_available_date'] = $nextAvailableDate;
            $response['expected_confirmations'] = ["po", "po dua ta rezervoj", "në rregull", "ok"];
        }
    } else {
        $response['reply'] = "Diçka nuk shkoi gjatë rezervimit. Ju lutem provoni përsëri.";
    }
    echo json_encode($response);
    exit;
}

$message = trim(strtolower($input['message'] ?? $_POST['message'] ?? ''));

$greeting = ['prsh', 'prsh mire', 'mire flm', 'mire', 'hello', 'hi'];
if (in_array($message, $greeting)) {
    echo json_encode(['reply' => '😊']);
    exit;
}

$greeting = ['Faleminderit', 'flm', 'thnx', 'shume faleminderit'];
if (in_array($message, $greeting)) {
    echo json_encode(['reply' => 'Faleminderit ju ! Auto Future Block ju uron një ditë të mbarë dhe shpresojmë t’ju shohim së shpejti përsëri.']);
    exit;
}

$answer = ['dua te rezervoj nje makine', 'do rezervoj nje makine', 'dua nje makine', 'dua nje makine me qera', 'dua makine me qera'];
if (in_array($message, $answer)) {
    echo json_encode(['reply' => 'Cilën makinë dëshironi të rezervoni?']);
    exit;
}

if (!empty($message)) {
    $stmt = $mysqli->prepare("
        SELECT cars.id, model, price_per_day, brands.name AS brand_name 
        FROM cars 
        JOIN brands ON brands.id = cars.brand_id
        WHERE LOWER(model) LIKE LOWER(?) 
        LIMIT 1
    ");
    $likeModel = "%$message%";
    $stmt->bind_param("s", $likeModel);
    $stmt->execute();
    $car = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if ($car) {
        $_SESSION['last_car_id'] = $car['id'];
        $response['reply'] = "Neqoftese deshironi të rezervoni makinën {$car['brand_name']} {$car['model']}, shkruani: 'rezervo {$car['model']} X dite'.";
        echo json_encode($response);
        exit;
    }
}

if (preg_match('/rezervo\s+(.+?)\s+(\d+)\s*dite/i', $message, $matches)) {
    $carModel = trim($matches[1]);
    $days = (int)$matches[2];

    $stmt = $mysqli->prepare("
        SELECT cars.id, model, price_per_day, brands.name AS brand_name 
        FROM cars 
        JOIN brands ON brands.id = cars.brand_id
        WHERE LOWER(model) LIKE LOWER(?) 
        LIMIT 1
    ");
    $likeModel = "%$carModel%";
    $stmt->bind_param("s", $likeModel);
    $stmt->execute();
    $car = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($car) {
        $_SESSION['last_car_id'] = $car['id'];
        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d', strtotime("+" . ($days - 1) . " days"));
        $availability = isCarAvailable($mysqli, $car['id'], $startDate, $endDate);
        if ($availability === true) {
            $result = reserveCar($mysqli, $car['id'], $days);
            $response['reply'] = $result['message'];
        } else {
            $nextAvailableDate = date('Y-m-d', strtotime($availability['end_date'] . ' +1 day'));
            $response['reply'] = "Makina {$car['brand_name']} {$car['model']} është e zënë nga {$availability['start_date']} deri më {$availability['end_date']}. Mund ta rezervoni vetëm pas kësaj date.";
            $response['next_available_date'] = $nextAvailableDate;
            $response['expected_confirmations'] = ["po", "po dua ta rezervoj", "në rregull", "ok"];
        }
    } else {
        $response['reply'] = "Nuk u gjet makina '$carModel'.";
    }
}

function getCarsByBrandOrCategory($mysqli, $searchTerm = '')
{
    $cars = [];

    $sql = "
        SELECT cars.id, model, price_per_day, brands.name AS brand_name, categories.name AS category_name,
               (SELECT COUNT(*) FROM reservations r WHERE r.car_id=cars.id AND r.status='pending' AND r.end_date >= CURDATE()) AS is_reserved
        FROM cars
        JOIN brands ON brands.id = cars.brand_id
        JOIN categories ON categories.id = cars.category_id
    ";

    if (!empty($searchTerm)) {
        $sql .= " WHERE LOWER(brands.name) LIKE LOWER(?) OR LOWER(categories.name) LIKE LOWER(?) ";
        $stmt = $mysqli->prepare($sql);
        $likeTerm = "%$searchTerm%";
        $stmt->bind_param("ss", $likeTerm, $likeTerm);
    } else {
        $stmt = $mysqli->prepare($sql);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $status = $row['is_reserved'] > 0 ? 'zena' : 'lira';
        $cars[] = [
            'id' => $row['id'],
            'brand' => $row['brand_name'],
            'category' => $row['category_name'],
            'model' => $row['model'],
            'price' => $row['price_per_day'],
            'status' => $status
        ];
    }

    $stmt->close();
    return $cars;
}

if (!empty($input['message'])) {
    $searchTerm = strtolower(trim($input['message']));
    $brandCategoryCars = getCarsByBrandOrCategory($mysqli, $searchTerm);

    if (!empty($brandCategoryCars)) {
        $replyHtml = "<ul>";
        foreach ($brandCategoryCars as $car) {
            $replyHtml .= "<li>{$car['brand']} {$car['model']} ({$car['category']}) - {$car['price']}€/ditë - {$car['status']}</li>";
        }
        $replyHtml .= "</ul>";
        echo json_encode(['reply' => $replyHtml]);
        exit;
    }
}

if (preg_match('/sa shkon\s+(.+?)\s+per\s+(\d+)\s*dite/i', $message, $matches)) {
    $carModel = trim($matches[1]);
    $days = (int)$matches[2];

    $stmt = $mysqli->prepare("
        SELECT cars.id, model, price_per_day, brands.name AS brand_name 
        FROM cars 
        JOIN brands ON brands.id = cars.brand_id
        WHERE LOWER(model) LIKE LOWER(?) 
        LIMIT 1
    ");
    $likeModel = "%$carModel%";
    $stmt->bind_param("s", $likeModel);
    $stmt->execute();
    $car = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($car) {
        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d', strtotime("+" . ($days - 1) . " days"));
        $availability = isCarAvailable($mysqli, $car['id'], $startDate, $endDate);

        if ($availability === true) {
            $total = $car['price_per_day'] * $days;
            $response['reply'] = "Makina {$car['brand_name']} {$car['model']} është e lirë për $days ditë. Çmimi total: $total €.";
        } else {
            $response['reply'] = "Makina {$car['brand_name']} {$car['model']} është e zënë nga {$availability['start_date']} deri më {$availability['end_date']}. Mund ta rezervoni vetëm pas kësaj date.";
        }
    } else {
        $response['reply'] = "Nuk u gjet makina '$carModel'.";
    }
}

function getCarsByAvailability($mysqli, $statusFilter = 'lira')
{
    $cars = [];

    $sql = "
        SELECT cars.id, model, price_per_day, brands.name AS brand_name, categories.name AS category_name,
               (SELECT COUNT(*) 
                FROM reservations r 
                WHERE r.car_id=cars.id AND r.status='pending' AND r.end_date >= CURDATE()
               ) AS is_reserved
        FROM cars
        JOIN brands ON brands.id = cars.brand_id
        JOIN categories ON categories.id = cars.category_id
    ";

    $stmt = $mysqli->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $status = $row['is_reserved'] > 0 ? 'zena' : 'lira';
        if ($status === $statusFilter) {
            $cars[] = [
                'id' => $row['id'],
                'brand' => $row['brand_name'],
                'category' => $row['category_name'],
                'model' => $row['model'],
                'price' => $row['price_per_day'],
                'status' => $status
            ];
        }
    }

    $stmt->close();
    return $cars;
}

if (in_array($message, ['cfare makinash keni te lira', 'cfare makinash keni te zena'])) {
    $statusFilter = $message === 'cfare makinash keni te lira' ? 'lira' : 'zena';
    $carsList = getCarsByAvailability($mysqli, $statusFilter);

    if (!empty($carsList)) {
        $replyHtml = "<ul>";
        foreach ($carsList as $car) {
            $replyHtml .= "<li>{$car['brand']} {$car['model']} ({$car['category']}) - {$car['price']}€/ditë - {$car['status']}</li>";
        }
        $replyHtml .= "</ul>";
        $response['reply'] = ucfirst($statusFilter) . " makinave: " . $replyHtml;
    } else {
        $response['reply'] = "Nuk u gjet asnjë makinë $statusFilter.";
    }
}


echo json_encode($response);
exit;
