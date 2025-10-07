<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/new_project/db/db.php';

// FUNKSIONET
function getCarFiles()
{
    $carDir = $_SERVER['DOCUMENT_ROOT'] . '/new_project/uploads/cars';
    $carFiles = glob($carDir . "/*.{jpg,png,jpeg,webp}", GLOB_BRACE);
    return array_filter($carFiles, fn($file) => basename($file)[0] != '.');
}

function syncCarsToDB($mysqli)
{
    $uploadsDir = $_SERVER['DOCUMENT_ROOT'] . '/new_project/uploads/cars/';
    $files = glob($uploadsDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
    foreach ($files as $file) {
        $fileName = basename($file);
        $carName = pathinfo($file, PATHINFO_FILENAME);
        $images = '/uploads/cars/' . $fileName;

        // Kontrollo nëse makina ekziston
        $stmt = $mysqli->prepare("SELECT id FROM cars WHERE images=?");
        $stmt->bind_param("s", $images);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        if ($result->num_rows > 0) continue;

        $price_per_day = rand(50, 300);
        $transmission = 'Automatic';
        $year = date('Y');
        $brand_id = 1;
        $category_id = 1;
        $vin = strtoupper(string: substr(md5($carName . uniqid('', true)), 0, 10));

        $stmtInsert = $mysqli->prepare("
            INSERT INTO cars (model, vin, brand_id, category_id, images, price_per_day, transmission, year)
            VALUES (?,?,?,?,?,?,?,?)");
        $stmtInsert->bind_param("ssiidssi", $carName, $vin, $brand_id, $category_id, $images, $price_per_day, $transmission, $year);
        $stmtInsert->execute();
        $stmtInsert->close();
    }
}

syncCarsToDB($mysqli);

// Merr makinat dhe klientët
$cars = $mysqli->query("SELECT * FROM cars")->fetch_all(MYSQLI_ASSOC);
$clients = $mysqli->query("SELECT * FROM clients ORDER BY full_name ASC")->fetch_all(MYSQLI_ASSOC);
$carFiles = getCarFiles();

include $_SERVER['DOCUMENT_ROOT'] . '/new_project/views/layout/header.php';
?>


<style>
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

    .row.g-4 {
        transition: all 0.5s ease;
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

    .car-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        background: linear-gradient(90deg, #007bff, #00c6ff);
        color: #fff;
        font-size: 0.7rem;
        padding: 5px 10px;
        border-radius: 8px;
        font-weight: 500;
    }

    .car-content {
        padding: 15px;
        text-align: center;
    }

    .car-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .car-name {
        font-size: 1rem;
        font-weight: 600;
        color: #333;
        margin: 0;
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
        color: #2e8b57;
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

    .btn-rezervo {
        background: #1E40AF;
        color: #fff;
        border: none;
        padding: 8px 14px;
        font-size: 0.9rem;
        font-weight: 600;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        transition: all 0.2s ease-in-out;
    }

    .btn-rezervo:hover {
        background: #152c8c;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25);
    }
</style>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row mb-3">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Dashboard Auto Future</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            $allCars = [];
            $newCars = 30;
            $usedCars = 7;
            foreach ($carFiles as $file) {
                $fileName = basename($file);
                if (stripos($fileName, 'LOGO') !== false) continue;

                $relativePath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $file);
                $carName = pathinfo($file, PATHINFO_FILENAME);
                $category = 'standard';
                if (preg_match('/\/([a-zA-Z0-9_]+)\//', $relativePath, $matches)) {
                    $category = strtolower($matches[1]);
                }

                if ($category === 'new') $newCars++;
                elseif ($category === 'used') $usedCars++;

                $carData = [
                    'name' => str_replace('_', ' ', $carName),
                    'image' => $relativePath,
                    'badge' => ucfirst($category),
                    'rating' => rand(3, 10) . '.' . rand(0, 9),
                    'seats' => rand(2, 7),
                    'transmission' => 'Automatic',
                    'type' => 'Sedan',
                    'price' => rand(50, 300),
                    'category' => $category
                ];
                $allCars[] = $carData;
            }
            $totalCars = count($allCars);
            ?>
            <div class="dashboard-cards" style="display:flex; gap:20px; flex-wrap:wrap; margin-bottom:30px;">
                <div class="card total-card" style="flex:1; min-width:150px; padding:20px; border-radius:12px; background:#1E40AF; color:#fff; text-align:center;">
                    <h5>Total Makina</h5>
                    <span class="counter" data-target="<?= $totalCars ?>">0</span>
                </div>
                <div class="card total-card" style="flex:1; min-width:150px; padding:20px; border-radius:12px; background:#10B981; color:#fff; text-align:center;">
                    <h5>Të Reja</h5>
                    <span class="counter" data-target="<?= $newCars ?>">0</span>
                </div>
                <div class="card total-card" style="flex:1; min-width:150px; padding:20px; border-radius:12px; background:#F59E0B; color:#fff; text-align:center;">
                    <h5>Të Përdorura</h5>
                    <span class="counter" data-target="<?= $usedCars ?>">0</span>
                </div>
            </div>

            <!-- === GRAFIKU === -->
            <canvas id="salesChart" style="max-height:400px; margin-bottom:30px;"></canvas>

            <!-- === GRID MAKINASH === -->
            <div class="car-grid">
                <?php foreach ($allCars as $carData):
                    $modalId = "carModal" . md5($carData['image']);
                ?>
                    <div class="car-card">
                        <img src="<?= htmlspecialchars($carData['image']) ?>" alt="<?= htmlspecialchars($carData['name']) ?>" class="car-image" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>">
                        <div class="car-rating">⭐ <?= $carData['rating'] ?></div>
                        <div class="car-content">
                            <h3 class="car-name"><?= htmlspecialchars($carData['name']) ?></h3>
                            <div class="car-specs">
                                <span class="spec">👥 <?= $carData['seats'] ?> vende</span>
                                <span class="spec">⚙️ <?= htmlspecialchars($carData['transmission']) ?></span>
                                <span class="spec">🚗 <?= htmlspecialchars($carData['type']) ?></span>
                            </div>
                            <div class="car-footer">
                                <div class="car-price-clean">
                                    💰 <span class="price-value"><?= $carData['price'] ?>€</span><span class="price-label"> /ditë</span>
                                </div>
                                <a href="#"
                                    class="btn btn-primary btn-rezervo"
                                    data-bs-toggle="modal"
                                    data-bs-target="#addReservationModal">
                                    Rezervo
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- === MODAL Makinës === -->
                    <div class="modal fade" id="<?= $modalId ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-body text-center p-4">
                                    <img src="<?= htmlspecialchars($carData['image']) ?>" class="img-fluid rounded mb-3" style="max-height:400px; object-fit:cover;">
                                    <h5 class="fw-semibold"><?= htmlspecialchars($carData['name']) ?></h5>
                                    <p class="text-muted mb-1"><?= ucfirst($carData['category']) ?> | <?= htmlspecialchars($carData['type']) ?> | <?= htmlspecialchars($carData['transmission']) ?></p>
                                    <p class="fs-5 fw-bold text-success">💰 <?= $carData['price'] ?> €/ditë</p>
                                    <p class="text-muted small">⭐ <?= $carData['rating'] ?> | 💺 <?= $carData['seats'] ?> vende</p>
                                    <hr class="my-3">
                                    <p>Kjo makinë ofron kombinimin perfekt të performancës dhe elegancës, e ndërtuar për drejtim të rehatshëm dhe efikas në çdo terren.</p>
                                    <p>Me teknologji moderne dhe dizajn të sofistikuar, ajo siguron eksperiencë të qetë drejtimi dhe komoditet maksimal për pasagjerët.</p>
                                    <p>Përshtatet në mënyrë të shkëlqyer për udhëtime të gjata, por edhe për përdorim të përditshëm, duke përfaqësuar standardin më të lartë të cilësisë.</p>
                                    <button class="btn btn-secondary mt-3" data-bs-dismiss="modal">Mbyll</button>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            </div>

            <!-- === MODAL ADD RESERVATION (vetëm një herë jashtë foreach) === -->
            <div class="modal fade" id="addReservationModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content p-4">
                        <div class="modal-header">
                            <h5 class="modal-title">Shto Rezervim</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST" action="/new_project/helper/reservations.php" id="reservationForm">
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
                                            data-bs-target="#addClientModal">
                                            + Shto Klient
                                        </button>
                                    </div>
                                </div>
                                <?php
                                $selectedCar = null;
                                if (isset($_GET['car_id'])) {
                                    $carId = (int)$_GET['car_id'];
                                    foreach ($cars as $c) {
                                        if ($c['id'] === $carId) {
                                            $selectedCar = $c;
                                            break;
                                        }
                                    }
                                }
                                ?>

                                <?php if ($selectedCar): ?>
                                    <div class="mb-3">
                                        <label class="form-label">Makina</label>
                                        <input type="text" class="form-control" value="<?= htmlspecialchars($selectedCar['model'] . ' - ' . $selectedCar['vin'] . ' | $' . $selectedCar['price_per_day'] . '/day') ?>" disabled>
                                        <input type="hidden" name="car_id" value="<?= $selectedCar['id'] ?>">
                                    </div>
                                <?php endif; ?>
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

            <!-- === MODAL ADD CLIENT (vetëm një herë jashtë foreach) === -->
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

            <script>
                document.querySelectorAll('[data-bs-target="#addClientModal"]').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const reservationModalEl = document.getElementById('addReservationModal');
                        const reservationModalInstance = bootstrap.Modal.getInstance(reservationModalEl);
                        if (reservationModalInstance) reservationModalInstance.hide();
                    });
                });

                var addClientModalEl = document.getElementById('addClientModal');
                addClientModalEl.addEventListener('hidden.bs.modal', function() {
                    const reservationModal = new bootstrap.Modal(document.getElementById('addReservationModal'));
                    reservationModal.show();
                });

                const ctx = document.getElementById('salesChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        datasets: [{
                            label: 'Shitjet e Makina',
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

                // === COUNTER ===
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
                        } else counter.innerText = target;
                    }
                    updateCounter();
                });

                // === ANIMACIONI CARD ===
                const cards = document.querySelectorAll('.card');
                window.addEventListener('load', () => {
                    cards.forEach((card, index) => {
                        setTimeout(() => card.classList.add('show'), index * 150);
                    });
                });

                // === EDIT BTN SWEETALERT ===
                document.addEventListener("DOMContentLoaded", () => {
                    document.querySelectorAll(".edit-btn").forEach(btn => {
                        btn.addEventListener("click", () => {
                            const oldName = btn.getAttribute("data-old");
                            Swal.fire({
                                title: "Ndrysho emrin e makinës",
                                input: "text",
                                inputLabel: "Emri i ri",
                                inputValue: oldName.replace(/\.[^/.]+$/, ""),
                                showCancelButton: true,
                                confirmButtonText: "Ruaj",
                                cancelButtonText: "Anulo",
                                preConfirm: (newName) => {
                                    if (!newName) Swal.showValidationMessage("Emri nuk mund të jetë bosh");
                                    return fetch("/new_project/helper/renameCar.php", {
                                            method: "POST",
                                            headers: {
                                                "Content-Type": "application/x-www-form-urlencoded"
                                            },
                                            body: "old_name=" + encodeURIComponent(oldName) + "&new_name=" + encodeURIComponent(newName)
                                        }).then(res => res.json())
                                        .catch(() => Swal.showValidationMessage("Gabim gjatë komunikimit me serverin"));
                                }
                            }).then(result => {
                                if (result.isConfirmed) {
                                    if (result.value.success) Swal.fire("Sukses!", "Emri u ndryshua.", "success").then(() => location.reload());
                                    else Swal.fire("Gabim!", result.value.message, "error");
                                }
                            });
                        });
                    });
                });

                console.log(<?= json_encode($allCars, JSON_PRETTY_PRINT) ?>);
            </script>

            <?php include $_SERVER['DOCUMENT_ROOT'] . '/new_project/views/layout/footer.php'; ?>