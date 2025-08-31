<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">

                <!-- Header -->
                <div class="card-header bg-primary bg-gradient text-white py-3">
                    <h4 class="mb-0"><i class="fa fa-edit mr-2"></i>Edit Instructor</h4>
                </div>

                <!-- Body -->
                <div class="card-body p-4">
                    <form id="editForm" method="post" action="/admin/instructor/update">

                        <!-- Name -->
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="<?= htmlspecialchars($data['name']) ?>" required>
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="<?= htmlspecialchars($data['email']) ?>" required>
                        </div>

                        <!-- Status -->
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="pending" <?= $data['status'] === 'pending' ? 'selected' : '' ?>>Pending
                                </option>
                                <option value="approved" <?= $data['status'] === 'approved' ? 'selected' : '' ?>>Approved
                                </option>
                                <option value="suspended" <?= $data['status'] === 'suspended' ? 'selected' : '' ?>>
                                    Suspended</option>
                            </select>
                        </div>

                        <!-- Hidden ID -->
                        <input type="hidden" name="id" value="<?= $data['id'] ?>">
                        <!-- Hidden Role -->
                        <input type="hidden" name="role" value="instructor">

                        <!-- Actions -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="/admin/instructor" class="btn btn-secondary rounded-pill px-4">
                                <i class="fa fa-arrow-left mr-1"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                                <i class="fa fa-save mr-1"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function () {

        $('#editForm').on('submit', function (e) {

            e.preventDefault();

            var datas = $(this).serializeArray();
            var data_array = {};
            console.log('something happened3')
            $.map(datas, function (data) {
                data_array[data['name']] = data['value'];
            });


            $.ajax({
                url: "/admin/instructor/update",
                method: "POST",
                data: {
                    ...data_array,
                },
                success: function (result) {
                    var toastText = 'Instructor updated successfully!';
                    var toastIcon = 'success';
                    generateToast(toastText, toastIcon, 'Success');
                },
                error: function (jqXHR) {
                    var res = JSON.parse(jqXHR.responseText)
                    var toastText = res.error;
                    var toastIcon = 'error';
                    generateToast(toastText, toastIcon, 'ERROR');
                }
            });
        });
    });

</script>