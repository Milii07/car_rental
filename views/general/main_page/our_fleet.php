<?php
include_once __DIR__ . '/../../../index.php';

include DB_PATH . 'db.php';
include_once HELPER_PATH . 'client_helper.php';
function getCarFiles()
{
    $carDir = $_SERVER['DOCUMENT_ROOT'] . '/new_project_bk/uploads/cars';
    $carFiles = glob($carDir . "/*.{jpg,png,jpeg,webp}", GLOB_BRACE);
    return array_filter($carFiles, fn($file) => basename($file)[0] != '.');
}

$carFiles = getCarFiles();

$query = "SELECT id, full_name FROM clients ORDER BY full_name ASC";
$result = $mysqli->query($query);

$clients = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $clients[] = $row;
    }
}


?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>

<body>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: #f5f5f5;
        }

        .booking-section {
            position: relative;
            background-image: url("/new_project_bk/uploads/chat.robot/background.jpg");
            height: 90vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin-bottom: 50px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
        }

        .booking-overlay {
            position: absolute;
            inset: 0;
            left: 0;
            background: rgba(0, 0, 0, 0.55);
            display: flex;
            align-items: center;
            justify-content: center;

        }


        @keyframes fadeInUp {
            0% {
                transform: translateY(30px);
                opacity: 0;
            }

            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .footer-section {
            background: #2f3236ff;
            color: #fff;
            padding: 50px 20px 20px;
        }

        .footer-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            max-width: 1200px;
            margin: 0 auto;
            gap: 30px;
        }

        .footer-column {
            flex: 1 1 250px;
            background: rgba(255, 255, 255, 0.05);
            padding: 20px;
            border-radius: 15px;
        }

        .footer-column h3 {
            font-size: 1.2rem;
            margin-bottom: 15px;
            color: #11224d;
        }

        .footer-column p,
        .footer-column li {
            font-size: 0.95rem;
            color: #f1f1f1;
            line-height: 1.6;
        }

        .footer-column ul {
            list-style: none;
            padding: 0;
        }

        .footer-column ul li::before {
            content: "– ";
            color: #2c599d;
        }

        .footer-column a {
            color: #2c599d;
            text-decoration: none;
        }

        .footer-column a:hover {
            text-decoration: underline;
        }

        .footer-bottom {
            text-align: center;
            margin-top: 40px;
            font-size: 0.9rem;
            color: #cccccc;
        }

        .footer-bottom a {
            color: #11224d;
            text-decoration: none;
        }

        .footer-bottom a:hover {
            text-decoration: underline;
        }

        .footer-section .services ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-section .services ul li {
            margin-bottom: 10px;
        }

        .footer-section .services ul li a {
            text-decoration: none;
            color: #ffffff;
            transition: color 0.3s ease;
        }

        .footer-section .services ul li a:hover {
            color: #2c599d;
        }

        @media (max-width: 768px) {
            .footer-container {
                flex-direction: column;
                text-align: center;
                gap: 25px;
            }

            .footer-column {
                flex: unset;
            }
        }

        .modal {
            z-index: 2000 !important;
            transition: transform 0.5s ease, opacity 0.5s ease;
        }

        .modal.fade .modal-dialog {
            transform: translateY(-50px);
            opacity: 0;
        }

        .modal.show .modal-dialog {
            transform: translateY(0);
            opacity: 1;
        }

        .modal-backdrop {
            z-index: 1990 !important;
            background: rgba(0, 0, 0, 0.45);
        }

        .modal-dialog {
            margin-top: 50px;
        }



        .car-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
            justify-content: center;

        }

        .car-card {
            display: flex;
            flex-direction: column;
            background: #fff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            height: 450px;
        }

        .car-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .car-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
            flex-shrink: 0;
            transition: transform 0.3s ease;
        }

        .car-card:hover .car-image {
            transform: scale(1.05);
        }

        .car-content-wrapper {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            justify-content: space-between;
            padding: 15px;
        }

        .car-name {
            font-weight: bold;
            font-size: 1rem;
            margin-bottom: 6px;
            text-transform: uppercase;
            color: #333;
            text-align: center;
        }

        .car-specs {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 6px;
            margin: 8px 0;
            color: #6c757d;
            font-size: 0.85rem;
        }

        .spec {
            background: #f8f9fa;
            padding: 4px 8px;
            border-radius: 6px;
        }

        .car-footer {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            margin-top: 10px;
        }

        .car-price-clean {
            color: #0d8d62ff;
            font-weight: 700;
            font-size: 1.6rem;
            letter-spacing: 0.5px;
        }

        .car-price-clean .price-label {
            font-size: 0.9rem;
            color: #777;
            font-weight: 400;
        }

        .car-rating {
            position: absolute;
            top: 12px;
            right: 12px;
            background: #f98125;
            color: #000;
            font-size: 0.8rem;
            font-weight: 600;
            padding: 5px 8px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }




        .car-content {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            flex-grow: 1;
            padding: 15px;
            text-align: center;
        }

        .car-specs {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 6px;
            margin: 8px 0;
            color: #6c757d;
            font-size: 0.85rem;
        }

        .spec {
            background: #f8f9fa;
            padding: 4px 8px;
            border-radius: 6px;
        }

        .car-footer {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }

        .car-price-clean {
            color: #2c599d;
            font-weight: 700;
            font-size: 1.6rem;
        }

        .car-price-clean .price-label {
            font-size: 0.9rem;
            color: #777;
            font-weight: 400;
        }

        .spinner-container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }

        .spinner-container .spinner-border {
            width: 3rem;
            height: 3rem;
        }


        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: #f5f8ffff;
            background-size: cover;
            background-position: center;
        }

        .navbar {
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            padding: 15px 30px;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(8px);
            display: flex;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .navbar .container {
            width: 100%;
            max-width: 1200px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar .logo {
            color: #fb9b50;
            font-size: 1.8rem;
            font-weight: 600;
            text-decoration: none;
            transition: transform 0.3s ease;
        }

        .navbar .logo:hover {
            transform: scale(1.1);
        }

        .nav-links {
            display: flex;
            gap: 25px;
            list-style: none;
            align-items: center;
            color: #fff
        }

        .nav-links li a {
            text-decoration: none;
            color: #fff;
            font-weight: 500;
            position: relative;
            transition: all 0.3s ease;
        }

        .nav-links li a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -4px;
            left: 0;
            background: #5b84c4;
            transition: width 0.3s ease;
        }

        .nav-links li a:hover::after {
            width: 100%;
        }

        .nav-links li a:hover {
            color: #5b84c4;
        }

        .nav-links .phone a {
            background: #fb9b50;
            color: #f1f3f5ff;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 600;
            border: 2px solid #fb9b50;
            transition: all 0.3s ease;
        }

        .nav-links .phone a:hover {
            background: transparent;
            color: #fb9b50;
            transform: translateY(-2px);
            box-shadow: 0 0 8px rgba(255, 213, 79, 0.4);
        }



        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
        }

        .hamburger span {
            width: 25px;
            height: 3px;
            background: #fff;
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        @media (max-width: 768px) {
            .nav-links {
                position: fixed;
                top: 60px;
                right: -100%;
                width: 200px;
                height: calc(100% - 60px);
                background: rgba(0, 0, 0, 0.9);
                flex-direction: column;
                padding: 20px;
                gap: 15px;
                transition: right 0.3s ease;
            }

            .nav-links.active {
                right: 0;
            }

            .hamburger {
                display: flex;
            }
        }

        .logo img {
            height: 95px;
            width: auto;
            object-fit: contain;
            transition: transform 0.3s ease;
            display: block;

        }

        .logo:hover img {
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .logo img {
                height: 35px;
            }
        }

        .fleet-section {
            position: relative;
            background-image: url("/new_project_bk/uploads/chat.robot/background.jpg");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 90vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            padding: 0 40px;
        }

        .fleet-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            z-index: 1;
        }

        .fleet-content {
            position: relative;
            z-index: 2;
            max-width: 900px;
            text-align: left;
            transform: translateX(-10%);
            /* zhvendos pak nga qendra majtas */
            display: flex;
            flex-direction: column;
            justify-content: center;
            line-height: 1.2;
        }

        .fleet-title {
            font-size: 6rem;
            font-weight: 900;
            color: #FFB800;
            margin: 0;
            text-shadow: 0 4px 15px rgba(0, 0, 0, 0.7);
            transform: translateX(-70%);
        }

        .fleet-subtitle {
            font-size: 1.8rem;
            color: #fff;
            margin-top: 10px;
            line-height: 1.3;
        }


        .fleet-content {
            animation: slideFadeLeft 1s ease forwards;
        }

        @keyframes slideFadeLeft {
            0% {
                opacity: 0;
                transform: translateX(-50px);
            }

            100% {
                opacity: 1;
                transform: translateX(-10%);
            }
        }

        @media (max-width: 768px) {
            .fleet-section {
                justify-content: center;
            }

            .fleet-content {
                transform: translateX(0);
                text-align: center;
            }

            .fleet-title {
                font-size: 3rem;
            }

            .fleet-subtitle {
                font-size: 1.2rem;
                line-height: 1.2;
            }
        }
    </style>

    <div class="main-content">
        <nav class="navbar">
            <div class="container">
                <a href="<?= BASE_URL ?>views/general/home/list.php" class="logo collapsed-sidebar-logo" id="navbar-brand-box img">
                    <img src="<?= UPLOADS_URL ?>cars/LOGO.png" alt="Logo">
                </a>
                <ul class="nav-links">
                    <li><a href="<?= GENERAL_URL ?>main_page/list.php">Home</a></li>
                    <li><a href="<?= GENERAL_URL ?>main_page/list.php">Book Now</a></li>
                    <li><a href="<?= GENERAL_URL ?>main_page/our_fleet.php">Our Fleet</a></li>
                    <li class="phone"><a href="https://wa.me/355695555556" target="_blank">+355 69 555 5556</a></li>
                </ul>
                <div class="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </nav>
        <div class="page-content">


            <div class="container-fluid">


                <div class="row mb-3">
                    <div class="col-12">

                        <section class="fleet-section">
                            <div class="fleet-overlay"></div>
                            <div class="fleet-content">
                                <h1 class="fleet-title">Our Fleet</h1>
                                <p class="fleet-subtitle">
                                    Explore our diverse fleet,<br>
                                    where quality meets choice<br>
                                    for every journey and style.
                                </p>
                            </div>
                        </section>


                    </div>
                </div>

                <div id="availableCars" style="margin-top:30px;"></div>


                <?php
                $allCars = [];
                $newCars = 0;
                $usedCars = 0;
                $uploadDir = '/new_project_bk/uploads/cars/';
                $query = "SELECT * FROM cars ORDER BY id DESC";
                $result = $mysqli->query($query);
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        if (isset($row['category_id'])) {
                            if ($row['category_id'] == 1) $newCars++;
                            elseif ($row['category_id'] == 2) $usedCars++;
                        }
                        $firstImage = '';
                        if (!empty($row['images'])) {
                            $imagesArray = explode(',', $row['images']);
                            $firstImage = trim($imagesArray[0]);
                        }
                        $allCars[] = [
                            'id' => $row['id'],
                            'name' => $row['model'],
                            'image' => $uploadDir . $firstImage,
                            'rating' => $row['rating'] ?? (rand(3, 10) . '.' . rand(0, 9)),
                            'seats' => $row['seating_capacity'],
                            'transmission' => $row['transmission'],
                            'type' => $row['body_type'],
                            'price' => $row['price_per_day'],
                            'category_id' => $row['category_id'] ?? 0
                        ];
                    }
                }
                $totalCars = count($allCars);
                ?>



                <div class="car-grid">
                    <?php foreach ($allCars as $carData):
                        $modalId = "carModal" . $carData['id'];
                    ?>
                        <div class="car-card">
                            <img src="<?= htmlspecialchars($carData['image']) ?>" alt="<?= htmlspecialchars($carData['name']) ?>"
                                class="car-image" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>">
                            <div class="car-rating">⭐ <?= $carData['rating'] ?></div>
                            <div class="car-content">
                                <h3 class="car-name"><?= htmlspecialchars($carData['name']) ?></h3>
                                <div class="car-specs">
                                    <span class="spec">👥 <?= $carData['seats'] ?> vende</span>
                                    <span class="spec">⚙️ <?= htmlspecialchars($carData['transmission']) ?></span>
                                    <span class="spec">🚗 <?= htmlspecialchars($carData['type']) ?></span>
                                </div>
                                <div class="car-footer d-flex justify-content-between align-items-center">
                                    <div class="car-price-clean"> <span class="price-value"><?= $carData['price'] ?>€</span> <span class="price-label">/ditë</span></div>
                                    <?php if (!empty($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                                        <button class="btn btn-sm btn-outline-primary edit-btn"
                                            data-id="<?= $carData['id'] ?>"
                                            data-name="<?= htmlspecialchars($carData['name']) ?>"
                                            data-price="<?= $carData['price'] ?>"
                                            data-seats="<?= $carData['seats'] ?>"
                                            data-transmission="<?= htmlspecialchars($carData['transmission']) ?>"
                                            data-type="<?= htmlspecialchars($carData['type']) ?>"
                                            data-rating="<?= $carData['rating'] ?>">
                                            Edito
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="<?= $modalId ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-body text-center p-4">
                                        <img src="<?= htmlspecialchars($carData['image']) ?>" class="img-fluid rounded mb-3" style="max-height:400px; object-fit:cover;">
                                        <h5 class="fw-semibold"><?= htmlspecialchars($carData['name']) ?></h5>
                                        <p class="text-muted mb-1"><?= htmlspecialchars($carData['type']) ?> | <?= htmlspecialchars($carData['transmission']) ?></p>
                                        <p class="fs-5 fw-bold " style="color: #2c599d;"> <?= $carData['price'] ?> €/ditë</p>
                                        <p class="text-muted small">⭐ <?= $carData['rating'] ?> | 💺 <?= $carData['seats'] ?> vende</p>
                                        <hr class="my-3">
                                        <p>Ky model makine ofron një eksperiencë të jashtëzakonshme udhëtimi. Sediljet janë të rehatshme dhe të rregullueshme sipas preferencave.</p>
                                        <p>Pajisjet teknologjike, përfshirë navigacionin, sistemin e ndihmës për parkim dhe asistencën e vozitjes, garantojnë një eksperiencë të sigurt.</p>
                                        <p>Pajisjet moderne të sigurisë, si airbag-et, ABS, kontrolli i stabilitetit dhe sistemi i paralajmërimit për rrezik.</p>
                                        <button class="btn btn-secondary mt-3" data-bs-dismiss="modal">Mbyll</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>


                <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form id="editCarForm">
                                <div class="modal-header">
                                    <h5 class="modal-title">Ndrysho të dhënat e makinës</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" id="editCarId" name="id">
                                    <div class="mb-3"><label class="form-label">Emri i makinës</label><input type="text" id="editCarName" name="name" class="form-control" required></div>
                                    <div class="mb-3"><label class="form-label">Çmimi (€ / ditë)</label><input type="number" id="editCarPrice" name="price" class="form-control" required></div>
                                    <div class="mb-3"><label class="form-label">Vende</label><input type="number" id="editCarSeats" name="seats" class="form-control" required></div>
                                    <div class="mb-3"><label class="form-label">Transmetimi</label><input type="text" id="editCarTransmission" name="transmission" class="form-control" required></div>
                                    <div class="mb-3"><label class="form-label">Tipi i makinës</label><input type="text" id="editCarType" name="type" class="form-control" required></div>
                                    <div class="mb-3"><label class="form-label">Rating</label><input type="number" step="0.1" id="editCarRating" name="rating" class="form-control" required></div>

                                    <div class="mb-3">
                                        <label class="form-label">Service Type</label>
                                        <select name="service_type" class="form-select" required>
                                            <option value="Weddings">Dasmë</option>
                                            <option value="Night Parties">Night Party</option>
                                            <option value="Airport Transfers">Transfer Aeroporti</option>
                                            <option value="Casinos">Kazino</option>
                                            <option value="birthday">Ditëlindje</option>
                                            <option value="Birthdays">Biznes</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anulo</button>
                                    <button type="submit" class="btn btn-primary">Ruaj Ndryshimet</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


                <div class="modal fade" id="addReservationModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content p-4">
                            <div class="modal-header">
                                <h5 class="modal-title">Shto Rezervim</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form method="POST" action="<?= BASE_URL ?>helper/reservations.php" id="reservationForm">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Klienti</label>
                                        <div class="input-group">
                                            <select name="client_id" id="clientSelect" class="form-select" required>
                                                <option value="">Zgjidh klientin</option>
                                                <?php foreach ($clients as $cl): ?>
                                                    <option value="<?= $cl['id'] ?>"><?= htmlspecialchars($cl['full_name']) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <button type="button" class="btn btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#addClientModal"
                                                data-current-reserve-modal="addReservationModal">
                                                + Shto Klient
                                            </button>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Makina</label>
                                        <select name="car_id" id="carSelect" class="form-select" required>
                                            <option value="">Zgjidh makinen</option>
                                            <?php foreach ($allCars as $car): ?>
                                                <option value="<?= $car['id'] ?>"><?= htmlspecialchars($car['name'] . ' | ' . $car['price'] . '€/ditë') ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Service Type</label>
                                        <input type="text" name="service_type" class="form-control" readonly style="background-color: #e9ecef;">
                                        <small class="text-muted">Service type është vendosur automatikisht nga kërkimi.</small>
                                    </div>

                                    <div class="row">
                                        <div class="col mb-3">
                                            <label class="form-label">Data Fillimit</label>
                                            <input type="date" name="start_date" class="form-control" required>
                                        </div>
                                        <div class="col mb-3">
                                            <label class="form-label">Ora Fillimit</label>
                                            <input type="time" name="time" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Data Mbarimit</label>
                                        <input type="date" name="end_date" class="form-control" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="create" class="btn btn-success w-30">Krijo Rezervim</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="addClientModal" tabindex="-1">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content p-4">
                            <div class="modal-header">
                                <h5 class="modal-title">Shto Klient</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form id="addClientForm">
                                <input type="hidden" name="from" value="reservation_modal">
                                <div class="modal-body row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Profile Type</label>
                                        <input type="text" class="form-control" name="profile_type" value="client" readonly>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Partner Type</label>
                                        <select class="form-control" name="partner_type" required>
                                            <option value="business">Business</option>
                                            <option value="individual" selected>Individual</option>
                                        </select>
                                    </div>
                                    <?php
                                    $clientFields = ['full_name', 'company_name', 'nipt', 'email', 'phone', 'birthday', 'country', 'city', 'zip', 'reference', 'address', 'payment_terms', 'remarks'];
                                    foreach ($clientFields as $field) {
                                        $label = ucwords(str_replace('_', ' ', $field));
                                        echo '<div class="col-md-3 mb-3">
                                        <label class="form-label">' . $label . '</label>
                                        <input type="text" class="form-control" name="' . $field . '">
                                    </div>';
                                    }
                                    ?>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="save_client" class="btn btn-success">Save Client</button>
                                </div>
                            </form>
                            <div id="clientFormMessage" class="mt-2"></div>
                        </div>
                    </div>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



                <footer class="footer-section">
                    <div class="footer-container">
                        <div class="footer-column contact-info">
                            <h3>Contact</h3>
                            <p>Tirana International Airport, Uzina, Tirana 1504, Albania</p>
                            <p><a href="mailto:info@faverent.al">info@faverent.al</a></p>
                            <p class="phone-number">+355 69 55 55 556</p>
                        </div>

                        <div class="footer-column services">
                            <h3>Services</h3>
                            <ul class="services">
                                <li><a href="#nightparties">Rent For Night Parties</a></li>
                                <li><a href="#weddings">Rent For Weddings</a></li>
                                <li><a href="#airport">Rent For Airport Transfers</a></li>
                                <li><a href="#casinos">Rent For Casinos</a></li>
                                <li><a href="#birthdays">Rent For Birthdays</a></li>
                            </ul>
                        </div>

                        <div class="footer-column cta">
                            <h3>Rent a car now!</h3>
                            <a href="<?= GENERAL_URL ?>main_page/list.php" target="_blank">Book Now</a>
                        </div>
                    </div>

                    <div class="footer-bottom">
                        <p>Copyright © 2025 <strong>Auto Future Block</strong> | Powered by
                            <a href="<?= BASE_URL ?>views/general/order_status/list.php" target="_blank">FutureBlock.al</a>
                        </p>
                    </div>
                </footer>
            </div>
        </div>
    </div>
</body>

</html>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {

        function loadCars(serviceType) {
            let pickup_date = $('[name="pickup_date"]').val() || new Date().toISOString().split('T')[0];
            let pickup_time = $('[name="pickup_time"]').val() || '09:00';
            let dropoff_date = $('[name="dropoff_date"]').val() || new Date().toISOString().split('T')[0];
            let dropoff_time = $('[name="dropoff_time"]').val() || '18:00';

            $('.car-grid').remove();
            $('#availableCars').html('<div class="spinner-container"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');

            $.ajax({
                url: '/new_project_bk/helper/reservations.php',
                type: 'POST',
                data: {
                    action: 'search_cars',
                    pickup_date,
                    pickup_time,
                    dropoff_date,
                    dropoff_time,
                    service_type: serviceType
                },
                dataType: 'json',
                success: function(cars) {
                    let html = '';
                    const serviceLabels = {
                        'all': 'Të gjitha shërbimet',
                        'Weddings': 'Dasmë',
                        'Night Parties': 'Night Party',
                        'Airport Transfers': 'Transfer Aeroporti',
                        'Casinos': 'Kazino',
                        'Birthdays': 'Ditëlindje',
                        'Business': 'Biznes'
                    };

                    if (cars.length > 0) {
                        html += `<h3 style="text-align:center;margin-bottom:20px;">Makinat për: <strong>${serviceLabels[serviceType] || serviceType}</strong></h3><div class="car-grid">`;
                        cars.forEach(car => {
                            html += `
                        <div class="car-card">
                            <img src="${car.image}" alt="${car.model}" class="car-image">
                            <div class="car-content">
                                <h3 class="car-name">${car.model}</h3>
                                <div class="car-specs">
                                    <span class="spec">👥 ${car.seats} vende</span>
                                    <span class="spec">⚙️ ${car.transmission}</span>
                                    <span class="spec">🚗 ${car.type}</span>
                                </div>
                                <div class="car-specs" style="margin-top:8px;">
                                    <span class="spec" style="background:#e3f2fd;color:#1976d2;font-weight:600;">${serviceLabels[car.service_type] || car.service_type}</span>
                                </div>
                                <div class="car-footer">
                                    <div class="car-price-clean"><span class="price-value">${car.price_per_day}€</span> / ditë</div>
                                </div>
                            </div>
                        </div>`;
                        });
                        html += '</div>';
                    } else {
                        html = `<div style="text-align:center;padding:40px;"><h3>Nuk ka makina për: <strong>${serviceLabels[serviceType] || serviceType}</strong></h3></div>`;
                    }

                    $('#availableCars').html(html);

                    $('html, body').animate({
                        scrollTop: $('#availableCars').offset().top - 100
                    }, 500);
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert('Gabim gjatë marrjes së makinave.');
                }
            });
        }

        $('.services a').on('click', function(e) {
            e.preventDefault();
            let href = $(this).attr('href');
            let serviceType = 'all';

            if (href.includes('#nightparties')) serviceType = 'Night Parties';
            else if (href.includes('#weddings')) serviceType = 'Weddings';
            else if (href.includes('#airport')) serviceType = 'Airport Transfers';
            else if (href.includes('#casinos')) serviceType = 'Casinos';
            else if (href.includes('#birthdays')) serviceType = 'Birthdays';
            else if (href.includes('#business')) serviceType = 'Business';

            loadCars(serviceType);
        });
    });
</script>