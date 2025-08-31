<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger" data-status="<?= $_SESSION['error_code'] ?>">
        <strong>Error <?= $_SESSION['error_code'] ?>:</strong>
        <?= htmlspecialchars($_SESSION['error']) ?>
    </div>
    <?php
    unset($_SESSION['error']);
    unset($_SESSION['error_code']);
?>
<?php endif; ?>

<!-- Logo -->
<div class="text-center mb-4">
    <h1 class="display-4 fw-bold text-primary">LMS+</h1>
    <h5 class="mt-2 fw-semibold">Welcome to Your Learning Hub</h5>
    <p class="text-muted">Log in to access your courses and dashboard</p>
</div>

<!-- Login Form -->
<form action="/login" method="post">
    <div class="form-group mb-3">
        <label for="email" class="fw-semibold">Email Address</label>
        <input type="email" class="form-control form-control-lg rounded-3" name="email" id="email"
            placeholder="Enter your email" required>
    </div>

    <div class="form-group mb-3">
        <label for="password" class="fw-semibold">Password</label>
        <input type="password" class="form-control form-control-lg rounded-3" name="password" id="password"
            placeholder="Enter your password" required>
    </div>

    <button type="submit" class="btn btn-primary btn-lg w-100 rounded-3 mt-2 shadow-sm">
        Sign In
    </button>
</form>

<!-- Links -->
<div class="d-flex justify-content-between mt-4">
    <a href="#" class="text-decoration-none">Forgot Password?</a>
    <a href="/register" class="text-decoration-none">Create an Account</a>
</div>