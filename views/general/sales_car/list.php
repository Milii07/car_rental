<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once $_SERVER['DOCUMENT_ROOT'] . '/new_project_bk/index.php';
include_once DB_PATH . 'db.php';
include_once HELPER_PATH . 'reservations.php';
include_once HELPER_PATH . 'client_helper.php';
include_once LAYOUT_PATH . 'header.php';

$today = date('Y-m-d');

$cars = $mysqli->query("
    SELECT c.id, c.model, c.vin, c.price_per_day, COALESCE(b.name,'-') AS brand_name,
           COALESCE(cat.name,'-') AS category_name, c.images,
           r.start_date, r.end_date, r.time, r.status as reservation_status, cl.full_name as client_name
    FROM cars c
    JOIN reservations r ON c.id = r.car_id AND r.status != 'cancelled'
    JOIN clients cl ON r.client_id = cl.id
    LEFT JOIN brands b ON c.brand_id = b.id
    LEFT JOIN categories cat ON c.category_id = cat.id
    WHERE r.start_date = '$today' OR r.end_date = '$today'
    ORDER BY r.start_date ASC
")->fetch_all(MYSQLI_ASSOC);

function getCarImages($images)
{
    if (!$images) return [];
    return explode(',', $images);
}

function getDurationAndRemaining($start_date, $end_date, $time = '00:00:00')
{
    $start = new DateTime($start_date . ' ' . $time);
    $end = new DateTime($end_date . ' ' . $time);
    $now = new DateTime();

    $interval = $start->diff($end);
    $days_reserved = $interval->days;
    if ($interval->h > 0 || $interval->i > 0 || $interval->s > 0) {
        $days_reserved += 1;
    }

    if ($now <= $end) {
        $remaining = $now->diff($end);
        $remaining_text = "{$remaining->d} ditë, {$remaining->h} orë, {$remaining->i} min";
    } else {
        $remaining_text = "0 ditë";
    }

    return [
        'days_reserved' => $days_reserved,
        'remaining_text' => $remaining_text
    ];
}
?>
<style>
    .modal-dialog.modal-dialog-centered {
        display: flex !important;
        align-items: flex-start !important;
        margin-top: 50px;
        min-height: calc(100vh - 1rem);
    }
</style>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Makina të Rezervuara Sot</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item">Makina</li>
                                <li class="breadcrumb-item active">Rezervime Sot</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (empty($cars)): ?>
                <div class="alert alert-info">Nuk ka makina të rezervuara për sot.</div>
            <?php endif; ?>

            <div class="row g-3">
                <?php foreach ($cars as $car):
                    $images = getCarImages($car['images']);
                    $main_image = !empty($images) ? $images[0] : 'default-car.png';

                    $duration = getDurationAndRemaining($car['start_date'], $car['end_date'], $car['time']);
                    $days_reserved = $duration['days_reserved'];
                    $remaining_text = $duration['remaining_text'];

                    if ($car['start_date'] == $today) {
                        $today_status = "Do të merret sot";
                        $badge_class = "bg-primary";
                    } elseif ($car['end_date'] == $today) {
                        $today_status = "Do të dorëzohet sot";
                        $badge_class = "bg-success";
                    } else {
                        $today_status = "Rezervuar";
                        $badge_class = "bg-secondary";
                    }
                ?>
                    <div class="col-md-4 col-lg-3">
                        <div class="card h-100 shadow-sm">
                            <img src="<?= UPLOADS_URL ?>cars/<?= htmlspecialchars($main_image) ?>"
                                class="card-img-top" style="height:180px;object-fit:cover;cursor:pointer;"
                                data-bs-toggle="modal"
                                data-bs-target="#viewCarModal<?= $car['id'] ?>">

                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($car['model']) ?></h5>
                                <p class="mb-1"><strong>Brand:</strong> <?= htmlspecialchars($car['brand_name']) ?></p>
                                <p class="mb-1"><strong>Kategori:</strong> <?= htmlspecialchars($car['category_name']) ?></p>
                                <p class="mb-1"><strong>Çmim / ditë:</strong> $<?= number_format($car['price_per_day'], 2) ?></p>
                                <p class="mb-1"><strong>VIN:</strong> <?= htmlspecialchars($car['vin']) ?></p>
                                <p class="mb-3"><strong>Gjendja:</strong>
                                    <span class="badge <?= $badge_class ?>"
                                        data-bs-toggle="tooltip"
                                        data-bs-html="true"
                                        data-bs-title="Klienti: <?= htmlspecialchars($car['client_name']) ?><br>Periudha: <?= $car['start_date'] ?> - <?= $car['end_date'] ?><br>Kohezgjatja: <?= $days_reserved ?> ditë,<br> Afati i dorezimit: <?= $remaining_text ?>">
                                        <?= $today_status ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="viewCarModal<?= $car['id'] ?>" tabindex="-1">
                        <div class="modal-dialog modal-xl modal-dialog-centered">
                            <div class="modal-content p-4">
                                <div class="modal-header">
                                    <h5 class="modal-title"><?= htmlspecialchars($car['model']) ?> - Detaje Makine</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body row">
                                    <div class="col-md-6">
                                        <?php foreach ($images as $img): ?>
                                            <img src="<?= UPLOADS_URL ?>cars/<?= htmlspecialchars($img) ?>" class="img-fluid mb-2 rounded" alt="Foto makine">
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
                                            <p><strong>Gjendja për sot:</strong> <?= $today_status ?></p>
                                            <p><strong>Klienti:</strong> <?= htmlspecialchars($car['client_name']) ?> </p>
                                            <p><strong>Periudha:</strong> <?= $car['start_date'] ?> - <?= $car['end_date'] ?></p>
                                            <p><strong>Kohezgjatja:</strong> <?= $days_reserved ?> ditë </p>
                                            <p><strong>Afati i dorezimit: </strong><?= $remaining_text ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary" data-bs-dismiss="modal">Mbyll</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>

<?php include LAYOUT_PATH . 'footer.php'; ?>