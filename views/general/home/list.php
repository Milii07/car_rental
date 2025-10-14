<?php
include_once __DIR__ . '/../../../index.php';


include $_SERVER['DOCUMENT_ROOT'] . '/new_project_bk/db/db.php';

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
                                    <p>Komfort dhe luks:
                                        Ky model makine ofron një eksperiencë të jashtëzakonshme udhëtimi. Sediljet janë të rehatshme dhe të rregullueshme sipas preferencave, ndërsa materiali i brendshëm dhe dizajni modern krijojnë një ambient luksoz për çdo pasagjer. Sistemet e klimës dhe izolimi i zhurmës e bëjnë udhëtimin qetësisht dhe komod.</p>
                                    <p>Performancë dhe teknologji:
                                        Me një motor të fuqishëm dhe efikas, kjo makinë kombinon performancën e lartë me ekonominë e karburantit. Pajisjet teknologjike, përfshirë navigacionin, sistemin e ndihmës për parkim dhe asistencën e vozitjes, garantojnë një eksperiencë të sigurt dhe të avancuar për shoferin.</p>
                                    <p>Siguri dhe besueshmëri:
                                        Siguria është prioriteti kryesor. Pajisjet moderne të sigurisë, si airbag-et, ABS, kontrolli i stabilitetit dhe sistemi i paralajmërimit për rrezik, sigurojnë mbrojtje maksimale për shoferin dhe pasagjerët. Kjo makinë nuk është vetëm një mjet transporti, por një shoqëruese e besueshme në çdo rrugë.</p>

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


            <?php include $_SERVER['DOCUMENT_ROOT'] . '/new_project_bk/views/layout/footer.php'; ?>