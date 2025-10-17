<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include $_SERVER['DOCUMENT_ROOT'] . '/new_project_bk/index.php';
include HELPER_PATH . 'brand.php';
include LAYOUT_PATH . 'header.php';
?>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row mb-3">
                <div class="col-12 d-flex justify-content-between align-items-center">
                    <h4>Menaxhimi i Brand-eve</h4>
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="ri-add-fill"></i> Shto Brand të Ri
                    </button>
                </div>
            </div>

            <?php if (isset($_SESSION['message'])) : ?>
                <div class="alert alert-success"><?= $_SESSION['message'];
                                                    unset($_SESSION['message']); ?></div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])) : ?>
                <div class="alert alert-danger"><?= $_SESSION['error'];
                                                unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <div class="card p-3 shadow-sm">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Emri</th>
                                <th>Created At</th>
                                <th>Veprime</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()) : ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td><?= $row['created_at'] ?></td>
                                    <td class="d-flex">

                                        <button class="btn btn-warning btn-sm me-1" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>">
                                            <i class="ri-edit-2-fill"></i>
                                        </button>

                                        <a href="list.php?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm delete-btn">
                                            <i class="ri-delete-bin-6-line"></i>
                                        </a>

                                        <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content p-4">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Brand</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form method="POST">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($row['name']) ?>" required>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" name="update" class="btn btn-primary w-30">Ruaj</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content p-4">
            <div class="modal-header">
                <h5 class="modal-title">Shto Brand të Ri</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="text" name="name" class="form-control" placeholder="Emri i brand-it" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="create" class="btn btn-success w-30">Shto</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="/new_project_bk/public/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                let deleteUrl = btn.href;

                Swal.fire({
                    title: 'Jeni i sigurt?',
                    text: "Kjo nuk mund të anulohet!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Po, fshi!',
                    cancelButtonText: 'Anulo'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = deleteUrl;
                    }
                });
            });
        });
    });
</script>

<?php include LAYOUT_PATH . 'footer.php'; ?>