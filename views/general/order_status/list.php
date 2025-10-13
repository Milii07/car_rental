<?php
if (session_status() === PHP_SESSION_NONE) session_start();

include $_SERVER['DOCUMENT_ROOT'] . '/new_project_bk/db/db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/new_project_bk/helper/reservations.php';
include $_SERVER['DOCUMENT_ROOT'] . '/new_project_bk/helper/client_helper.php';
include $_SERVER['DOCUMENT_ROOT'] . '/new_project_bk/views/layout/header.php';

$clients_result = $mysqli->query("SELECT id, full_name FROM clients ORDER BY full_name ASC");
$clients = $clients_result->fetch_all(MYSQLI_ASSOC);

$cars = $mysqli->query("
    SELECT c.id, c.model, c.vin, c.price_per_day, COALESCE(b.name,'-') AS brand_name,
           COALESCE(cat.name,'-') AS category_name, c.images
    FROM cars c
    LEFT JOIN brands b ON c.brand_id = b.id
    LEFT JOIN categories cat ON c.category_id = cat.id
")->fetch_all(MYSQLI_ASSOC);

function getCarStatus($car_id, $mysqli)
{
    $today = date('Y-m-d');
    $stmt = $mysqli->prepare("
        SELECT r.*, cl.full_name 
        FROM reservations r
        JOIN clients cl ON r.client_id = cl.id
        WHERE r.car_id=? AND r.status!='cancelled'
        ORDER BY r.start_date ASC
    ");
    $stmt->bind_param("i", $car_id);
    $stmt->execute();
    $reservations = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    foreach ($reservations as $r) {
        if (!empty($r['end_date']) && $r['end_date'] != '0000-00-00' && $today >= $r['start_date'] && $today <= $r['end_date']) {
            return [
                'status' => 'E zënë',
                'client_name' => $r['full_name'],
                'start_date' => $r['start_date'],
                'end_date' => $r['end_date'],
                'time' => $r['time']
            ];
        }
    }
    return [
        'status' => 'E lirë',
        'client_name' => null,
        'start_date' => null,
        'end_date' => null,
        'time' => null
    ];
}

function getCarImages($images)
{
    if (!$images) return [];
    return explode(',', $images);
}
?>

<style>
    .modal-dialog-centered {
        display: flex !important;
        align-items: center !important;
        min-height: calc(100vh - 1rem);
    }

    .modal {
        position: fixed !important;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1055 !important;
        overflow: auto;
    }

    .modal.show {
        display: flex !important;
    }

    .modal-dialog {
        margin: auto;
        transform: translateY(0);
        transition: all 0.25s ease-in-out;
    }

    .modal-content {
        z-index: 1060 !important;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
        background: #fff;
        pointer-events: auto;
    }

    .modal.modal-second {
        z-index: 1075 !important;
    }

    .modal.modal-second .modal-content {
        pointer-events: auto !important;
    }

    .modal-backdrop.modal-backdrop-second {
        z-index: 1070 !important;
    }

    .modal-backdrop {
        background-color: rgba(0, 0, 0, 0.5) !important;
        z-index: 1050 !important;
        opacity: 1 !important;
    }

    body.modal-open {
        overflow: hidden !important;
    }

    .tooltip-inner .remaining-days {
        color: red;
        font-weight: bold;
    }

    .custom-modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1040;
        display: none;
    }
</style>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Gjendja e Makina</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="#">Makina</a></li>
                                <li class="breadcrumb-item active">Gjendja</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (isset($_SESSION['message'])): ?>
                <div id="alertMessage" class="alert alert-success"><?= $_SESSION['message'];
                                                                    unset($_SESSION['message']); ?></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <div id="alertMessage" class="alert alert-danger"><?= $_SESSION['error'];
                                                                    unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <div class="row g-3">
                <?php foreach ($cars as $car):
                    $status_info = getCarStatus($car['id'], $mysqli);
                    $images = getCarImages($car['images']);
                    $main_image = !empty($images) ? $images[0] : 'default-car.png';

                    if ($status_info['status'] == 'E zënë') {
                        $start = new DateTime($status_info['start_date']);
                        $end = new DateTime($status_info['end_date']);
                        $days_reserved = $start->diff($end)->days + 1;
                    }
                ?>
                    <div class="col-md-4 col-lg-3">
                        <div class="card h-100 shadow-sm">

                            <img src="/new_project_bk/uploads/cars/<?= htmlspecialchars($main_image) ?>"
                                class="card-img-top"
                                style="height:180px;object-fit:cover;cursor:pointer;"
                                data-bs-toggle="modal"
                                data-bs-target="#viewCarModal<?= $car['id'] ?>">

                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($car['model']) ?></h5>
                                <p class="mb-1"><strong>Brand:</strong> <?= htmlspecialchars($car['brand_name']) ?></p>
                                <p class="mb-1"><strong>Kategori:</strong> <?= htmlspecialchars($car['category_name']) ?></p>
                                <p class="mb-1"><strong>Çmim / ditë:</strong> $<?= number_format($car['price_per_day'], 2) ?></p>
                                <p class="mb-3"><strong>Gjendja:</strong>
                                    <?php if ($status_info['status'] == 'E zënë'): ?>
                                        <span class="badge bg-danger"
                                            data-bs-toggle="tooltip"
                                            data-bs-title="<?= $status_info['start_date'] ?> - <?= $status_info['end_date'] ?>">
                                            <?= htmlspecialchars($status_info['client_name']) ?> (<?= $days_reserved ?> ditë)
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-success">E lirë</span>
                                    <?php endif; ?>
                                </p>

                                <div class="d-flex justify-content-end">
                                    <?php if ($status_info['status'] == 'E lirë'): ?>
                                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#reserveCarModal<?= $car['id'] ?>">Rezervo</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="viewCarModal<?= $car['id'] ?>" tabindex="-1">
                        <div class="modal-dialog modal-xl modal-dialog-centered">
                            <div class="modal-content p-4">
                                <div class="modal-header">
                                    <h5 class="modal-title fw-bold text-primary mb-3">
                                        <?= htmlspecialchars($car['model']) ?> - Detajet e Makinës
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body row">
                                    <div class="col-md-6">
                                        <?php foreach ($images as $img): ?>
                                            <img src="/new_project_bk/uploads/cars/<?= htmlspecialchars($img) ?>" class="img-fluid mb-2 rounded">
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card p-3 mb-3">
                                            <h5 class="mb-3">Informacion i Përgjithshëm</h5>
                                            <p><strong>Brand:</strong> <?= htmlspecialchars($car['brand_name']) ?></p>
                                            <p><strong>Kategori:</strong> <?= htmlspecialchars($car['category_name']) ?></p>
                                            <p><strong>Model:</strong> <?= htmlspecialchars($car['model']) ?></p>
                                            <p><strong>VIN:</strong> <?= htmlspecialchars($car['vin']) ?></p>
                                            <p><strong>Çmim / ditë:</strong> $<?= number_format($car['price_per_day'], 2) ?></p>
                                            <p><strong>Gjendja:</strong> <?= $status_info['status'] ?></p>
                                            <?php if ($status_info['status'] == 'E zënë'): ?>
                                                <p><strong>Klienti:</strong> <?= htmlspecialchars($status_info['client_name']) ?> (<?= $days_reserved ?> ditë)</p>
                                                <p><strong>Periudha:</strong> <?= $status_info['start_date'] ?> - <?= $status_info['end_date'] ?></p>
                                            <?php endif; ?>
                                            <h5 class="mt-4 mb-2">Përshkrimi i makinës</h5>
                                            <p>Udhëtim i jashtëzakonshëm, rehati maksimale
                                                Hyni në botën e luksit dhe rehatisë me këtë makinë të shkëlqyer. Çdo udhëtim shndërrohet në një eksperiencë të qetë dhe të këndshme. Udhëtimet në qytet apo në rrugë të largëta bëhen të sigurta dhe të këndshme.

                                            <p> Performancë dhe stil që tërheq vëmendjen
                                                Me motor të fuqishëm dhe stabilitet të lartë, kjo makinë ofron performancë të shkëlqyer në çdo rrugë. Linjat elegante dhe dizajni modern e bëjnë makinën një zgjedhje perfekte për çdo rast.

                                            <p>Siguri dhe besueshmëri në çdo kilometër
                                                Pajisjet më të fundit të sigurisë dhe teknologjia e avancuar garanton një udhëtim pa shqetësime. Zgjidhni këtë makinë për një eksperiencë të paharrueshme dhe udhëtime të sigurt për ju dhe pasagjerët tuaj.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary" data-bs-dismiss="modal">Mbyll</button>
                                </div>
                            </div>
                        </div>
                    </div>



                    <?php if ($status_info['status'] == 'E lirë'): ?>
                        <div class="modal fade" id="reserveCarModal<?= $car['id'] ?>" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content p-4">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Rezervo <?= htmlspecialchars($car['model']) ?></h5>
                                        <button class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="/new_project_bk/helper/reservations.php">
                                        <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Klienti</label>
                                                <div class="input-group">
                                                    <select name="client_id" class="form-select reserve-client-select" required>
                                                        <option value="">Zgjidh klientin</option>
                                                        <?php foreach ($clients as $cl): ?>
                                                            <option value="<?= $cl['id'] ?>"><?= htmlspecialchars($cl['full_name']) ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <button type="button" class="btn btn-outline-primary add-client-btn"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#addClientModal"
                                                        data-current-reserve-modal="reserveCarModal<?= $car['id'] ?>">
                                                        + Shto Klient
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col mb-3">
                                                    <label class="form-label">Data e fillimit</label>
                                                    <input type="date" name="start_date" class="form-control" required>
                                                </div>
                                                <div class="col mb-3">
                                                    <label class="form-label">Ora e fillimit</label>
                                                    <input type="time" name="time" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Data e mbarimit</label>
                                                <input type="date" name="end_date" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" name="create" class="btn btn-success">Rezervo Makinen</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                <?php endforeach; ?>
            </div>
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


<div id="customBackdrop" class="custom-modal-backdrop"></div>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        document.querySelectorAll('.badge.bg-danger[data-bs-toggle="tooltip"]').forEach(function(el) {
            const originalTitle = el.getAttribute('data-bs-title');
            if (!originalTitle) return;

            let parts = originalTitle.split(' - ');
            let startDate = parts[0] || '';
            let endDate = parts[1] || '';

            let today = new Date();
            let end = new Date(endDate);
            let remainingDays = Math.ceil((end - today) / (1000 * 60 * 60 * 24)) + 1;
            remainingDays = remainingDays < 0 ? 0 : remainingDays;

            let clientName = el.textContent.split(' ')[0];

            const tooltipContent = `
        <div><strong>${clientName}</strong></div>
        <div>${startDate} - ${endDate}</div>
        <div class="remaining-days">Ditë të mbetura: ${remainingDays}</div>
    `;

            new bootstrap.Tooltip(el, {
                title: tooltipContent,
                html: true,
                placement: 'top'
            });
        });


        setTimeout(() => {
            const alert = document.getElementById('alertMessage');
            if (alert) alert.remove();
        }, 5000);

        const backdrop = document.getElementById('customBackdrop');
        const modals = document.querySelectorAll('.modal');

        modals.forEach(modal => {
            modal.addEventListener('show.bs.modal', function() {
                backdrop.style.display = 'block';
            });

            modal.addEventListener('hidden.bs.modal', function() {
                backdrop.style.display = 'none';
            });
        });

        let currentReservationModalId = null;

        document.querySelectorAll('.add-client-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                currentReservationModalId = this.dataset.currentReserveModal;
            });
        });

        const addClientForm = document.getElementById('addClientForm');
        if (addClientForm) {
            addClientForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.disabled = true;

                fetch('/new_project_bk/helper/save_client_ajax.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        submitBtn.disabled = false;
                        const msgDiv = document.getElementById('clientFormMessage');

                        if (data.success) {
                            msgDiv.innerHTML = '<div class="alert alert-success">Klienti u ruajt me sukses!</div>';

                            if (currentReservationModalId) {
                                const reserveSelect = document.querySelector('#' + currentReservationModalId + ' select.reserve-client-select');
                                if (reserveSelect) {
                                    const option = new Option(data.client_name, data.client_id, true, true);
                                    reserveSelect.appendChild(option);
                                    reserveSelect.value = data.client_id;
                                }
                                const reserveModal = new bootstrap.Modal(document.getElementById(currentReservationModalId));
                                reserveModal.show();
                            }

                            const addClientModal = bootstrap.Modal.getInstance(document.getElementById('addClientModal'));
                            addClientModal.hide();
                            addClientForm.reset();
                            currentReservationModalId = null;
                            setTimeout(() => msgDiv.innerHTML = '', 3000);
                        } else {
                            msgDiv.innerHTML = '<div class="alert alert-danger">' + (data.message || 'Gabim ne ruajtjen e klientit') + '</div>';
                        }
                    })
                    .catch(err => {
                        submitBtn.disabled = false;
                        document.getElementById('clientFormMessage').innerHTML = '<div class="alert alert-danger">Gabim ne server</div>';
                        console.error(err);
                    });
            });
        }

    });
</script>



<?php include $_SERVER['DOCUMENT_ROOT'] . '/new_project_bk/views/layout/footer.php'; ?>