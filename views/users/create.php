<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">
            <!-- Header Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body bg-primary text-white text-center py-4">
                    <i class="fa fa-chalkboard-teacher fa-3x mb-3"></i>
                    <h2 class="mb-0">Create New <?= ucfirst($userType) ?></h2>
                    <p class="mb-0 opacity-75">Add a new <?= $userType ?> to the system</p>
                </div>
            </div>

            <!-- Form Card -->
            <div class="card shadow border-0">
                <div class="card-body p-4">
                    <form id="createInstructorForm" method="POST" novalidate>

                        <!-- Full Name -->
                        <div class="form-group mb-4">
                            <label for="name" class="font-weight-bold text-dark mb-2">
                                <i class="fa fa-user text-primary mr-2"></i>Full Name
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-lg">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light ">
                                        <i class="fa fa-user text-muted"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control  bg-light" id="name" name="name"
                                    placeholder="Enter full name" required>
                            </div>
                            <div class="invalid-feedback">Please enter the full name.</div>
                        </div>

                        <!-- Email -->
                        <div class="form-group mb-4">
                            <label for="email" class="font-weight-bold text-dark mb-2">
                                <i class="fa fa-envelope text-primary mr-2"></i>Email Address
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-lg">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light ">
                                        <i class="fa fa-envelope text-muted"></i>
                                    </span>
                                </div>
                                <input type="email" class="form-control  bg-light" id="email" name="email"
                                    placeholder="someuser@example.com" required>
                                <div class="input-group-append d-none" id="emailSpinner">
                                    <span class="input-group-text bg-light">
                                        <i class="fa fa-spinner fa-spin text-primary"></i>
                                    </span>
                                </div>
                            </div>
                            <small class="form-text text-muted mt-2">
                                <i class="fa fa-info-circle text-info mr-1"></i>
                                This email will be used for login credentials
                            </small>
                            <div class="invalid-feedback">Please enter a valid email address.</div>
                        </div>

                        <!-- Password -->
                        <div class="form-group mb-4">
                            <label for="password" class="font-weight-bold text-dark mb-2">
                                <i class="fa fa-lock text-primary mr-2"></i>Password
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-lg">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light">
                                        <i class="fa fa-lock text-muted"></i>
                                    </span>
                                </div>
                                <input type="password" class="form-control bg-light" id="password" name="password"
                                    placeholder="Create a strong password" required>
                            </div>
                            <small class="form-text text-muted mt-2">
                                <i class="fa fa-shield-alt text-success mr-1"></i>
                                Must contain at least 8 characters with uppercase, lowercase, number, and symbol
                            </small>
                            <div class="invalid-feedback">Please enter a valid password.</div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group mb-4">
                            <label for="confirm_password" class="font-weight-bold text-dark mb-2">
                                <i class="fa fa-check-circle text-primary mr-2"></i>Confirm Password
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-lg">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light ">
                                        <i class="fa fa-check-circle text-muted"></i>
                                    </span>
                                </div>
                                <input type="password" class="form-control  bg-light" id="confirm_password"
                                    name="confirm_password" placeholder="Confirm your password" required>
                            </div>
                            <div class="invalid-feedback">Passwords do not match.</div>
                        </div>

                        <?php if (strtolower($userType) === 'student'): ?>
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
                        <?php endif; ?>

                        <!-- Hidden Inputs -->
                        <input type="hidden" name="role" value="<?= $userType ?>">
                        <input type="hidden" name="status" value="pending">

                        <!-- Password Strength Indicator -->
                        <div class="mb-4">
                            <small class="text-muted">Password Strength:</small>
                            <div class="progress mt-1" style="height: 6px;">
                                <div class="progress-bar" role="progressbar" id="passwordStrength" style="width: 0%">
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="border-top pt-4 mt-4">
                            <div class="row">
                                <div class="col-md-6 mb-2 mb-md-0">
                                    <button type="button" class="btn btn-outline-secondary btn-lg btn-block"
                                        onclick="window.history.back()">
                                        <i class="fa fa-arrow-left mr-2"></i>Cancel
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-6 pr-1">
                                            <button type="button" class="btn btn-outline-info btn-lg btn-block"
                                                onclick="resetForm()">
                                                <i class="fa fa-undo"></i>
                                            </button>
                                        </div>
                                        <div class="col-6 pl-1">
                                            <button type="submit" class="btn btn-primary btn-lg btn-block"
                                                id="submitBtn">
                                                <i class="fa fa-plus-circle mr-2"></i>Create
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

            <!-- Help Card -->
            <div class="card border-0 mt-4 bg-transparent">
                <div class="card-body text-center">
                    <small class="text-muted">
                        <i class="fa fa-question-circle mr-1"></i>
                        Need help? Contact the system administrator
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>


<script>

    const CONFIG = {
        ENDPOINT: {
            POST: '<?= $urlEndpoint ?>'
        },
        MESSAGE: {
            SUCCESS: '<?= ucfirst($userType) ?> added successfully!',
        }
    }

    $('#password').on('input', function () {
        const password = $(this).val();
        const strengthBar = $('#passwordStrength');
        let strength = 0;
        if (password.length >= 8) strength += 25;
        if (/[A-Z]/.test(password)) strength += 25;
        if (/[a-z]/.test(password)) strength += 25;
        if (/[0-9]/.test(password) && /[^A-Za-z0-9]/.test(password)) strength += 25;

        strengthBar.css('width', ` ${strength}%`);
        if (strength < 50) {
            strengthBar.className = 'progress-bar bg-danger';
        } else if (strength < 75) {
            strengthBar.className = 'progress-bar bg-warning';
        } else {
            strengthBar.className = 'progress-bar bg-success';
        }
    })

    $('#confirm_password').on('input', function () {
        const password = $('#password').val();
        const confirmPassword = $(this).val();

        if (password != confirmPassword) {
            $(this).addClass('is-invalid')
        }
        else {
            $(this).removeClass('is-invalid');
            $(this).addClass('is-valid')
        }
    })

    // $('#email').blur(function () {
    //     const emailSpinner = $('#emailSpinner');
    //     const email = $(this).val();

    //     if (email && email.includes('@')) {
    //         emailSpinner.removeClass('d-none');

    //         // Simulate email check
    //         // ! TODO EMAIL CHECK
    //         setTimeout(() => {
    //             emailSpinner.addClass('d-none');
    //             $(this).addClass('is-valid');
    //         }, 1000);
    //     }

    // })


    $(document).ready(function () {
        $('#createInstructorForm').on('submit', function (e) {
            e.preventDefault();

            var datas = $(this).serializeArray();

            var data_array = {};

            $.map(datas, function (data) {
                data_array[data['name']] = data['value'];
            });

            $.ajax({
                url: CONFIG.ENDPOINT.POST,
                method: "POST",
                data: {
                    ...data_array,
                },
                success: function (result) {
                    console.log(result);

                    var toastText = CONFIG.MESSAGE.SUCCESS;
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