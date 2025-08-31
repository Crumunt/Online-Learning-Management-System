<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">

                <!-- Header -->
                <div class="card-header bg-primary bg-gradient text-white py-3">
                    <h4 class="mb-0"><i class="fa fa-edit mr-2"></i>Edit Course</h4>
                </div>

                <!-- Body -->
                <div class="card-body p-4">
                    <form id="editForm" method="post" action="/admin/course/update">

                        <!-- Name -->
                        <div class="form-group">
                            <label for="title">Course Title</label>
                            <input type="text" class="form-control" id="title" name="title"
                                value="<?= htmlspecialchars($data['title']) ?>" required>
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <div class="d-flex justify-content-between">
                                <label for="description">Description</label>
                                <div class="characterIndicator">
                                    <span id="charCount">250</span>
                                    <small> characters remaining</small>
                                </div>
                            </div>
                            <textarea class="form-control" maxlength="250" id="description" name="description"
                                required><?= htmlspecialchars($data['description']) ?></textarea>
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

                        <!-- Actions -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="/admin/course" class="btn btn-secondary rounded-pill px-4">
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



        const TEXTAREA_MAX_LENGTH = 250;


        $('#charCount').html(TEXTAREA_MAX_LENGTH - $('#description').val().length)

        $('#description').on('input', function () {

            console.log('testing')
            let currLength = $(this).val().length;
            let remainingCharacters = TEXTAREA_MAX_LENGTH - currLength

            $('#charCount').html(remainingCharacters);

        });


        $('#editForm').on('submit', function (e) {

            e.preventDefault();

            var datas = $(this).serializeArray();
            var data_array = {};
            $.map(datas, function (data) {
                data_array[data['name']] = data['value'];
            });


            $.ajax({
                url: "/admin/course/update",
                method: "POST",
                data: {
                    ...data_array,
                },
                success: function (result) {
                    var toastText = 'Course updated successfully!';
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