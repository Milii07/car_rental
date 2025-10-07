<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include $_SERVER['DOCUMENT_ROOT'] . '/new_project/db/db.php';

$carFields = ['vin', 'model', 'year', 'body_type', 'color', 'fuel_type', 'transmission', 'odometer', 'license_plate', 'seating_capacity'];
$ownerFields = ['owner_name', 'dob', 'address', 'phone', 'email', 'license_number', 'tax_id'];
$insuranceFields = ['insurance_provider', 'policy_number', 'coverage_type'];
$financialFields = ['registration_fee', 'road_tax', 'sales_tax', 'payment_method', 'price_per_day'];
$dealerFields = ['dealer_info', 'special_plate'];


function upload_images($files)
{
    $uploaded = [];
    if (isset($files['tmp_name']) && is_array($files['tmp_name'])) {
        foreach ($files['tmp_name'] as $key => $tmp_name) {
            if ($tmp_name) {
                $filename = basename($files['name'][$key]);
                $target = $_SERVER['DOCUMENT_ROOT'] . '/new_project/uploads/cars/' . $filename;
                if (move_uploaded_file($tmp_name, $target)) {
                    $uploaded[] = $filename;
                }
            }
        }
    }
    return implode(',', $uploaded);
}

if (isset($_POST['create'])) {
    $data = $_POST;
    $images = !empty($_FILES['images']['name'][0]) ? upload_images($_FILES['images']) : '';

    $columns = array_merge($carFields, $ownerFields, $insuranceFields, $financialFields, $dealerFields);
    $columns[] = 'brand_id';
    $columns[] = 'category_id';
    $columns[] = 'images';

    $placeholders = implode(',', array_fill(0, count($columns), '?'));
    $values = [];
    foreach (array_merge($carFields, $ownerFields, $insuranceFields, $financialFields, $dealerFields) as $col) $values[] = $data[$col] ?? '';
    $values[] = $data['brand_id'];
    $values[] = $data['category_id'];
    $values[] = $images;

    $stmt = $mysqli->prepare("INSERT INTO cars (" . implode(',', $columns) . ") VALUES ($placeholders)");
    $stmt->bind_param(str_repeat('s', count($values)), ...$values);
    $stmt->execute();
    $stmt->close();
    $_SESSION['message'] = "Makina u shtua me sukses!";
    header("Location: /new_project/views/general/cars/list.php");
    exit;
}


if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $data = $_POST;
    $images = !empty($_FILES['images']['name'][0])
        ? upload_images($_FILES['images'])
        : $mysqli->query("SELECT images FROM cars WHERE id=$id")->fetch_assoc()['images'];

    $columns = array_merge($carFields, $ownerFields, $insuranceFields, $financialFields, $dealerFields);
    $columns[] = 'brand_id';
    $columns[] = 'category_id';
    $set = implode(',', array_map(fn($c) => "$c=?", $columns)) . ",images=?";
    $values = [];
    foreach (array_merge($carFields, $ownerFields, $insuranceFields, $financialFields, $dealerFields) as $col) $values[] = $data[$col] ?? '';
    $values[] = $data['brand_id'];
    $values[] = $data['category_id'];
    $values[] = $images;
    $values[] = $id;

    $stmt = $mysqli->prepare("UPDATE cars SET $set WHERE id=?");
    $stmt->bind_param(str_repeat('s', count($values)), ...$values);
    $stmt->execute();
    $stmt->close();
    $_SESSION['message'] = "Makina u përditësua me sukses!";
    header("Location: /new_project/views/general/cars/list.php");
    exit;
}


if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $row = $mysqli->query("SELECT images FROM cars WHERE id=$id")->fetch_assoc();
    if (!empty($row['images'])) {
        foreach (explode(',', $row['images']) as $img) {
            $file = $_SERVER['DOCUMENT_ROOT'] . '/new_project/uploads/cars/' . $img;
            if (file_exists($file)) unlink($file);
        }
    }
    $stmt = $mysqli->prepare("DELETE FROM cars WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    $_SESSION['message'] = "Makina u fshi me sukses!";
    header("Location: /new_project/views/general/cars/list.php");
    exit;
}
