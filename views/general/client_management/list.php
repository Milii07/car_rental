<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once $_SERVER['DOCUMENT_ROOT'] . '/new_project_bk/index.php';

include_once HELPER_PATH . 'client_helper.php';
include_once LAYOUT_PATH . 'header.php';


$result = $mysqli->query("SELECT * FROM clients ORDER BY id DESC");


$clientFields = [
    'profile_type',
    'partner_type',
    'full_name',
    'company_name',
    'nipt',
    'email',
    'phone',
    'birthday',
    'country',
    'city',
    'zip',
    'reference',
    'address',
    'payment_terms',
    'remarks'
];

function renderClientFields($fields, $data = [])
{
    foreach ($fields as $field) {
        $label = ucwords(str_replace('_', ' ', $field));
        $value = $data[$field] ?? '';
        echo '<div class="col-md-3 mb-3">
                <label class="form-label">' . $label . '</label>
                <input type="text" class="form-control" name="' . $field . '" value="' . htmlspecialchars($value) . '">
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
                        <h4 class="mb-sm-0">Menaxhimi i Klientëve</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="#">Klientët</a></li>
                                <li class="breadcrumb-item active">Lista e Klientëve</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    <div class="card p-3 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Lista e Klientëve</h5>
                            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addClientModal">
                                <i class="ri-add-fill"></i> Shto Klient
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
                            <table class="table table-bordered table-hover align-middle" style="font-size:0.85rem;">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Full Name</th>
                                        <th>Company</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Veprime</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($client = $result->fetch_assoc()) : ?>
                                        <tr>
                                            <td><?= $client['id'] ?></td>
                                            <td><?= htmlspecialchars($client['full_name']) ?></td>
                                            <td><?= htmlspecialchars($client['company_name']) ?></td>
                                            <td><?= htmlspecialchars($client['email']) ?></td>
                                            <td><?= htmlspecialchars($client['phone']) ?></td>
                                            <td class="d-flex">
                                                <button class="btn btn-info btn-sm me-1" data-bs-toggle="modal" data-bs-target="#viewClientModal<?= $client['id'] ?>"><i class="ri-eye-fill"></i></button>
                                                <button class="btn btn-warning btn-sm me-1" data-bs-toggle="modal" data-bs-target="#editClientModal<?= $client['id'] ?>"><i class="ri-edit-2-fill"></i></button>
                                                <a href="list.php?delete=<?= $client['id'] ?>" class="btn btn-danger btn-sm delete-btn"><i class="ri-delete-bin-6-line"></i></a>
                                            </td>
                                        </tr>


                                        <div class="modal fade" id="viewClientModal<?= $client['id'] ?>" tabindex="-1">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content p-4">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title fw-bold text-primary mb-3">Detajet e Klientit - ID <?= $client['id'] ?></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body row">
                                                        <?php
                                                        foreach ($clientFields as $field) {
                                                            $label = ucwords(str_replace('_', ' ', $field));
                                                            $value = $client[$field] ?? '';
                                                            echo '<div class="col-md-3 mb-3">
                                                                    <label class="form-label fw-bold">' . $label . ':</label>
                                                                    <p class="form-control-plaintext">' . htmlspecialchars($value) . '</p>
                                                                  </div>';
                                                        }
                                                        if (!empty($client['photos'])) {
                                                            echo '<div class="col-12 mb-3"><label class="form-label fw-bold">Photos:</label><div>';
                                                            foreach (explode(',', $client['photos']) as $photo) {
                                                                echo '<img src="/new_project_bk/uploads/clients/' . htmlspecialchars($photo) . '" width="80" class="me-1 mb-1">';
                                                            }
                                                            echo '</div></div>';
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mbyll</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="modal fade" id="editClientModal<?= $client['id'] ?>" tabindex="-1">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content p-4">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Client - ID <?= $client['id'] ?></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form method="POST" enctype="multipart/form-data">
                                                        <input type="hidden" name="id" value="<?= $client['id'] ?>">
                                                        <div class="modal-body row">
                                                            <?php renderClientFields($clientFields, $client); ?>
                                                            <div class="col-12 mb-3">
                                                                <label class="form-label">Upload Photos</label>
                                                                <input type="file" class="form-control" name="photos[]" multiple accept="image/*">
                                                                <?php if (!empty($client['photos'])) : ?>
                                                                    <div class="mt-2">
                                                                        <?php foreach (explode(',', $client['photos']) as $photo) : ?>
                                                                            <img src="/new_project_bk/uploads/clients/<?= htmlspecialchars($photo) ?>" width="50" class="me-1 mb-1">
                                                                        <?php endforeach; ?>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" name="update_client" class="btn btn-primary">Save Changes</button>
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
<?php include $_SERVER['DOCUMENT_ROOT'] . '/new_project_bk/views/general/client_management/add_client_modal.php'; ?>

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
<?php include $_SERVER['DOCUMENT_ROOT'] . '/new_project_bk/views/layout/footer.php'; ?>