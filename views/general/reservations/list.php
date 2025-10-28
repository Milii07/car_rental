<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/new_project_bk/index.php';
include_once HELPER_PATH . 'reservations.php';
include_once HELPER_PATH . 'client_helper.php';
include_once LAYOUT_PATH . 'header.php';

$clients_result = $mysqli->query("SELECT id, full_name FROM clients ORDER BY full_name ASC");
$clients = $clients_result->fetch_all(MYSQLI_ASSOC);

$cars = $mysqli->query("
    SELECT c.id, c.model, c.vin, c.price_per_day,
           COALESCE(b.name, '-') AS brand_name,
           COALESCE(cat.name, '-') AS category_name
    FROM cars c
    LEFT JOIN brands b ON c.brand_id = b.id
    LEFT JOIN categories cat ON c.category_id = cat.id
")->fetch_all(MYSQLI_ASSOC);

$reservations_result = $mysqli->query("
    SELECT r.*, c.model, c.vin, c.price_per_day,
           COALESCE(b.name, '-') AS brand_name, 
           COALESCE(cat.name, '-') AS category_name, 
           cl.full_name AS client_name
    FROM reservations r
    JOIN cars c ON r.car_id = c.id
    LEFT JOIN brands b ON c.brand_id = b.id
    LEFT JOIN categories cat ON c.category_id = cat.id
    JOIN clients cl ON r.client_id = cl.id
    ORDER BY r.id DESC
");
$reservations = $reservations_result->fetch_all(MYSQLI_ASSOC);

function getCarStatus($car_id, $mysqli)
{
    $now = new DateTime();

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
        if (empty($r['start_date']) || empty($r['end_date']) || empty($r['time'])) continue;

        $start = new DateTime($r['start_date'] . ' ' . $r['time']);
        $end = new DateTime($r['end_date'] . ' ' . $r['time']);

        if ($now >= $start && $now < $end) {
            return [
                'status' => 'E zënë',
                'client_name' => $r['full_name'],
                'end_date' => $r['end_date']
            ];
        }
    }

    return [
        'status' => 'E lirë',
        'client_name' => null,
        'end_date' => null
    ];
}

function calculateDuration($start_date, $start_time, $end_date, $end_time)
{
    $start = new DateTime($start_date . ' ' . $start_time);
    $end = new DateTime($end_date . ' ' . $end_time);

    if ($end < $start) {
        return ['days' => 0, 'hours' => 0, 'minutes' => 0];
    }

    $diff = $start->diff($end);

    return [
        'days' => $diff->days,
        'hours' => $diff->h,
        'minutes' => $diff->i
    ];
}

function calculateTotalPrice($price_per_day, $duration)
{
    $total_days = max(1, $duration['days']);
    return $total_days * $price_per_day;
}
?>

<style>
    .table tbody tr:nth-child(odd) {
        background-color: #f9f9f9;
    }

    .table tbody tr:nth-child(even) {
        background-color: #ffffff;
    }

    .table tbody tr:hover {
        background-color: #e6f7ff;
        transition: background 0.2s ease-in-out;
    }
</style>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Menaxhimi i Rezervimeve</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="#">Rezervimet</a></li>
                                <li class="breadcrumb-item active">Lista e Rezervimeve</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card p-3 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5>Lista e Rezervimeve</h5>
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addReservationModal">
                        <i class="ri-add-fill"></i> Shto Rezervim
                    </button>
                </div>

                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-success"><?= $_SESSION['message'];
                                                        unset($_SESSION['message']); ?></div>
                <?php endif; ?>
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?= $_SESSION['error'];
                                                    unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <div class="table-responsive shadow rounded">
                    <table class="table table-sm table-bordered table-hover align-middle text-center fs-6">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Klienti</th>
                                <th>Makina</th>
                                <th>Brand</th>
                                <th>Kategoria</th>
                                <th>Data Fillimit</th>
                                <th>Ora</th>
                                <th>Data Mbarimit</th>
                                <th>Kohezgjatja</th>
                                <th>Ditë të mbetura</th>
                                <th>Totali</th>
                                <th>Status</th>
                                <th>Veprime</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reservations as $r):
                                $duration = calculateDuration($r['start_date'], $r['time'], $r['end_date'], $r['time']);

                                $days_reserved = $duration['days'];
                                $hours_reserved = $duration['hours'];
                                $minutes_reserved = $duration['minutes'];

                                $total_price = calculateTotalPrice($r['price_per_day'], $duration);
                                $now = new DateTime();
                                $end = new DateTime($r['end_date'] . ' ' . $r['time']);

                                if ($now < $end) {
                                    $remainingInterval = $now->diff($end);
                                    $remaining_text = "{$remainingInterval->d} ditë, {$remainingInterval->h} orë, {$remainingInterval->i} min";
                                    $is_free = false;
                                } else {
                                    $remaining_text = "0 ditë, 0 orë, 0 min";
                                    $is_free = true;
                                }

                                $carStatus = getCarStatus($r['car_id'], $mysqli);

                                if ($is_free) {
                                    $remainingClass = 'text-success fw-bold';
                                    $status_text = '<span class="text-success fw-bold">E lirë</span>';
                                } else {
                                    $remainingClass = 'text-danger fw-bold';
                                    $status_text = '<span class="text-danger fw-bold">' . $carStatus['status'] . '</span>';
                                }

                            ?>
                                <tr>
                                    <td><?= $r['id'] ?></td>
                                    <td><?= htmlspecialchars($r['client_name']) ?></td>
                                    <td><?= htmlspecialchars($r['model'] . ' (' . $r['vin'] . ')') ?></td>
                                    <td><?= htmlspecialchars($r['brand_name']) ?></td>
                                    <td><?= htmlspecialchars($r['category_name']) ?></td>
                                    <td><?= $r['start_date'] ?></td>
                                    <td><?= $r['time'] ?></td>
                                    <td><?= $r['end_date'] ?></td>
                                    <td><?= $days_reserved ?> ditë <?= $hours_reserved > 0 ? ', ' . $hours_reserved . ' orë' : '' ?></td>
                                    <td class="<?= $remainingClass ?>"><?= $remaining_text ?></td>
                                    <td>$<?= number_format($total_price, 2) ?></td>
                                    <td>
                                        <?= $status_text ?>
                                        <?php if (!empty($carStatus['client_name']) && !$is_free): ?>
                                            (<?= htmlspecialchars($carStatus['client_name']) ?>)
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?= BASE_URL ?>helper/reservations.php?delete=<?= $r['id'] ?>" class="btn btn-sm btn-danger delete-btn">
                                            <i class="ri-delete-bin-6-line"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
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
                                    <select name="car_id" class="form-select" required>
                                        <option value="">Zgjidh makinen</option>
                                        <?php foreach ($cars as $car): ?>
                                            <option value="<?= $car['id'] ?>"><?= htmlspecialchars($car['model'] . ' - ' . $car['vin'] . ' | $' . $car['price_per_day'] . '/day') ?></option>
                                        <?php endforeach; ?>
                                    </select>
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

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let currentReservationModalId = null;
        document.querySelectorAll('.add-client-btn, [data-current-reserve-modal]').forEach(btn => {
            btn.addEventListener('click', function() {
                currentReservationModalId = this.dataset.currentReserveModal || this.getAttribute('data-current-reserve-modal');
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
                                const reserveSelect = document.querySelector('#' + currentReservationModalId + ' select[name="client_id"]');
                                if (reserveSelect) {
                                    const option = new Option(data.client_name, data.client_id, true, true);
                                    reserveSelect.appendChild(option);
                                    reserveSelect.value = data.client_id;
                                }
                                const reserveModal = new bootstrap.Modal(document.getElementById(currentReservationModalId));
                                reserveModal.show();
                            }
                            const addClientModal = bootstrap.Modal.getInstance(document.getElementById('addClientModal'));
                            if (addClientModal) addClientModal.hide();
                            addClientForm.reset();
                            currentReservationModalId = null;
                            setTimeout(() => msgDiv.innerHTML = '', 3000);
                        } else {
                            msgDiv.innerHTML = '<div class="alert alert-danger">' + (data.message || 'Gabim në ruajtjen e klientit') + '</div>';
                        }
                    })
                    .catch(err => {
                        submitBtn.disabled = false;
                        const msgDiv = document.getElementById('clientFormMessage');
                        msgDiv.innerHTML = '<div class="alert alert-danger">Gabim në server</div>';
                        console.error(err);
                    });
            });
        }
    });
</script>

<?php include LAYOUT_PATH . 'footer.php'; ?>