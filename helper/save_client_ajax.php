<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include $_SERVER['DOCUMENT_ROOT'] . '/new_project_bk/db/db.php';
header('Content-Type: application/json');

if (isset($_POST['full_name']) && !empty(trim($_POST['full_name']))) {

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

    $stmt = $mysqli->prepare("
        INSERT INTO clients
        (profile_type, partner_type, full_name, company_name, nipt, email, phone, birthday, country, city, zip, reference, address, payment_terms, remarks)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
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
        echo json_encode(['success' => true, 'client_id' => $client_id, 'full_name' => $full_name]);
        exit;
    } else {
        echo json_encode(['success' => false, 'error' => 'Gabim ne ruajtjen e klientit']);
        exit;
    }
} else {
    // echo json_encode(['success' => false, 'error' => 'Fusha emri eshte e zbrazet']);
    // exit;
}
