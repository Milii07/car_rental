<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/../../../db/db.php';
require $_SERVER['DOCUMENT_ROOT'] . '/new_project_bk/helper/home.php';

include_once $_SERVER['DOCUMENT_ROOT'] . '/new_project_bk/views/layout/header.php';

// Fushat e makinës
$carFields = ['vin', 'model', 'year', 'body_type', 'color', 'fuel_type', 'transmission', 'odometer', 'license_plate', 'seating_capacity'];
$ownerFields = ['owner_name', 'dob', 'address', 'phone', 'email', 'license_number', 'tax_id'];
$insuranceFields = ['insurance_provider', 'policy_number', 'coverage_type'];
$financialFields = ['registration_fee', 'road_tax', 'sales_tax', 'payment_method', 'price_per_day'];
$dealerFields = ['dealer_info', 'special_plate'];

// Merr brands dhe categories
$brands = $mysqli->query("SELECT * FROM brands ORDER BY name ASC")->fetch_all(MYSQLI_ASSOC);
$categories = $mysqli->query("SELECT * FROM categories ORDER BY name ASC")->fetch_all(MYSQLI_ASSOC);

// Merr makinat
$result = $mysqli->query("SELECT * FROM cars ORDER BY id DESC");

// Funksion për form fields
function renderFields($fields, $data = [])
{
    foreach ($fields as $field) {
        $label = ucwords(str_replace('_', ' ', $field));
        $value = $data[$field] ?? '';
        $type = in_array($field, ['registration_fee', 'road_tax', 'sales_tax', 'price_per_day']) ? 'number' : 'text';
        $step = $type === 'number' ? 'step="0.01" min="0"' : '';
        echo '<div class="col-md-3 mb-3">
                <label class="form-label">' . $label . '</label>
                <input type="' . $type . '" class="form-control" name="' . $field . '" value="' . htmlspecialchars($value) . '" ' . $step . ' required>
              </div>';
    }
}
?>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Cars</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="#">Cars</a></li>
                                <li class="breadcrumb-item active">Lista e Makina</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card p-3 shadow-sm mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Lista e Makina</h5>
                            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addCarModal">
                                <i class="ri-add-fill"></i> Shto Makine
                            </button>
                        </div>

                        <?php if (isset($_SESSION['message'])) : ?>
                            <div class="alert alert-success"><?= $_SESSION['message'];
                                                                unset($_SESSION['message']); ?></div>
                        <?php endif; ?>
                        <?php if (isset($_SESSION['error'])) : ?>
                            <div class="alert alert-danger"><?= $_SESSION['error'];
                                                            unset($_SESSION['error']); ?></div>
                        <?php endif; ?>

                        <div class="table-responsive shadow rounded">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Model</th>
                                        <th>Brand</th>
                                        <th>Kategoria</th>
                                        <th>Owner</th>
                                        <th>Insurance</th>
                                        <th>Financials</th>
                                        <th>Dealer</th>
                                        <th>Images</th>
                                        <th>Veprime</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $result->fetch_assoc()) : ?>
                                        <tr>
                                            <td><?= $row['id'] ?></td>
                                            <td><?= htmlspecialchars($row['model']) ?></td>
                                            <td><?= htmlspecialchars($row['brand_id'] ? $mysqli->query("SELECT name FROM brands WHERE id=" . $row['brand_id'])->fetch_assoc()['name'] : '-') ?></td>
                                            <td><?= htmlspecialchars($row['category_id'] ? $mysqli->query("SELECT name FROM categories WHERE id=" . $row['category_id'])->fetch_assoc()['name'] : '-') ?></td>
                                            <td><?= htmlspecialchars($row['owner_name']) ?></td>
                                            <td><?= htmlspecialchars($row['policy_number']) ?></td>
                                            <td><?= htmlspecialchars($row['registration_fee']) ?></td>
                                            <td><?= htmlspecialchars($row['dealer_info']) ?></td>
                                            <td>
                                                <?php if (!empty($row['images'])) :
                                                    foreach (explode(',', $row['images']) as $img) :
                                                        echo "<img src='/new_project_bk/uploads/cars/" . htmlspecialchars($img) . "' width='50' class='me-1 mb-1'>";
                                                    endforeach;
                                                endif; ?>
                                            </td>
                                            <td class="d-flex">
                                                <button class="btn btn-info btn-sm me-1" data-bs-toggle="modal" data-bs-target="#viewCarModal<?= $row['id'] ?>"><i class="ri-eye-fill"></i></button>
                                                <button class="btn btn-warning btn-sm me-1" data-bs-toggle="modal" data-bs-target="#editCarModal<?= $row['id'] ?>"><i class="ri-edit-2-fill"></i></button>
                                                <a href="/new_project_bk/helper/cars.php?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm delete-btn"><i class="ri-delete-bin-6-line"></i></a>
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="viewCarModal<?= $row['id'] ?>" tabindex="-1">
                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                <div class="modal-content border-0 shadow-lg rounded-4">

                                                    <!-- HEADER -->
                                                    <div class="modal-header  text-white rounded-top-4">
                                                        <h5 class="modal-title d-flex text-primary align-items-center">
                                                            <i class="ri-car-line me-2 fs-5"></i> Detajet e Makinës – ID <?= $row['id'] ?>
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>

                                                    <!-- BODY -->
                                                    <div class="modal-body px-4 py-3">

                                                        <!-- FOTOT (të vogla) -->
                                                        <?php if (!empty($row['images'])) : ?>
                                                            <div class="d-flex flex-wrap justify-content-center gap-2 mb-3">
                                                                <?php foreach (explode(',', $row['images']) as $img) : ?>
                                                                    <div class="border rounded-3 overflow-hidden">
                                                                        <img src="/new_project_bk/uploads/cars/<?= htmlspecialchars($img) ?>"
                                                                            class="img-fluid"
                                                                            style="width:390px; height:260px; object-fit:cover;"
                                                                            alt="Foto makine">
                                                                    </div>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        <?php else : ?>
                                                            <p class="text-center text-muted mb-3">Nuk ka foto për këtë makinë.</p>
                                                        <?php endif; ?>

                                                        <!-- INFORMACIONI -->
                                                        <div class="container">
                                                            <div class="row gy-2">

                                                                <?php
                                                                $brand = htmlspecialchars($row['brand_id'] ? $mysqli->query("SELECT name FROM brands WHERE id=" . $row['brand_id'])->fetch_assoc()['name'] : '-');
                                                                $category = htmlspecialchars($row['category_id'] ? $mysqli->query("SELECT name FROM categories WHERE id=" . $row['category_id'])->fetch_assoc()['name'] : '-');

                                                                echo '<div class="col-md-6"><strong>Brand:</strong> ' . $brand . '</div>';
                                                                echo '<div class="col-md-6"><strong>Kategori:</strong> ' . $category . '</div>';

                                                                $allFields = array_merge($carFields, $ownerFields, $insuranceFields, $financialFields, $dealerFields);
                                                                foreach ($allFields as $field) :
                                                                    $label = ucfirst(str_replace('_', ' ', $field));
                                                                    $value = htmlspecialchars($row[$field] ?? '-');
                                                                    if (!empty($value) && $value !== '-') :
                                                                        echo '<div class="col-md-6"><strong>' . $label . ':</strong> ' . $value . '</div>';
                                                                    endif;
                                                                endforeach;
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- FOOTER -->
                                                    <div class="modal-footer border-0 justify-content-center pb-3">
                                                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                                                            Mbyll
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>










                                        <div class="modal fade" id="editCarModal<?= $row['id'] ?>" tabindex="-1">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content p-4">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Car - ID <?= $row['id'] ?></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form method="POST" action="/new_project_bk/helper/cars.php" enctype="multipart/form-data">
                                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                        <div class="modal-body row">
                                                            <?php renderFields(array_merge($carFields, $ownerFields, $insuranceFields, $financialFields, $dealerFields), $row); ?>
                                                            <div class="col-md-3 mb-3">
                                                                <label class="form-label">Brand</label>
                                                                <select name="brand_id" class="form-select" required>
                                                                    <option value="">Zgjidh brandin</option>
                                                                    <?php foreach ($brands as $b) : ?>
                                                                        <option value="<?= $b['id'] ?>" <?= $b['id'] == $row['brand_id'] ? 'selected' : '' ?>><?= htmlspecialchars($b['name']) ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3 mb-3">
                                                                <label class="form-label">Kategori</label>
                                                                <select name="category_id" class="form-select" required>
                                                                    <option value="">Zgjidh kategorinë</option>
                                                                    <?php foreach ($categories as $c) : ?>
                                                                        <option value="<?= $c['id'] ?>" <?= $c['id'] == $row['category_id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-12 mb-3">
                                                                <label class="form-label">Upload Images</label>
                                                                <input type="file" class="form-control" name="images[]" multiple accept="image/*">
                                                                <?php if (!empty($row['images'])) : ?>
                                                                    <div class="mt-2">
                                                                        <?php foreach (explode(',', $row['images']) as $img) : ?>
                                                                            <img src="/new_project_bk/uploads/cars/Makina\Camera Roll/<?= htmlspecialchars($img) ?>" width="50" class="me-1 mb-1">
                                                                        <?php endforeach; ?>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" name="update" class="btn btn-primary">Ruaj Ndryshimet</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="addCarModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content p-4">
            <div class="modal-header">
                <h5 class="modal-title">Shto Makine</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/new_project_bk/helper/cars.php" enctype="multipart/form-data">
                <div class="modal-body row">
                    <?php renderFields(array_merge($carFields, $ownerFields, $insuranceFields, $financialFields, $dealerFields)); ?>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Brand</label>
                        <select name="brand_id" class="form-select" required>
                            <option value="">Zgjidh brandin</option>
                            <?php foreach ($brands as $b) : ?>
                                <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Zgjidh kategorinë</option>
                            <?php foreach ($categories as $c) : ?>
                                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Upload Images</label>
                        <input type="file" class="form-control" name="images[]" multiple accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="create" class="btn btn-success">Shto Makine</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- JS -->
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
                cancelButtonColor: '#d33',
                confirmButtonText: 'Po, fshi!',
                cancelButtonText: 'Anulo'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = deleteUrl;
                }
            });
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php include __DIR__ . '/../../../views/layout/footer.php'; ?>