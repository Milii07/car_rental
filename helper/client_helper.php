<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include $_SERVER['DOCUMENT_ROOT'] . '/new_project/db/db.php';


if (isset($_POST['save_client'])) {
    $profile_type = $_POST['profile_type'];
    $partner_type = $_POST['partner_type'];
    $full_name = $_POST['full_name'];
    $company_name = $_POST['company_name'];
    $nipt = $_POST['nipt'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $birthday = $_POST['birthday'];
    $country = $_POST['country'];
    $city = $_POST['city'];
    $zip = $_POST['zip'];
    $reference = $_POST['reference'];
    $address = $_POST['address'];
    $payment_terms = $_POST['payment_terms'];
    $remarks = $_POST['remarks'];

    $uploaded = [];
    if (isset($_FILES['photos']) && $_FILES['photos']['name'][0] != '') {
        foreach ($_FILES['photos']['tmp_name'] as $key => $tmp_name) {
            $filename = basename($_FILES['photos']['name'][$key]);
            $target = $_SERVER['DOCUMENT_ROOT'] . '/new_project/client_management/uploads/' . $filename;
            if (move_uploaded_file($tmp_name, $target)) {
                $uploaded[] = $filename;
            }
        }
    }
    $photos = implode(',', $uploaded);

    $stmt = $mysqli->prepare("INSERT INTO clients (profile_type, partner_type, full_name, company_name, nipt, email, phone, birthday, country, city, zip, reference, address, payment_terms, remarks, photos) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param("ssssssssssssssss", $profile_type, $partner_type, $full_name, $company_name, $nipt, $email, $phone, $birthday, $country, $city, $zip, $reference, $address, $payment_terms, $remarks, $photos);
    $stmt->execute();
    $stmt->close();

    if (isset($_POST['from']) && $_POST['from'] === 'order_status') {
        header("Location: /new_project/views/general/order_status/list.php");
        exit;
    } else {
        header("Location: /new_project/views/general/client_management/list.php");
        exit;
    }
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $res_check = $mysqli->prepare("SELECT COUNT(*) as total FROM reservations WHERE client_id=?");
    $res_check->bind_param("i", $id);
    $res_check->execute();
    $total = $res_check->get_result()->fetch_assoc()['total'];
    $res_check->close();

    if ($total > 0) {
        $_SESSION['error'] = "Ky klient ka rezervime aktive dhe nuk mund të fshihet!";
    } else {
        $row = $mysqli->query("SELECT photos FROM clients WHERE id=$id")->fetch_assoc();
        if (!empty($row['photos'])) {
            foreach (explode(',', $row['photos']) as $img) {
                $file = $_SERVER['DOCUMENT_ROOT'] . '/new_project/client_management/uploads/' . $img;
                if (file_exists($file)) unlink($file);
            }
        }
        $stmt = $mysqli->prepare("DELETE FROM clients WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        $_SESSION['message'] = "Client u fshi me sukses!";
    }

    header("Location: /new_project/views/general/client_management/list.php");
    exit;
}

if (isset($_POST['update_client'])) {
    $id = $_POST['id'];
    $profile_type = $_POST['profile_type'];
    $partner_type = $_POST['partner_type'];
    $full_name = $_POST['full_name'];
    $company_name = $_POST['company_name'];
    $nipt = $_POST['nipt'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $birthday = $_POST['birthday'];
    $country = $_POST['country'];
    $city = $_POST['city'];
    $zip = $_POST['zip'];
    $reference = $_POST['reference'];
    $address = $_POST['address'];
    $payment_terms = $_POST['payment_terms'];
    $remarks = $_POST['remarks'];

    $uploaded = [];
    if (isset($_FILES['photos']) && $_FILES['photos']['name'][0] != '') {
        foreach ($_FILES['photos']['tmp_name'] as $key => $tmp_name) {
            $filename = basename($_FILES['photos']['name'][$key]);
            $target = $_SERVER['DOCUMENT_ROOT'] . '/new_project/client_management/uploads/' . $filename;
            if (move_uploaded_file($tmp_name, $target)) {
                $uploaded[] = $filename;
            }
        }
    }

    $row = $mysqli->query("SELECT photos FROM clients WHERE id=$id")->fetch_assoc();
    $existing_photos = !empty($row['photos']) ? explode(',', $row['photos']) : [];
    $all_photos = implode(',', array_merge($existing_photos, $uploaded));

    $stmt = $mysqli->prepare("UPDATE clients SET profile_type=?, partner_type=?, full_name=?, company_name=?, nipt=?, email=?, phone=?, birthday=?, country=?, city=?, zip=?, reference=?, address=?, payment_terms=?, remarks=?, photos=? WHERE id=?");
    $stmt->bind_param("ssssssssssssssssi", $profile_type, $partner_type, $full_name, $company_name, $nipt, $email, $phone, $birthday, $country, $city, $zip, $reference, $address, $payment_terms, $remarks, $all_photos, $id);
    $stmt->execute();
    $stmt->close();

    $_SESSION['message'] = "Client u përditësua me sukses!";
    header("Location: /new_project/views/general/client_management/list.php");
    exit;
}

$result = $mysqli->query("SELECT * FROM clients ORDER BY id DESC");
