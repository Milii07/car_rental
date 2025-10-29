<?php
include_once __DIR__ . '/../../../index.php';

include DB_PATH . 'db.php';


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

include_once LAYOUT_PATH . 'header.php';
?>

<style>
    .booking-section {
        position: relative;
        background-image: url("/new_project_bk/uploads/chat.robot/background.jpg");
        height: 90vh;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 50px;
        margin-right: 1px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
    }

    .booking-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.55);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .booking-box {
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 30px 40px;
        width: 90%;
        max-width: 900px;
        color: #fff;
        text-align: center;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
        animation: fadeInUp 0.8s ease;
    }

    .booking-title {
        font-size: 2rem;
        font-weight: 600;
        margin-bottom: 25px;
        color: #fff;
        text-shadow: 0 2px 6px rgba(0, 0, 0, 0.4);
    }

    .booking-form {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 15px;
        width: 100%;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        text-align: left;
    }

    .form-group label {
        font-size: 0.9rem;
        margin-bottom: 5px;
        font-weight: 500;
        color: #e5e7eb;
    }

    .form-group input {
        padding: 8px 10px;
        border-radius: 10px;
        border: none;
        outline: none;
        background: rgba(255, 255, 255, 0.9);
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .form-group input:focus {
        box-shadow: 0 0 8px #1E40AF;
    }

    .checkbox-inline {
        flex-direction: row;
        align-items: center;
        justify-content: flex-start;
        gap: 6px;
    }

    .search-btn {
        margin-top: 25px;
        background: linear-gradient(45deg, #1E3A8A, #2563EB);
        color: #fff;
        border: none;
        padding: 12px 40px;
        font-size: 1rem;
        border-radius: 30px;
        cursor: pointer;
        transition: all 0.3s ease;
        letter-spacing: 0.5px;
    }

    .search-btn:hover {
        background: linear-gradient(45deg, #2563EB, #1E40AF);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(30, 64, 175, 0.4);
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
        background: #032c69;
        color: #fff;
        padding: 50px 20px 20px;
        font-family: 'Poppins', sans-serif;
        border-radius: 20px;
        margin-bottom: 20px;
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
        color: #ffd54f;
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
        color: #ffd54f;
    }

    .footer-column a {
        color: #0dcaf0;
        text-decoration: none;
    }

    .footer-column a:hover {
        text-decoration: underline;
    }

    .rent-btn {
        display: inline-block;
        margin-top: 10px;
        padding: 12px 30px;
        background: #ffd54f;
        color: #032c69;
        font-weight: 600;
        border-radius: 30px;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .rent-btn:hover {
        background: #e6c04d;
        transform: translateY(-2px);
    }

    .footer-bottom {
        text-align: center;
        margin-top: 40px;
        font-size: 0.9rem;
        color: #cccccc;
    }

    .footer-bottom a {
        color: #ffd54f;
        text-decoration: none;
    }

    .footer-bottom a:hover {
        text-decoration: underline;
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
        color: #0dcaf0;
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

    .btn-sm {
        font-size: 0.75rem;
        transition: all 0.3s ease;
    }

    .btn-sm:hover {
        transform: scale(1.05);
    }

    .d-flex-btns {
        display: flex;
        justify-content: space-between;
        gap: 4px;
    }

    .d-flex-btns .btn {
        flex: 1;
        transition: all 0.3s ease;
    }

    .card {
        transform: translateY(30px);
        opacity: 0;
        transition: transform 0.6s ease, opacity 0.6s ease, box-shadow 0.3s ease, background 0.5s ease;
        border-radius: 12px;
        cursor: pointer;
        background: linear-gradient(145deg, #f0f3f7, #d9e2ec);
        position: relative;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);

    }

    .card.show {
        transform: translateY(0);
        opacity: 1;
    }

    .card:hover {
        transform: translateY(-10px) scale(1.05);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
    }

    .card img {
        transition: transform 0.5s ease;
    }

    .card:hover img {
        transform: scale(1.1) rotate(1deg);
    }

    .card-title {
        font-weight: bold;
        font-size: 0.85rem;
        color: #444;
        margin-bottom: 6px;
        text-transform: uppercase;
    }

    .card::after {
        content: "";
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: -100%;
        background: linear-gradient(120deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.05), rgba(255, 255, 255, 0.2));
        transform: skewX(-25deg);
        transition: left 0.5s ease;
    }

    .card:hover::after {
        left: 200%;
    }

    .counter {
        display: inline-block;
        font-size: 2rem;
        font-weight: bold;
        color: #fff;
        background: none;
        margin-top: 5px;
    }

    @keyframes pop {
        0% {
            transform: scale(0.5);
            opacity: 0;
        }

        60% {
            transform: scale(1.2);
            opacity: 1;
        }

        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    .btn-primary:hover {
        background: linear-gradient(45deg, #1e3c72, #2a5298);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    .btn-warning:hover {
        background: linear-gradient(45deg, #f7971e, #ffd200);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    .btn-secondary:hover {
        background: #6c757d;
        color: #fff;
    }

    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

    .car-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        padding: 1rem;
        font-family: 'Poppins', sans-serif;
    }

    .car-card {
        background: #fff;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
    }

    .car-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .car-image {
        width: 100%;
        height: 180px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .car-card:hover .car-image {
        transform: scale(1.05);
    }

    .car-rating {
        position: absolute;
        top: 12px;
        right: 12px;
        background: rgba(255, 193, 7, 0.9);
        color: #000;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 5px 8px;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .car-content {
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
        margin-top: 10px;
    }

    .car-price-clean {
        color: #0d8d62ff;
        font-weight: 700;
        font-size: 1.6rem;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }

    .car-price-clean .price-label {
        font-size: 0.9rem;
        color: #777;
        font-weight: 400;
    }
</style>


<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Dashboard Auto Future Block</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                        </div>
                    </div>
                    <section class="booking-section">
                        <div class="booking-overlay">
                            <div class="booking-box">
                                <h2 class="booking-title">Rezervo makinën tënde tani</h2>
                                <form id="bookingForm" class="booking-form">
                                    <div class="form-grid">

                                        <div class="form-group">
                                            <label>Pick-up location</label>
                                            <input type="text" name="pickup_location" placeholder="TIA" required>
                                        </div>

                                        <div class="form-group checkbox-inline">
                                            <input type="checkbox" id="same-location" checked>
                                            <label for="same-location">Return car in same location</label>
                                        </div>

                                        <div class="form-group">
                                            <label>Pick-up date</label>
                                            <input type="date" name="pickup_date" value="2025-10-29" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Pick-up time</label>
                                            <input type="time" name="pickup_time" value="08:00" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Drop-off date</label>
                                            <input type="date" name="dropoff_date" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Drop-off time</label>
                                            <input type="time" name="dropoff_time" value="08:00" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Driver's country</label>
                                            <input type="text" name="country" value="Albania" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Driver's age</label>
                                            <input type="text" name="age" placeholder="18 - 65" required>
                                        </div>
                                    </div>

                                    <button type="submit" class="search-btn">Search</button>
                                </form>
                            </div>
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

            <div class="dashboard-cards" style="display:flex; gap:20px; flex-wrap:wrap; margin-bottom:30px;">
                <div class="card total-card keep-color" style="flex:1; min-width:150px; padding:20px; border-radius:12px; background:#1E40AF; color:#fff !important; text-align:center;">
                    <h5 class="text-white">Total Makina</h5>
                    <span class="counter" data-target="<?= $totalCars ?>">0</span>
                </div>
                <div class="card total-card keep-color" style="flex:1; min-width:150px; padding:20px; border-radius:12px; background:#10B981; color:#fff !important; text-align:center;">
                    <h5 class="text-white">Të Reja</h5>
                    <span class="counter" data-target="<?= $newCars ?>">0</span>
                </div>
                <div class="card total-card keep-color" style="flex:1; min-width:150px; padding:20px; border-radius:12px; background:#F59E0B; color:#fff !important; text-align:center;">
                    <h5 class="text-white">Të Përdorura</h5>
                    <span class="counter" data-target="<?= $usedCars ?>">0</span>
                </div>
            </div>

            <canvas id="salesChart" style="max-height:400px; margin-bottom:30px;"></canvas>

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
                                <div class="car-price-clean">💰 <span class="price-value"><?= $carData['price'] ?>€</span> <span class="price-label">/ditë</span></div>
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
                                    <p class="fs-5 fw-bold text-success">💰 <?= $carData['price'] ?> €/ditë</p>
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
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anulo</button>
                                <button type="submit" class="btn btn-primary">Ruaj Ndryshimet</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

            <script>
                document.addEventListener("DOMContentLoaded", () => {
                    const ctx = document.getElementById('salesChart')?.getContext('2d');
                    if (ctx) {
                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                                datasets: [{
                                    label: 'Shitjet e Makinave',
                                    data: [12, 19, 14, 18, 22, 20, 25, 30, 28, 26, 32, 35],
                                    backgroundColor: '#032c69ff',
                                    borderRadius: 8
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    }

                    const counters = document.querySelectorAll('.counter');
                    counters.forEach(counter => {
                        const target = +counter.getAttribute('data-target');
                        let count = 0;
                        const step = target / 200;
                        const updateCounter = () => {
                            if (count < target) {
                                count += step;
                                counter.innerText = Math.ceil(count);
                                requestAnimationFrame(updateCounter);
                            } else {
                                counter.innerText = target;
                            }
                        };
                        updateCounter();
                    });

                    const cards = document.querySelectorAll('.card');
                    window.addEventListener('load', () => {
                        cards.forEach((card, index) => {
                            setTimeout(() => card.classList.add('show'), index * 100);
                        });
                    });

                    const editModalEl = document.getElementById('editModal');
                    const editModal = new bootstrap.Modal(editModalEl);
                    const editForm = document.getElementById('editCarForm');

                    document.querySelectorAll(".edit-btn").forEach(btn => {
                        btn.addEventListener("click", () => {
                            editForm.id.value = btn.dataset.id;
                            editForm.name.value = btn.dataset.name;
                            editForm.price.value = btn.dataset.price;
                            editForm.seats.value = btn.dataset.seats;
                            editForm.transmission.value = btn.dataset.transmission;
                            editForm.type.value = btn.dataset.type;
                            editForm.rating.value = btn.dataset.rating;

                            editModal.show();
                        });
                    });

                    editForm.addEventListener('submit', async e => {
                        e.preventDefault();
                        const formData = new FormData(editForm);

                        try {
                            const res = await fetch('/new_project_bk/helper/updateCar.php', {
                                method: 'POST',
                                body: formData
                            });

                            const result = await res.json();

                            if (result.success) {
                                editModal.hide();
                                Swal.fire('Sukses!', 'Makina u përditësua me sukses.', 'success')
                                    .then(() => location.reload());
                            } else {
                                Swal.fire('Gabim!', result.message || 'Ndodhi një problem.', 'error');
                            }
                        } catch (err) {
                            console.error(err);
                            Swal.fire('Gabim!', 'Gabim gjatë komunikimit me serverin.', 'error');
                        }
                    });
                });
            </script>

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
                        <ul>
                            <li><a href="<?= BASE_URL ?>views/general/order_status/list.php#nightparties">Rent For Night Parties</a></li>
                            <li><a href="<?= BASE_URL ?>views/general/order_status/list.php#weddings">Rent For Weddings</a></li>
                            <li><a href="<?= BASE_URL ?>views/general/order_status/list.php#airport">Rent For Airport Transfers</a></li>
                            <li><a href="<?= BASE_URL ?>views/general/order_status/list.php#casinos">Rent For Casinos</a></li>
                            <li><a href="<?= BASE_URL ?>views/general/order_status/list.php#birthdays">Rent For Birthdays</a></li>
                        </ul>
                    </div>

                    <div class="footer-column cta">
                        <h3>Rent a car now!</h3>
                        <a href="/new_project_bk/views/general/order_status/list.php" class="rent-btn">Book Now</a>
                    </div>
                </div>

                <div class="footer-bottom">
                    <p>Copyright © 2025 <strong>FaveRent</strong> | Powered by

                        <a href="<?= BASE_URL ?>views/general/order_status/list.php" target="_blank">FutureBlock.al</a>
                    </p>
                </div>
            </footer>


            <?php include LAYOUT_PATH . 'footer.php'; ?>

            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
                $(document).ready(function() {
                    $('#bookingForm').on('submit', function(e) {
                        e.preventDefault();

                        let pickup_date = $('[name="pickup_date"]').val();
                        let pickup_time = $('[name="pickup_time"]').val();
                        let dropoff_date = $('[name="dropoff_date"]').val();
                        let dropoff_time = $('[name="dropoff_time"]').val();

                        if (!pickup_date || !pickup_time || !dropoff_date || !dropoff_time) {
                            alert('Plotëso të gjitha fushat!');
                            return;
                        }

                        $.ajax({
                            url: '/new_project_bk/helper/reservations.php',
                            type: 'POST',
                            data: {
                                action: 'search_cars',
                                pickup_date: pickup_date,
                                pickup_time: pickup_time,
                                dropoff_date: dropoff_date,
                                dropoff_time: dropoff_time
                            },
                            dataType: 'json',
                            success: function(cars) {
                                let html = '';

                                if (cars.length > 0) {
                                    html += '<h3 style="margin-bottom: 20px; text-align: center;">Makinat e lira për zgjedhjet tuaja:</h3><div class="car-grid">';

                                    cars.forEach(function(car) {
                                        let modalId = 'carModalAvailable' + car.id;

                                        html += `
                        <div class="car-card">
                            <img src="${car.image}" alt="${car.model}" class="car-image" data-bs-toggle="modal" data-bs-target="#${modalId}">
                            <div class="car-rating">⭐ ${car.rating}</div>
                            <div class="car-content">
                                <h3 class="car-name">${car.model}</h3>
                                <div class="car-specs">
                                    <span class="spec">👥 ${car.seats} vende</span>
                                    <span class="spec">⚙️ ${car.transmission}</span>
                                    <span class="spec">🚗 ${car.type}</span>
                                </div>
                                <div class="car-footer">
                                    <div class="car-price-clean">💰 <span class="price-value">${car.price_per_day}€</span> <span class="price-label">/ditë</span></div>
                                    <button class="btn btn-success btn-sm reserve-btn" 
                                        data-car-id="${car.id}"
                                        data-car-name="${car.model}"
                                        data-pickup-date="${pickup_date}"
                                        data-pickup-time="${pickup_time}"
                                        data-dropoff-date="${dropoff_date}"
                                        data-dropoff-time="${dropoff_time}">
                                        Rezervo
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="${modalId}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-body text-center p-4">
                                        <img src="${car.image}" class="img-fluid rounded mb-3" style="max-height:400px; object-fit:cover;">
                                        <h5 class="fw-semibold">${car.model}</h5>
                                        <p class="text-muted mb-1">${car.type} | ${car.transmission}</p>
                                        <p class="fs-5 fw-bold text-success">💰 ${car.price_per_day} €/ditë</p>
                                        <p class="text-muted small">⭐ ${car.rating} | 💺 ${car.seats} vende</p>
                                        <hr class="my-3">
                                        <p>Ky model makine ofron një eksperiencë të jashtëzakonshme udhëtimi. Sediljet janë të rehatshme dhe të rregullueshme sipas preferencave.</p>
                                        <p>Pajisjet teknologjike, përfshirë navigacionin, sistemin e ndihmës për parkim dhe asistencën e vozitjes, garantojnë një eksperiencë të sigurt.</p>
                                        <p>Pajisjet moderne të sigurisë, si airbag-et, ABS, kontrolli i stabilitetit dhe sistemi i paralajmërimit për rrezik.</p>
                                        <button class="btn btn-success mt-3 reserve-btn-modal"
                                            data-car-id="${car.id}"
                                            data-car-name="${car.model}"
                                            data-pickup-date="${pickup_date}"
                                            data-pickup-time="${pickup_time}"
                                            data-dropoff-date="${dropoff_date}"
                                            data-dropoff-time="${dropoff_time}"
                                            data-bs-dismiss="modal">
                                            Rezervo Këtë Makinë
                                        </button>
                                        <button class="btn btn-secondary mt-3" data-bs-dismiss="modal">Mbyll</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        `;
                                    });

                                    html += '</div>';
                                } else {
                                    html = '<div style="text-align: center; padding: 40px;"><h3>Nuk ka makina të lira për këto data.</h3><p>Ju lutem zgjidhni data të tjera.</p></div>';
                                }

                                $('.car-grid').first().replaceWith(html);

                                $('.dashboard-cards').hide();
                                $('#salesChart').hide();

                                var modalElements = document.querySelectorAll('.modal');
                                modalElements.forEach(el => {
                                    new bootstrap.Modal(el);
                                });

                                $('.reserve-btn, .reserve-btn-modal').on('click', function() {
                                    let carId = $(this).data('car-id');
                                    let carName = $(this).data('car-name');
                                    let pickupDate = $(this).data('pickup-date');
                                    let pickupTime = $(this).data('pickup-time');
                                    let dropoffDate = $(this).data('dropoff-date');
                                    let dropoffTime = $(this).data('dropoff-time');

                                    $('.modal').modal('hide');

                                    if (confirm(`Dëshiron të rezervosh ${carName}?\n\nPick-up: ${pickupDate} ${pickupTime}\nDrop-off: ${dropoffDate} ${dropoffTime}`)) {
                                        $.ajax({
                                            url: '/new_project_bk/helper/reservations.php',
                                            type: 'POST',
                                            data: {
                                                action: 'create_reservation',
                                                car_id: carId,
                                                pickup_date: pickupDate,
                                                pickup_time: pickupTime,
                                                dropoff_date: dropoffDate,
                                                dropoff_time: dropoffTime
                                            },
                                            dataType: 'json',
                                            success: function(response) {
                                                if (response.success) {
                                                    alert('Rezervimi u krye me sukses!');
                                                    location.reload();
                                                } else {
                                                    alert('Gabim: ' + (response.message || 'Rezervimi nuk u krye.'));
                                                }
                                            },
                                            error: function(err) {
                                                console.log(err);
                                                alert('Ka ndodhur një gabim gjatë rezervimit!');
                                            }
                                        });
                                    }
                                });

                                $('html, body').animate({
                                    scrollTop: $('#availableCars').offset().top - 100
                                }, 500);
                            },
                            error: function(err) {
                                console.log(err);
                                alert('Ka ndodhur një gabim!');
                            }
                        });
                    });
                });
            </script>