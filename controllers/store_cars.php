<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
function upload_images($files)
{
    $uploaded = [];
    if (!empty($files['name'][0])) {
        foreach ($files['tmp_name'] as $key => $tmp_name) {
            if ($tmp_name) {
                $filename = time() . '_' . basename($files['name'][$key]);
                $target = $_SERVER['DOCUMENT_ROOT'] . '/new_project_bk/uploads/cars/' . $filename;
                if (move_uploaded_file($tmp_name, $target)) {
                    $uploaded[] = $filename;
                }
            }
        }
    }
    return implode(',', $uploaded);
}


function insert_car($mysqli, $data)
{
    $data['images'] = isset($_FILES['images']) ? upload_images($_FILES['images']) : '';

    if (empty($data['images'])) {
        $imagesArr = [];
        $hash = null;
    } else {
        $imagesArr = explode(',', $data['images']);
        $firstFile = $_SERVER['DOCUMENT_ROOT'] . '/new_project_bk/uploads/cars/' . $imagesArr[0];
        $hash = md5_file($firstFile);

        $stmtChk = $mysqli->prepare("SELECT id FROM cars WHERE file_hash = ?");
        $stmtChk->bind_param("s", $hash);
        $stmtChk->execute();
        $res = $stmtChk->get_result();
        $stmtChk->close();
        if ($res->num_rows > 0) {
            return false;
        }
    }

    $stmt = $mysqli->prepare("INSERT INTO cars (
        vin, brand_id, category_id, model, year, body_type, color, fuel_type, transmission,
        odometer, license_plate, seating_capacity, owner_name, dob, address, phone, email,
        license_number, tax_id, insurance_provider, policy_number, coverage_type, registration_fee,
        road_tax, sales_tax, payment_method, dealer_info, special_plate, images, created_at, file_hash) 
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

    $created_at = date('Y-m-d H:i:s');

    $stmt->bind_param(
        "siisiisssiiissssssssssdddsissss",
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
        $created_at,
        $hash
    );

    if ($stmt->execute()) {
        $stmt->close();
        return true;
    } else {
        error_log($stmt->error);
        $stmt->close();
        return false;
    }
}
