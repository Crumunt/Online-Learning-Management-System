<div class="container my-5">

    <form id="createInstructorForm" action="/admin/instructor/create" method="POST" novalidate>

        <!-- Full Name -->
        <div class="form-group">
            <label for="name" class="font-weight-bold">Full Name <span class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-white text-primary"><i class="fa fa-user"></i></span>
                </div>
                <input type="text" class="form-control" id="name" name="name" placeholder="John Doe" required>
            </div>
            <div class="invalid-feedback">Please enter the full name.</div>
        </div>

        <!-- Email -->
        <div class="form-group">
            <label for="email" class="font-weight-bold">Email Address <span class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-white text-primary"><i class="fa fa-envelope"></i></span>
                </div>
                <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com"
                    required>
                <div class="input-group-append d-none" id="emailSpinner">
                    <span class="input-group-text bg-white">
                        <div class="spinner-border spinner-border-sm text-primary"></div>
                    </span>
                </div>
            </div>
            <small class="form-text text-muted">
                <i class="fa fa-info-circle mr-1"></i>Used for login credentials.
            </small>
            <div class="invalid-feedback">Please enter a valid email address.</div>
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="font-weight-bold">Password <span class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-white text-primary"><i class="fa fa-lock"></i></span>
                </div>
                <input type="password" class="form-control" id="password" name="password" placeholder="••••••••"
                    required>
            </div>
            <small class="form-text text-muted">
                <i class="fa fa-info-circle mr-1"></i>At least 8 characters including uppercase, lowercase, number, and
                symbol.
            </small>
            <div class="invalid-feedback">Please enter a valid password.</div>
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <label for="confirm_password" class="font-weight-bold">Confirm Password <span
                    class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-white text-primary"><i class="fa fa-lock"></i></span>
                </div>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                    placeholder="Re-enter password" required>
            </div>
            <div class="invalid-feedback">Passwords do not match.</div>
        </div>

        <!-- Hidden Inputs -->
        <input type="hidden" name="role" value="instructor">
        <input type="hidden" name="status" value="pending">

        <!-- Form Actions -->
        <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-4">
            <button type="button" class="btn btn-outline-secondary" onclick="window.history.back()">
                <i class="fa fa-arrow-left mr-2"></i>Cancel
            </button>
            <div>
                <button type="button" class="btn btn-secondary mr-2" onclick="resetForm()">
                    <i class="fa fa-undo mr-2"></i>Reset
                </button>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fa fa-chalkboard-teacher mr-2"></i>Create Instructor
                </button>
            </div>
        </div>

    </form>

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