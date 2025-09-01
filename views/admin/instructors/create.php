<div class="container my-5">

    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">

                <!-- Header -->
                <div class="p-4 text-white" style="background: linear-gradient(135deg, #4e73df, #224abe);">
                    <h2 class="mb-0">
                        <i class="fa fa-graduation-cap me-2"></i> Create New Instructor
                    </h2>
                </div>

                <!-- Body -->
                <div class="card-body p-4 bg-light">

                    <!-- Success/Error Alerts -->
                    <div id="alertContainer" class="mb-3"></div>

                    <form id="createInstructorForm" novalidate action="/admin/instructor/create" method="POST">

                        <!-- Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">Full Name <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white text-primary">
                                        <i class="fa fa-user"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control form-control-lg rounded-right-pill" id="name"
                                    name="name" placeholder="Enter instructor's full name" required>
                            </div>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">Email Address <span
                                    class="text-danger">*</span></label>
                            <div class="input-group position-relative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white text-primary">
                                        <i class="fa fa-envelope"></i>
                                    </span>
                                </div>
                                <input type="email" class="form-control form-control-lg rounded-right-pill" id="email"
                                    name="email" placeholder="Enter instructor's email address" required>
                                <div class="position-absolute top-50 end-0 translate-middle-y pe-3 d-none"
                                    id="emailSpinner">
                                    <div class="spinner-border spinner-border-sm text-primary"></div>
                                </div>
                            </div>
                            <div class="invalid-feedback"></div>
                            <small class="text-muted"><i class="fa fa-info-circle me-1"></i> Used for login
                                credentials</small>
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold">Password <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white text-primary">
                                        <i class="fa fa-lock"></i>
                                    </span>
                                </div>
                                <input type="password" class="form-control form-control-lg" id="password"
                                    name="password" placeholder="Enter a secure password" required>
                            </div>
                            <div class="invalid-feedback"></div>
                            <small class="text-muted"><i class="fa fa-info-circle me-1"></i>
                                At least 8 characters, including uppercase, lowercase, number, and symbol</small>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="confirm_password" class="form-label fw-semibold">Confirm Password <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white text-primary">
                                        <i class="fa fa-lock"></i>
                                    </span>
                                </div>
                                <input type="password" class="form-control form-control-lg rounded-right-pill"
                                    id="confirm_password" name="confirm_password" placeholder="Confirm the password"
                                    required>
                            </div>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Hidden Inputs -->
                        <input type="hidden" name="role" value="instructor">
                        <input type="hidden" name="status" value="pending">

                        <!-- Actions -->
                        <div class="pt-3 border-top d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary btn-lg px-4 rounded-pill shadow-sm"
                                onclick="window.history.back()">
                                <i class="fa fa-arrow-left me-2"></i> Cancel
                            </button>
                            <div>
                                <button type="button" class="btn btn-secondary btn-lg px-4 rounded-pill shadow-sm me-2"
                                    onclick="resetForm()">
                                    <i class="fa fa-undo me-2"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary btn-lg px-4 rounded-pill shadow-sm"
                                    id="submitBtn">
                                    <i class="fa fa-chalkboard-teacher me-2"></i> Create Instructor
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script>

    $(document).ready(function () {
        $('#createInstructorForm').on('submit', function (e) {
            e.preventDefault();

            console.log('something happened')

            var datas = $(this).serializeArray();
            console.log('something happened2', datas)
            var data_array = {};
            console.log('something happened3')
            $.map(datas, function (data) {
                data_array[data['name']] = data['value'];
            });
            console.log('something happened4')


            $.ajax({
                url: "/admin/instructor/create",
                method: "POST",
                data: {
                    ...data_array,
                },
                success: function (result) {
                    console.log(result);

                    var toastText = 'Instructor added successfully!';
                    var toastIcon = 'success';
                    generateToast(toastText, toastIcon, 'Success');

                    resetForm();
                },
                error: function (jqXHR) {
                    var res = JSON.parse(jqXHR.responseText)
                    console.log(jqXHR);
                    var toastText = res.message;
                    var toastIcon = 'error';
                    generateToast(toastText, toastIcon, res.error);
                }
            });
        })
    });

</script>