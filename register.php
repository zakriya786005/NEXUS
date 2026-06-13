<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/config/database.php';

// Redirect if already logged in - BEFORE any HTML output
if (isLoggedIn()) {
    redirect('dashboard.php');
}

$currentPage = 'login';
$pageTitle = 'Student Registration - NEXUS COMPUTER INSTITUTE';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<section class="auth-container">
    <div class="auth-card" data-aos="fade-up" style="max-width: 540px;">
        <div style="text-align:center; margin-bottom:24px;">
            <div style="width:64px; height:64px; background:linear-gradient(135deg, var(--accent), var(--highlight)); border-radius:16px; display:flex; align-items:center; justify-content:center; margin:0 auto 16px; font-size:1.5rem; color:var(--primary);">
                <i class="fas fa-user-plus"></i>
            </div>
        </div>

        <h2>Create Account</h2>
        <p class="auth-subtitle">Register as a new student</p>

        <div id="register-alert"></div>

        <form id="registerForm" enctype="multipart/form-data">
            <?= csrfField() ?>

            <div class="form-row">
                <div class="form-group">
                    <label>Full Name *</label>
                    <input type="text" name="name" class="form-control" placeholder="Full Name" required>
                    <div class="form-error"></div>
                </div>
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" class="form-control" placeholder="your@email.com" required>
                    <div class="form-error"></div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Phone *</label>
                    <input type="tel" name="phone" class="form-control" placeholder="03XX-XXXXXXX" required>
                    <div class="form-error"></div>
                </div>
                <div class="form-group">
                    <label>CNIC</label>
                    <input type="text" name="cnic" class="form-control" placeholder="XXXXX-XXXXXXX-X" maxlength="15">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Password *</label>
                    <input type="password" name="password" class="form-control" placeholder="Min 6 characters" required minlength="6">
                    <div class="form-error"></div>
                </div>
                <div class="form-group">
                    <label>Confirm Password *</label>
                    <input type="password" name="confirm_password" class="form-control" placeholder="Re-enter password" required>
                    <div class="form-error"></div>
                </div>
            </div>

            <div class="form-group">
                <label>Profile Image</label>
                <input type="file" name="profile_image" class="form-control" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; margin-top:8px;">
                <i class="fas fa-user-plus"></i> Register
            </button>
        </form>

        <div class="auth-footer">
            Already have an account? <a href="<?= assetUrl('login.php') ?>">Login Here</a>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('registerForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (!NexusForm.validate(form)) return;

            // Check password match
            var pass = form.querySelector('[name="password"]').value;
            var confirmPass = form.querySelector('[name="confirm_password"]').value;
            if (pass !== confirmPass) {
                document.getElementById('register-alert').innerHTML = '<div class="alert alert-danger fade-in-up"><i class="fas fa-exclamation-circle"></i> Passwords do not match.</div>';
                return;
            }

            var formData = new FormData(form);
            var btn = form.querySelector('button[type="submit"]');
            var originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Registering...';

            fetch('<?= assetUrl('api/register.php') ?>', { method: 'POST', body: formData })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                btn.disabled = false;
                btn.innerHTML = originalText;
                var alertDiv = document.getElementById('register-alert');
                if (data.success) {
                    alertDiv.innerHTML = '<div class="alert alert-success fade-in-up"><i class="fas fa-check-circle"></i> ' + data.message + '</div>';
                    form.reset();
                    setTimeout(function() { window.location.href = '<?= assetUrl('login.php') ?>'; }, 2000);
                } else {
                    alertDiv.innerHTML = '<div class="alert alert-danger fade-in-up"><i class="fas fa-exclamation-circle"></i> ' + (data.message || 'Registration failed.') + '</div>';
                }
                setTimeout(function() { if (!data.success) alertDiv.innerHTML = ''; }, 5000);
            })
            .catch(function() {
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
        });
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
