<div class="container my-5">

    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">

                <!-- Header -->
                <div class="p-4 text-white" style="background: linear-gradient(135deg, #36b9cc, #2c9faf);">
                    <h2 class="mb-0">
                        <i class="fa fa-user-plus me-2"></i> Create New Student
                    </h2>
                </div>

                <!-- Body -->
                <div class="card-body p-4 bg-light">

                    <form id="createStudentForm" novalidate action="/admin/student/create" method="POST">

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
                                    name="name" placeholder="Enter student's full name" required>
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
                                    name="email" placeholder="Enter student's email address" required>
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
                            <small class="text-muted">
                                <i class="fa fa-info-circle me-1"></i>
                                At least 8 characters, including uppercase, lowercase, number, and symbol
                            </small>
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

                        <!-- Address -->
                        <div class="mb-4">
                            <label for="address" class="form-label fw-semibold">Complete Address <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white text-primary">
                                        <i class="fa fa-address-card"></i>
                                    </span>
                                </div>
                                <textarea class="form-control form-control-lg rounded-right" id="address" name="address"
                                    rows="3" placeholder="Street, City, State, ZIP, Country" required></textarea>
                            </div>
                            <div class="invalid-feedback"></div>
                            <small class="text-muted"><i class="fa fa-info-circle me-1"></i> Include full address
                                details</small>
                        </div>

                        <!-- Hidden Inputs -->
                        <input type="hidden" name="role" value="student">
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
                                    <i class="fa fa-user-plus me-2"></i> Create Student
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<style>
    input:focus,
    textarea:focus {
        border-color: #0066ff;
        box-shadow: 0 0 0 .25rem rgba(0, 102, 255, 0.2);
    }
</style>

<script>

    $(document).ready(function () {

        $('#createStudentForm').on('submit', function (e) {
            e.preventDefault();
            var datas = $(this).serializeArray();
            var data_array = {};
            $.map(datas, function (data) {
                data_array[data['name']] = data['value'];
            });

            $.ajax({
                url: "/admin/student/create",
                method: "POST",
                data: {
                    ...data_array,
                },
                success: function (result) {
                    var toastText = 'Student added successfully!';
                    var toastIcon = 'success';
                    generateToast(toastText, toastIcon, 'Success');

                    resetForm();
                },
                error: function (jqXHR) {
                    var res = JSON.parse(jqXHR.responseText)
                    console.log(res)
                    var toastText = res.message;
                    var toastIcon = 'error';
                    var toastHeading = res.error;
                    generateToast(toastText, toastIcon, toastHeading);
                }
            });

        })

    });

</script>