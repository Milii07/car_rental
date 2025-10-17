<div class="modal fade" id="addClientModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content p-4">
            <div class="modal-header">
                <h5 class="modal-title">Shto Klient</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addClientForm" enctype="multipart/form-data">
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
                    <?php renderClientFields(array_slice($clientFields, 2)); ?>
                    <div class="col-12 mb-3">
                        <label class="form-label">Upload Photos</label>
                        <input type="file" class="form-control" name="photos[]" multiple accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="save_client" class="btn btn-success">Save Client</button>
                </div>
            </form>
            <div id="clientFormMessage" class="mt-2"></div>
        </div>
    </div>
</div>

<script>
    document.getElementById('addClientForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const submitBtn = form.querySelector('button[name="save_client"]');
        submitBtn.disabled = true;

        const formData = new FormData(form);

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

                    const addModalEl = document.getElementById('addClientModal');
                    let addModal = bootstrap.Modal.getInstance(addModalEl);

                    if (!addModal) addModal = new bootstrap.Modal(addModalEl);
                    addModal.hide();

                    document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());

                    document.body.classList.remove('modal-open');

                    const tbody = document.querySelector('table tbody');
                    const newRow = document.createElement('tr');
                    newRow.id = 'clientRow' + data.client_id;
                    newRow.innerHTML = `
        <td>${data.client_id}</td>
        <td>${data.full_name}</td>
        <td>${form.company_name?.value || ''}</td>
        <td>${form.email?.value || ''}</td>
        <td>${form.phone?.value || ''}</td>
        <td class="d-flex">
            <button class="btn btn-info btn-sm me-1 view-btn" data-id="${data.client_id}"><i class="ri-eye-fill"></i></button>
            <button class="btn btn-warning btn-sm me-1 edit-btn" data-id="${data.client_id}"><i class="ri-edit-2-fill"></i></button>
            <a href="list.php?delete=${data.client_id}" class="btn btn-danger btn-sm delete-btn"><i class="ri-delete-bin-6-line"></i></a>
        </td>`;
                    tbody.prepend(newRow);

                    form.reset();
                } else {
                    msgDiv.innerHTML = '<div class="alert alert-danger">' + (data.error || 'Gabim ne ruajtjen e klientit') + '</div>';
                }
            })
            .catch(err => {
                submitBtn.disabled = false;
                document.getElementById('clientFormMessage').innerHTML = '<div class="alert alert-danger">Gabim ne server</div>';
                console.error(err);
            });
    });
</script>