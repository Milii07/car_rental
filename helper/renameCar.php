<?php
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/new_project/uploads/cars/";

    $oldName = basename($_POST['old_name']);
    $newName = basename($_POST['new_name']);
    $extension = pathinfo($oldName, PATHINFO_EXTENSION);

    $oldFile = $uploadDir . $oldName;
    $newFile = $uploadDir . $newName . "." . $extension;

    if (!file_exists($oldFile)) {
        echo json_encode(["success" => false, "message" => "Skedari nuk ekziston."]);
        exit;
    }

    if (file_exists($newFile)) {
        echo json_encode(["success" => false, "message" => "Një makinë me këtë emër ekziston tashmë."]);
        exit;
    }

    if (rename($oldFile, $newFile)) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Nuk u arrit të ndryshohej emri."]);
    }
}
