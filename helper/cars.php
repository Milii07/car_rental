<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(__DIR__) . '/index.php';

$carFields       = ['vin', 'model', 'year', 'body_type', 'color', 'fuel_type', 'transmission', 'odometer', 'license_plate', 'seating_capacity'];
$ownerFields     = ['owner_name', 'dob', 'address', 'phone', 'email', 'license_number', 'tax_id'];
$insuranceFields = ['insurance_provider', 'policy_number', 'coverage_type'];
$financialFields = ['registration_fee', 'road_tax', 'sales_tax', 'payment_method', 'price_per_day'];
$dealerFields    = ['dealer_info', 'special_plate'];

function upload_images($files)
{
    $uploaded = [];
    if (!empty($files['name'][0])) {
        foreach ($files['tmp_name'] as $key => $tmp_name) {
            if ($tmp_name) {
                $filename = time() . '_' . basename($files['name'][$key]);
                $target = UPLOADS_PATH . 'cars/' . $filename;
                if (move_uploaded_file($tmp_name, $target)) {
                    $uploaded[] = $filename;
                }
            }
        }
    }
    return implode(',', $uploaded);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
    $data = $_POST;
    $data['images'] = isset($_FILES['images']) ? upload_images($_FILES['images']) : '';

    $hash = null;
    if (!empty($data['images'])) {
        $firstFile = UPLOADS_PATH . 'cars/' . explode(',', $data['images'])[0];
        $hash = md5_file($firstFile);

        $stmtChk = $mysqli->prepare("SELECT id FROM cars WHERE file_hash = ?");
        $stmtChk->bind_param("s", $hash);
        $stmtChk->execute();
        $res = $stmtChk->get_result();
        $stmtChk->close();
        if ($res->num_rows > 0) {
            $_SESSION['message'] = "Kjo makinë ekziston më parë!";
            header("Location: " . GENERAL_URL . "cars/list.php");
            exit;
        }
    }

    $columns = array_merge($carFields, $ownerFields, $insuranceFields, $financialFields, $dealerFields, ['brand_id', 'category_id', 'images', 'file_hash']);
    $placeholders = implode(',', array_fill(0, count($columns), '?'));
    $values = [];
    foreach (array_merge($carFields, $ownerFields, $insuranceFields, $financialFields, $dealerFields) as $col) {
        $values[] = $data[$col] ?? '';
    }
    $values[] = $data['brand_id'] ?? '';
    $values[] = $data['category_id'] ?? '';
    $values[] = $data['images'];
    $values[] = $hash;

    $stmt = $mysqli->prepare("INSERT INTO cars (" . implode(',', $columns) . ") VALUES ($placeholders)");
    $stmt->bind_param(str_repeat('s', count($values)), ...$values);
    $stmt->execute();
    $stmt->close();

    $_SESSION['message'] = "Makina u shtua me sukses!";
    header("Location: " . GENERAL_URL . "cars/list.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update']) && isset($_POST['id'])) {
    $id = $_POST['id'];
    $data = $_POST;

    $existingImages = $mysqli->query("SELECT images FROM cars WHERE id = $id")->fetch_assoc()['images'];
    $newImages = upload_images($_FILES['images']);
    $images = $newImages ? $newImages : $existingImages;
    $data['images'] = $images;

    $hash = null;
    if (!empty($images)) {
        $firstFile = UPLOADS_PATH . 'cars/' . explode(',', $images)[0];
        $hash = md5_file($firstFile);
    }

    $columns = array_merge($carFields, $ownerFields, $insuranceFields, $financialFields, $dealerFields, ['brand_id', 'category_id', 'images', 'file_hash']);
    $set = implode(',', array_map(fn($c) => "$c = ?", $columns));

    $values = [];
    foreach (array_merge($carFields, $ownerFields, $insuranceFields, $financialFields, $dealerFields) as $col) {
        $values[] = $data[$col] ?? '';
    }
    $values[] = $data['brand_id'] ?? '';
    $values[] = $data['category_id'] ?? '';
    $values[] = $data['images'];
    $values[] = $hash;
    $values[] = $id;

    $stmt = $mysqli->prepare("UPDATE cars SET $set WHERE id = ?");
    $stmt->bind_param(str_repeat('s', count($values)), ...$values);
    $stmt->execute();
    $stmt->close();

    $_SESSION['message'] = "Makina u përditësua me sukses!";
    header("Location: " . GENERAL_URL . "cars/list.php");
    exit;
}

if (isset($_GET['delete'])) {
    $id  = intval($_GET['delete']);
    $row = $mysqli->query("SELECT images FROM cars WHERE id = $id")->fetch_assoc();

    if (!empty($row['images'])) {
        foreach (explode(',', $row['images']) as $img) {
            $file = UPLOADS_PATH . 'cars/' . $img;
            if (file_exists($file)) unlink($file);
        }
    }

    $stmt = $mysqli->prepare("DELETE FROM cars WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    $_SESSION['message'] = "Makina u fshi me sukses!";
    header("Location: " . GENERAL_URL . "cars/list.php");
    exit;
}
