<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/new_project/db/db.php';


function upload_images($files)
{
    $uploaded = [];
    foreach ($files['tmp_name'] as $key => $tmp_name) {
        if (!empty($files['name'][$key])) {
            $filename = basename($files['name'][$key]);
            $target = $_SERVER['DOCUMENT_ROOT'] . '/new_project/uploads/cars/' . $filename;
            if (move_uploaded_file($tmp_name, $target)) {
                $uploaded[] = $filename;
            }
        }
    }
    return implode(',', $uploaded);
}

if (isset($_POST['create'])) {
    $fields = [
        'vin',
        'brand_id',
        'category_id',
        'model',
        'year',
        'body_type',
        'color',
        'fuel_type',
        'transmission',
        'odometer',
        'license_plate',
        'seating_capacity',
        'owner_name',
        'dob',
        'address',
        'phone',
        'email',
        'license_number',
        'tax_id',
        'insurance_provider',
        'policy_number',
        'coverage_type',
        'registration_fee',
        'road_tax',
        'sales_tax',
        'payment_method',
        'dealer_info',
        'special_plate'
    ];

    $data = [];
    foreach ($fields as $field) {
        $data[$field] = trim($_POST[$field] ?? '');
    }

    $data['images'] = isset($_FILES['images']) ? upload_images($_FILES['images']) : '';
    $data['created_at'] = date('Y-m-d H:i:s');

    if (!empty($data['vin']) && !empty($data['brand_id'])) {
        $stmt = $mysqli->prepare("
            INSERT INTO cars (
                vin, brand_id, category_id, model, year, body_type, color, fuel_type, transmission,
                odometer, license_plate, seating_capacity, owner_name, dob, address, phone, email,
                license_number, tax_id, insurance_provider, policy_number, coverage_type, registration_fee,
                road_tax, sales_tax, payment_method, dealer_info, special_plate, images, created_at
            ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
        ");

        $stmt->bind_param(
            "siisiisssiiissssssssssdddsisss",
            $data['vin'],
            $data['brand_id'],
            $data['category_id'],
            $data['model'],
            $data['year'],
            $data['body_type'],
            $data['color'],
            $data['fuel_type'],
            $data['transmission'],
            $data['odometer'],
            $data['license_plate'],
            $data['seating_capacity'],
            $data['owner_name'],
            $data['dob'],
            $data['address'],
            $data['phone'],
            $data['email'],
            $data['license_number'],
            $data['tax_id'],
            $data['insurance_provider'],
            $data['policy_number'],
            $data['coverage_type'],
            $data['registration_fee'],
            $data['road_tax'],
            $data['sales_tax'],
            $data['payment_method'],
            $data['dealer_info'],
            $data['special_plate'],
            $data['images'],
            $data['created_at']
        );

        if ($stmt->execute()) {
            $_SESSION['message'] = "Makina u shtua me sukses!";
        } else {
            $_SESSION['error'] = "Gabim gjatë shtimit të makinës: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "VIN dhe Brand janë të detyrueshme!";
    }

    header("Location: list.php");
    exit;
}


if (isset($_POST['update']) && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $fields = [
        'vin',
        'brand_id',
        'category_id',
        'model',
        'year',
        'body_type',
        'color',
        'fuel_type',
        'transmission',
        'odometer',
        'license_plate',
        'seating_capacity',
        'owner_name',
        'dob',
        'address',
        'phone',
        'email',
        'license_number',
        'tax_id',
        'insurance_provider',
        'policy_number',
        'coverage_type',
        'registration_fee',
        'road_tax',
        'sales_tax',
        'payment_method',
        'dealer_info',
        'special_plate'
    ];

    $data = [];
    foreach ($fields as $field) {
        $data[$field] = trim($_POST[$field] ?? '');
    }


    if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
        $data['images'] = upload_images($_FILES['images']);
    } else {

        $stmt = $mysqli->prepare("SELECT images FROM cars WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $data['images'] = $row['images'] ?? '';
        $stmt->close();
    }

    $stmt = $mysqli->prepare("
        UPDATE cars SET
            vin=?, brand_id=?, category_id=?, model=?, year=?, body_type=?, color=?, fuel_type=?, transmission=?,
            odometer=?, license_plate=?, seating_capacity=?, owner_name=?, dob=?, address=?, phone=?, email=?,
            license_number=?, tax_id=?, insurance_provider=?, policy_number=?, coverage_type=?, registration_fee=?,
            road_tax=?, sales_tax=?, payment_method=?, dealer_info=?, special_plate=?, images=?
        WHERE id=?
    ");

    $stmt->bind_param(
        "siisiisssiiissssssssssdddsissssi",
        $data['vin'],
        $data['brand_id'],
        $data['category_id'],
        $data['model'],
        $data['year'],
        $data['body_type'],
        $data['color'],
        $data['fuel_type'],
        $data['transmission'],
        $data['odometer'],
        $data['license_plate'],
        $data['seating_capacity'],
        $data['owner_name'],
        $data['dob'],
        $data['address'],
        $data['phone'],
        $data['email'],
        $data['license_number'],
        $data['tax_id'],
        $data['insurance_provider'],
        $data['policy_number'],
        $data['coverage_type'],
        $data['registration_fee'],
        $data['road_tax'],
        $data['sales_tax'],
        $data['payment_method'],
        $data['dealer_info'],
        $data['special_plate'],
        $data['images'],
        $id
    );

    if ($stmt->execute()) {
        $_SESSION['message'] = "Makina u përditësua me sukses!";
    } else {
        $_SESSION['error'] = "Gabim gjatë përditësimit: " . $stmt->error;
    }
    $stmt->close();

    header("Location: list.php");
    exit;
}


if (isset($_GET['id'])) {
    $id = intval($_GET['id']);


    $stmt = $mysqli->prepare("SELECT images FROM cars WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $images = explode(',', $row['images']);
        foreach ($images as $img) {
            $file = $_SERVER['DOCUMENT_ROOT'] . '/new_project/uploads/cars/' . $img;
            if (file_exists($file) && is_file($file)) unlink($file);
        }
    }
    $stmt->close();


    $stmt = $mysqli->prepare("DELETE FROM cars WHERE id=?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Makina u fshi me sukses!";
    } else {
        $_SESSION['error'] = "Gabim gjatë fshirjes: " . $stmt->error;
    }
    $stmt->close();

    header("Location: list.php");
    exit;
}
