<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(__DIR__) . '/index.php';

header('Content-Type: application/json');

if (!isset($_POST['full_name']) || empty(trim($_POST['full_name']))) {
    echo json_encode([
        'success' => false,
        'error' => 'Fusha "Emri i Plotë" është e detyrueshme!'
    ]);
    exit;
}

$full_name = trim($_POST['full_name']);
$profile_type = $_POST['profile_type'] ?? 'client';
$partner_type = $_POST['partner_type'] ?? 'individual';
$company_name = $_POST['company_name'] ?? '';
$nipt = $_POST['nipt'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$birthday = $_POST['birthday'] ?? '';
$country = $_POST['country'] ?? '';
$city = $_POST['city'] ?? '';
$zip = $_POST['zip'] ?? '';
$reference = $_POST['reference'] ?? '';
$address = $_POST['address'] ?? '';
$payment_terms = $_POST['payment_terms'] ?? '';
$remarks = $_POST['remarks'] ?? '';

try {
    $stmt = $mysqli->prepare("
        INSERT INTO clients 
        (profile_type, partner_type, full_name, company_name, nipt, email, phone, birthday, country, city, zip, reference, address, payment_terms, remarks)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    if (!$stmt) {
        throw new Exception("Gabim në përgatitjen e query: " . $mysqli->error);
    }

    $stmt->bind_param(
        "sssssssssssssss",
        $profile_type,
        $partner_type,
        $full_name,
        $company_name,
        $nipt,
        $email,
        $phone,
        $birthday,
        $country,
        $city,
        $zip,
        $reference,
        $address,
        $payment_terms,
        $remarks
    );

    if ($stmt->execute()) {
        $client_id = $stmt->insert_id;
        $stmt->close();

        echo json_encode([
            'success' => true,
            'client_id' => $client_id,
            'client_name' => $full_name,
            'message' => 'Klienti u shtua me sukses!'
        ]);
    } else {
        throw new Exception("Gabim në ekzekutimin e query: " . $stmt->error);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
exit;
