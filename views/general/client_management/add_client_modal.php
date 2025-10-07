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
        console.log("sss");
        var form = this;
        var formData = new FormData(form);
        var submitBtn = form.querySelector('button[name="save_client"]');
        submitBtn.disabled = true;

        fetch('/new_project/helper/save_client_ajax.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                submitBtn.disabled = false;
                const msgDiv = document.getElementById('clientFormMessage');

                if (data.success) {
                    msgDiv.innerHTML = '<div class="alert alert-success">Klienti u ruajt me sukses!</div>';

                    var addModal = bootstrap.Modal.getInstance(document.getElementById('addClientModal'));
                    addModal.hide();

                    if (window.currentReservationModalId) {
                        var reserveModalEl = document.getElementById(window.currentReservationModalId);
                        if (reserveModalEl) {
                            var reserveModal = new bootstrap.Modal(reserveModalEl);
                            reserveModal.show();

                            var select = reserveModalEl.querySelector('select[name="client_id"]');
                            if (select) {
                                var option = new Option(data.full_name, data.client_id, true, true);
                                select.appendChild(option);
                                select.value = data.client_id;
                            }
                        }
                    }

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