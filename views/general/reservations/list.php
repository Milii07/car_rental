<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include $_SERVER['DOCUMENT_ROOT'] . '/new_project_bk/db/db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/new_project_bk/helper/reservations.php';
include $_SERVER['DOCUMENT_ROOT'] . '/new_project_bk/helper/client_helper.php';
include $_SERVER['DOCUMENT_ROOT'] . '/new_project_bk/views/layout/header.php';

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
    SELECT r.*, c.model, c.vin, 
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
    $today = date('Y-m-d H:i:s');
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
        if ($today >= $r['start_date'] && $today <= $r['end_date']) {
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
?>

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
                                $start = new DateTime($r['start_date']);
                                $end = new DateTime($r['end_date']);
                                $today = new DateTime();
                                $days_reserved = $start->diff($end)->days + 1;
                                $remaining_days = ($today <= $end) ? $today->diff($end)->days : 0;
                                $carStatus = getCarStatus($r['car_id'], $mysqli);
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
                                    <td><?= $days_reserved ?> ditë</td>
                                    <td><?= $remaining_days ?> ditë</td>
                                    <td>$<?= number_format($r['total_price'], 2) ?></td>
                                    <td>
                                        <?= $carStatus['status'] ?>
                                        <?php if ($carStatus['client_name']) echo ' (' . htmlspecialchars($carStatus['client_name']) . ')'; ?>
                                    </td>
                                    <td>
                                        <a href="/new_project_bk/helper/reservations.php?delete=<?= $r['id'] ?>" class="btn btn-sm btn-danger delete-btn">
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
                        <form method="POST" action="/new_project_bk/helper/reservations.php" id="reservationForm">
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            let deleteUrl = this.href;
            Swal.fire({
                title: 'Jeni i sigurt?',
                text: "Kjo nuk mund të anulohet!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: 'rgba(226, 40, 40, 1)',
                confirmButtonText: 'Po, fshi!',
                cancelButtonText: 'Anulo'
            }).then(result => {
                if (result.isConfirmed) {
                    window.location.href = deleteUrl;
                }
            });
        });
    });

    setTimeout(function() {
        const alert = document.getElementById('alertMessage');
        if (alert) alert.remove();
    }, 5000);

    var currentReservationModalId = null;
    document.querySelectorAll('[data-current-reserve-modal]').forEach(btn => {
        btn.addEventListener('click', function() {
            currentReservationModalId = this.getAttribute('data-current-reserve-modal');
        });
    });

    document.getElementById('addClientForm').addEventListener('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        var submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;

        fetch('/new_project_bk/helper/save_client_ajax.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                submitBtn.disabled = false;
                var msgDiv = document.getElementById('clientFormMessage');

                if (data.success) {
                    msgDiv.innerHTML = '<div class="alert alert-success">Klienti u ruajt me sukses!</div>';

                    if (currentReservationModalId) {
                        var reserveSelect = document.querySelector('#' + currentReservationModalId + ' select[name="client_id"]');
                        if (reserveSelect) {
                            var option = new Option(data.client_name, data.client_id, true, true);
                            reserveSelect.appendChild(option);
                            reserveSelect.value = data.client_id;
                        }
                        var reserveModal = new bootstrap.Modal(document.getElementById(currentReservationModalId));
                        reserveModal.show();
                    }

                    var addClientModal = bootstrap.Modal.getInstance(document.getElementById('addClientModal'));
                    addClientModal.hide();
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
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/new_project_bk/views/layout/footer.php'; ?>