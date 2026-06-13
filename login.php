<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/config/database.php';

// Redirect if already logged in - BEFORE any HTML output
if (isLoggedIn()) {
    redirect('dashboard.php');
}

$currentPage = 'login';
$pageTitle = 'Student Login - NEXUS COMPUTER INSTITUTE';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<section class="auth-container">
    <div class="auth-card" data-aos="fade-up">
        <div style="text-align:center; margin-bottom:24px;">
            <div style="width:64px; height:64px; background:linear-gradient(135deg, var(--accent), var(--highlight)); border-radius:16px; display:flex; align-items:center; justify-content:center; margin:0 auto 16px; font-size:1.5rem; color:var(--primary);">
                <i class="fas fa-user-graduate"></i>
            </div>
        </div>

        <h2>Welcome Back</h2>
        <p class="auth-subtitle">Login to your student account</p>

        <div id="login-alert"></div>

        <!-- Social Login Buttons -->
        <div class="social-login">
            <button class="social-btn google" disabled title="Coming Soon">
                <i class="fab fa-google"></i> Google
            </button>
            <button class="social-btn facebook" disabled title="Coming Soon">
                <i class="fab fa-facebook-f"></i> Facebook
            </button>
        </div>

        <div class="divider">or login with email</div>

        <form id="loginForm">
            <?= csrfField() ?>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="your@email.com" required>
                <div class="form-error"></div>
            </div>

            <div class="form-group">
                <label>Password</label>
                <div style="position:relative;">
                    <input type="password" name="password" class="form-control" placeholder="Enter your password" required style="padding-right:44px;">
                    <button type="button" class="toggle-password" style="position:absolute; right:14px; top:50%; transform:translateY(-50%); background:none; border:none; color:var(--text-muted); cursor:pointer;">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div class="form-error"></div>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; margin-top:8px;">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>

        <div class="auth-footer">
            Don't have an account? <a href="<?= assetUrl('register.php') ?>">Register Now</a>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var input = this.parentElement.querySelector('input');
            var icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });

    // Login form
    var form = document.getElementById('loginForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (!NexusForm.validate(form)) return;

            var formData = new FormData(form);
            var btn = form.querySelector('button[type="submit"]');
            var originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Logging in...';

            fetch('<?= assetUrl('api/login.php') ?>', { method: 'POST', body: formData })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                btn.disabled = false;
                btn.innerHTML = originalText;
                var alertDiv = document.getElementById('login-alert');
                if (data.success) {
                    window.location.href = data.redirect || '<?= assetUrl('dashboard.php') ?>';
                } else {
                    alertDiv.innerHTML = '<div class="alert alert-danger fade-in-up"><i class="fas fa-exclamation-circle"></i> ' + (data.message || 'Invalid credentials.') + '</div>';
                    setTimeout(function() { alertDiv.innerHTML = ''; }, 4000);
                }
            })
            .catch(function() {
                btn.disabled = false;
                btn.innerHTML = originalText;
                document.getElementById('login-alert').innerHTML = '<div class="alert alert-danger fade-in-up"><i class="fas fa-exclamation-circle"></i> An error occurred.</div>';
            });
        });
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
