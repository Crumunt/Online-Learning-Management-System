<!-- Header -->
<div class="text-center mb-4">
    <h1 class="display-5 fw-bold text-primary">LMS+</h1>
    <h5 class="mt-2 fw-semibold">Create Your Account</h5>
    <p class="text-muted">Join and start learning today</p>
</div>

<!-- Register Form -->
<form id="registerForm" action="/register" method="post">
    <!-- Full Name -->
    <div class="form-group mb-3">
        <label for="name" class="fw-semibold">Full Name</label>
        <input type="text" name="name" class="form-control form-control-lg rounded-3" id="name" placeholder="Enter your full name"
            required>
    </div>

    <!-- Email -->
    <div class="form-group mb-3">
        <label for="email" class="fw-semibold">Email Address</label>
        <input type="email" name="email" class="form-control form-control-lg rounded-3" id="email" placeholder="Enter your email"
            required>
    </div>

    <!-- Password -->
    <div class="form-group mb-3">
        <label for="password" class="fw-semibold">Password</label>
        <input type="password" name="password" class="form-control form-control-lg rounded-3" id="password"
            placeholder="Create a password" required>
    </div>

    <div class="form-group mb-3">
        <label for="confirmPassword" class="fw-semibold">Confirm Password</label>
        <input type="password" name="confirmPassword" class="form-control form-control-lg rounded-3" id="confirmPassword"
            placeholder="Confirm password" required>
    </div>

    <!-- Address -->
    <div class="form-group mb-3">
        <label for="address" class="fw-semibold">Address</label>
        <textarea name="address" class="form-control form-control-lg rounded-3" id="address" rows="3" placeholder="Enter your address"
            required></textarea>
    </div>

    <!-- HIDDEN INPUT -->
    <input type="hidden" name="role" value="student">

    <!-- Submit -->
    <button type="submit" class="btn btn-success btn-lg w-100 rounded-3 mt-2 shadow-sm">
        Create Account
    </button>
</form>

<!-- Links -->
<div class="d-flex justify-content-center mt-4">
    <p class="mb-0 text-muted">Already have an account?
        <a href="/login" class="text-decoration-none fw-semibold">Sign In</a>
    </p>
</div>


<script>

    $(document).ready(function () {

        $('#registerForm').on('submit', function (e) {
            e.preventDefault();

            var datas = $(this).serializeArray();
            var data_array = {};
            $.map(datas, function (data) {
                data_array[data['name']] = data['value'];
            });

            $.ajax({
                url: "/register",
                method: "POST",
                data: {
                    ...data_array,
                },
                dataType: "json",
                success: function (result) {
                    console.log('Registered successfully');
                    console.log(result);
                    window.location.href = result.route;
                },
                error: function (jqXHR) {
                    var res = JSON.parse(jqXHR.responseText)
                    var toastText = res.error;
                    var toastIcon = 'error';
                    generateToast(toastText, toastIcon, 'ERROR');
                }
            });
        })

    });

</script>