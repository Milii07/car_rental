<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);


include 'db/db.php';

if (!$mysqli) {
    die("Database connection failed: " . mysqli_connect_error());
}

$uploadDir = '../uploads/cars/';

$brands = $mysqli->query("SELECT id, name FROM brands ORDER BY name ASC")->fetch_all(MYSQLI_ASSOC);
$categories = $mysqli->query("SELECT id, name FROM categories ORDER BY name ASC")->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $vin = $_POST['vin'] ?? '';
    $make = $_POST['make'] ?? '';
    $brand_id = $_POST['brand_id'] ?? null;
    $category_id = $_POST['category_id'] ?? null;
    $model = $_POST['model'] ?? '';
    $year = $_POST['year'] ?? '';
    $body_type = $_POST['body_type'] ?? '';
    $color = $_POST['color'] ?? '';
    $fuel_type = $_POST['fuel_type'] ?? '';
    $transmission = $_POST['transmission'] ?? '';
    $odometer = $_POST['odometer'] ?? '';
    $license_plate = $_POST['license_plate'] ?? '';
    $seating_capacity = $_POST['seating_capacity'] ?? '';

    $owner_name = $_POST['owner_name'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $address = $_POST['address'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $license_number = $_POST['license_number'] ?? '';
    $tax_id = $_POST['tax_id'] ?? '';

    $insurance_provider = $_POST['insurance_provider'] ?? '';
    $policy_number = $_POST['policy_number'] ?? '';
    $coverage_type = $_POST['coverage_type'] ?? '';

    $registration_fee = $_POST['registration_fee'] ?? 0;
    $road_tax = $_POST['road_tax'] ?? 0;
    $sales_tax = $_POST['sales_tax'] ?? 0;
    $payment_method = $_POST['payment_method'] ?? '';

    $dealer_info = $_POST['dealer_info'] ?? '';
    $special_plate = $_POST['special_plate'] ?? '';

    $uploadedImages = [];
    if (!empty($_FILES['car_images']['name'][0])) {
        $totalFiles = count($_FILES['car_images']['name']);
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        for ($i = 0; $i < $totalFiles; $i++) {
            $fileName = $_FILES['car_images']['name'][$i];
            $fileTmp = $_FILES['car_images']['tmp_name'][$i];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array($fileExt, $allowed)) {
                $newFileName = uniqid('car_') . '.' . $fileExt;
                if (move_uploaded_file($fileTmp, $uploadDir . $newFileName)) {
                    $uploadedImages[] = $newFileName;
                }
            }
        }
    }

    $imagesString = implode(',', $uploadedImages);


    $stmt = $mysqli->prepare("INSERT INTO cars 
(vin, brand_id, category_id, model, year, body_type, color, fuel_type, transmission, odometer, license_plate, seating_capacity, owner_name, dob, address, phone, email, license_number, tax_id, insurance_provider, policy_number, coverage_type, registration_fee, road_tax, sales_tax, payment_method, dealer_info, special_plate, images, created_at)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

    if ($stmt === false) {
        die("Prepare failed: " . $mysqli->error);
    }

    $brand_id = intval($brand_id);
    $category_id = intval($category_id);
    $year = intval($year);
    $odometer = intval($odometer);
    $seating_capacity = intval($seating_capacity);

    $registration_fee = floatval($registration_fee);
    $road_tax = floatval($road_tax);
    $sales_tax = floatval($sales_tax);

    $license_plate = $license_plate ?: NULL;
    $tax_id = $tax_id ?: NULL;
    $dealer_info = $dealer_info ?: NULL;
    $special_plate = $special_plate ?: NULL;
    $imagesString = $imagesString ?: NULL;

    $stmt->bind_param(
        "siissssssisssssssssssddds",
        $vin,
        $brand_id,
        $category_id,
        $model,
        $year,
        $body_type,
        $color,
        $fuel_type,
        $transmission,
        $odometer,
        $license_plate,
        $seating_capacity,
        $owner_name,
        $dob,
        $address,
        $phone,
        $email,
        $license_number,
        $tax_id,
        $insurance_provider,
        $policy_number,
        $coverage_type,
        $registration_fee,
        $road_tax,
        $sales_tax,
        $payment_method,
        $dealer_info,
        $special_plate,
        $imagesString
    );


    if ($stmt->execute()) {
        $_SESSION['message'] = "Car registered successfully!";
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            padding: 20px;
            margin: 0;
        }

        .card {
            background: #ffffff;
            border-radius: 15px;
            padding: 25px 30px;
            width: 100%;
            max-width: 900px;
            margin: auto;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        h2 {
            font-weight: 700;
            color: #34495e;
            margin-bottom: 25px;
        }

        h4 {
            font-weight: 600;
            font-size: 1.15rem;
            border-bottom: 2px solid #5c6bc0;
            padding-bottom: 5px;
            margin-bottom: 15px;
            color: #5c6bc0;
        }

        .btn-gradient {
            background: linear-gradient(90deg, #5c6bc0, #7986cb);
            color: #fff;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-gradient:hover {
            background: linear-gradient(90deg, #7986cb, #5c6bc0);
        }

        input.form-control,
        select.form-select {
            border-radius: 8px;
            padding: 10px;
        }

        .text-muted {
            font-size: 0.85rem;
        }

        @media (max-width: 576px) {
            .row.g-3>[class*='col-'] {
                margin-bottom: 10px;
            }
        }
    </style>
</head>

<body>

    <div class="card">

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['message']) ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <h2 class="text-center mb-4">Register Your Car</h2>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-section mb-4">
                <h4><i class="bi bi-car-front-fill"></i> Vehicle Details</h4>
                <div class="row g-3">
                    <div class="col-md-3"><input type="text" class="form-control" name="vin" placeholder="VIN" required></div>
                    <div class="col-md-3">
                        <select name="brand_id" class="form-select" required>
                            <option selected disabled>Brand</option>
                            <?php foreach ($brands as $b): ?>
                                <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="category_id" class="form-select" required>
                            <option selected disabled>Category</option>
                            <?php foreach ($categories as $c): ?>
                                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-md-3"><input type="text" class="form-control" name="model" placeholder="Model" required></div>
                    <div class="col-md-2"><input type="number" class="form-control" name="year" placeholder="Year" required></div>
                    <div class="col-md-3">
                        <select name="body_type" class="form-select" required>
                            <option selected disabled>Body Type</option>
                            <option>Sedan</option>
                            <option>SUV</option>
                            <option>Truck</option>
                            <option>Hatchback</option>
                            <option>Coupe</option>
                        </select>
                    </div>
                    <div class="col-md-2"><input type="text" class="form-control" name="color" placeholder="Color" required></div>
                    <div class="col-md-2">
                        <select name="fuel_type" class="form-select" required>
                            <option selected disabled>Fuel Type</option>
                            <option>Gasoline</option>
                            <option>Diesel</option>
                            <option>Electric</option>
                            <option>Hybrid</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-md-3">
                        <select name="transmission" class="form-select" required>
                            <option selected disabled>Transmission</option>
                            <option>Manual</option>
                            <option>Automatic</option>
                        </select>
                    </div>
                    <div class="col-md-3"><input type="number" class="form-control" name="odometer" placeholder="Odometer (km)" required></div>
                    <div class="col-md-3"><input type="text" class="form-control" name="license_plate" placeholder="License Plate"></div>
                    <div class="col-md-3"><input type="number" class="form-control" name="seating_capacity" placeholder="Seating Capacity" required></div>
                </div>
            </div>

            <div class="form-section mb-4">
                <h4><i class="bi bi-person-fill"></i> Owner Details</h4>
                <div class="row g-3">
                    <div class="col-md-4"><input type="text" class="form-control" name="owner_name" placeholder="Full Name" required></div>
                    <div class="col-md-3"><input type="date" class="form-control" name="dob" required></div>
                    <div class="col-md-5"><input type="text" class="form-control" name="address" placeholder="Address" required></div>
                </div>
                <div class="row g-3 mt-2">
                    <div class="col-md-3"><input type="text" class="form-control" name="phone" placeholder="Phone" required></div>
                    <div class="col-md-3"><input type="email" class="form-control" name="email" placeholder="Email" required></div>
                    <div class="col-md-3"><input type="text" class="form-control" name="license_number" placeholder="Driver's License #" required></div>
                    <div class="col-md-3"><input type="text" class="form-control" name="tax_id" placeholder="Tax ID / SSN"></div>
                </div>
            </div>

            <div class="form-section mb-4">
                <h4><i class="bi bi-shield-fill-check"></i> Insurance</h4>
                <div class="row g-3">
                    <div class="col-md-4"><input type="text" class="form-control" name="insurance_provider" placeholder="Insurance Provider" required></div>
                    <div class="col-md-4"><input type="text" class="form-control" name="policy_number" placeholder="Policy Number" required></div>
                    <div class="col-md-4">
                        <select name="coverage_type" class="form-select" required>
                            <option selected disabled>Coverage Type</option>
                            <option>Liability</option>
                            <option>Comprehensive</option>
                            <option>Collision</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-section mb-4">
                <h4><i class="bi bi-cash-stack"></i> Financials</h4>
                <div class="row g-3">
                    <div class="col-md-3"><input type="number" class="form-control" name="registration_fee" placeholder="Registration Fee"></div>
                    <div class="col-md-3"><input type="number" class="form-control" name="road_tax" placeholder="Road Tax"></div>
                    <div class="col-md-3"><input type="number" class="form-control" name="sales_tax" placeholder="Sales Tax"></div>
                    <div class="col-md-3"><input type="text" class="form-control" name="payment_method" placeholder="Payment Method"></div>
                </div>
            </div>

            <div class="form-section mb-4">
                <h4><i class="bi bi-building"></i> Dealer & Special Plate</h4>
                <div class="row g-3">
                    <div class="col-md-6"><input type="text" class="form-control" name="dealer_info" placeholder="Dealer Info"></div>
                    <div class="col-md-6"><input type="text" class="form-control" name="special_plate" placeholder="Special Plate"></div>
                </div>
            </div>

            <div class="form-section mb-3">
                <h4><i class="bi bi-images"></i> Car Images</h4>
                <input type="file" class="form-control" name="car_images[]" multiple accept="image/*">
                <small class="text-muted">Mund të ngarkoni më shumë se një foto (jpg, png, jpeg)</small>
            </div>

            <button type="submit" class="btn btn-gradient w-100 mt-3">Register Car</button>
        </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>