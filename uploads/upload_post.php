<?php

require_once __DIR__ . '/../db/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $target_dir = "uploads/";
        $file_name = uniqid() . '_' . basename($_FILES["photo"]["name"]);
        $target_file = $target_dir . $file_name;

        $check = getimagesize($_FILES["photo"]["tmp_name"]);
        if ($check === false) {
            die("Skedari nuk është një imazh i vlefshëm.");
        }

        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            $stmt = $mysqli->prepare("INSERT INTO posts (title, price, description, image_url) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sdss", $title, $price, $description, $target_file);

            if ($stmt->execute()) {
                echo "Postimi u shtua me sukses!";
                echo "<br><a href='header.php'>Kthehu tek faqja kryesore</a>";
            } else {
                echo "Gabim gjatë shtimit të postimit: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Gabim gjatë ngarkimit të fotos.";
        }
    } else {
        echo "Ju lutem zgjidhni një foto për t'u ngarkuar.";
    }
} else {
    echo "Kërkesë e pavlefshme.";
}
