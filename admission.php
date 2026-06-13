<?php
$currentPage = 'admission';
$pageTitle = 'Online Admission - NEXUS COMPUTER INSTITUTE';
$pageDesc = 'Apply online for admission to NEXUS Computer Institute. Fill the form to enroll in our IT courses.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

// Fetch courses for dropdown
$courses = [];
try {
    $stmt = db()->query("SELECT id, title, fee, duration FROM courses WHERE status = 'active' ORDER BY title ASC");
    $courses = $stmt->fetchAll();
} catch (Exception $e) {}

$selectedCourse = isset($_GET['course']) ? (int) $_GET['course'] : 0;
?>

<!-- Page Header -->
<section class="page-header">
    <h1 data-aos="fade-up">Online <span>Admission</span></h1>
    <div class="breadcrumb" data-aos="fade-up" data-aos-delay="200">
        <a href="<?= assetUrl('index.php') ?>">Home</a>
        <span>/</span>
        <span>Admission</span>
    </div>
</section>

<!-- Admission Form -->
<section class="section" style="padding-top: 40px;">
    <div class="container">
        <div class="grid-2">
            <!-- Form -->
            <div class="glass-card" data-aos="fade-right">
                <h2 style="margin-bottom:8px;"><i class="fas fa-file-alt" style="color:var(--accent);"></i> Admission Form</h2>
                <p style="color:var(--text-muted); margin-bottom:32px;">Fill the form below to apply for admission. All fields marked with * are required.</p>

                <div id="admission-alert"></div>

                <form id="admissionForm" enctype="multipart/form-data">
                    <?= csrfField() ?>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Student Name *</label>
                            <input type="text" name="student_name" class="form-control" placeholder="Full Name" required>
                            <div class="form-error"></div>
                        </div>
                        <div class="form-group">
                            <label>Father's Name *</label>
                            <input type="text" name="father_name" class="form-control" placeholder="Father's Name" required>
                            <div class="form-error"></div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>CNIC *</label>
                            <input type="text" name="cnic" class="form-control" placeholder="XXXXX-XXXXXXX-X" required maxlength="15">
                            <div class="form-error"></div>
                        </div>
                        <div class="form-group">
                            <label>Phone Number *</label>
                            <input type="tel" name="phone" class="form-control" placeholder="03XX-XXXXXXX" required maxlength="15">
                            <div class="form-error"></div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Email Address *</label>
                            <input type="email" name="email" class="form-control" placeholder="your@email.com" required>
                            <div class="form-error"></div>
                        </div>
                        <div class="form-group">
                            <label>Gender *</label>
                            <select name="gender" class="form-control" required>
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                            <div class="form-error"></div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Date of Birth *</label>
                            <input type="date" name="date_of_birth" class="form-control" required>
                            <div class="form-error"></div>
                        </div>
                        <div class="form-group">
                            <label>Select Course *</label>
                            <select name="course_id" class="form-control" required>
                                <option value="">Select a Course</option>
                                <?php foreach ($courses as $course): ?>
                                    <option value="<?= $course['id'] ?>" <?= ($selectedCourse === (int) $course['id']) ? 'selected' : '' ?>>
                                        <?= sanitize($course['title']) ?> - PKR <?= number_format((float) $course['fee'], 0) ?> (<?= sanitize($course['duration']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-error"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Address *</label>
                        <textarea name="address" class="form-control" rows="3" placeholder="Complete Address" required></textarea>
                        <div class="form-error"></div>
                    </div>

                    <div class="form-group">
                        <label>Profile Image</label>
                        <input type="file" name="profile_image" class="form-control" accept="image/*" data-preview="profilePreview">
                        <div class="image-preview">
                            <img id="profilePreview" src="" alt="Preview" style="display:none;">
                            <span class="placeholder" id="previewPlaceholder"><i class="fas fa-camera"></i></span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; margin-top:16px;">
                        <i class="fas fa-paper-plane"></i> Submit Application
                    </button>
                </form>
            </div>

            <!-- Sidebar Info -->
            <div data-aos="fade-left">
                <div class="glass-card" style="margin-bottom:24px;">
                    <h3 style="margin-bottom:16px;"><i class="fas fa-info-circle" style="color:var(--accent);"></i> Admission Process</h3>
                    <div style="color:var(--text-light); line-height:2;">
                        <div style="display:flex; align-items:center; gap:12px; margin-bottom:12px;">
                            <span style="width:32px; height:32px; background:rgba(0,212,255,0.2); border-radius:50%; display:flex; align-items:center; justify-content:center; color:var(--accent); font-weight:700; font-size:0.85rem;">1</span>
                            Fill the online admission form
                        </div>
                        <div style="display:flex; align-items:center; gap:12px; margin-bottom:12px;">
                            <span style="width:32px; height:32px; background:rgba(0,212,255,0.2); border-radius:50%; display:flex; align-items:center; justify-content:center; color:var(--accent); font-weight:700; font-size:0.85rem;">2</span>
                            Submit required documents at institute
                        </div>
                        <div style="display:flex; align-items:center; gap:12px; margin-bottom:12px;">
                            <span style="width:32px; height:32px; background:rgba(0,212,255,0.2); border-radius:50%; display:flex; align-items:center; justify-content:center; color:var(--accent); font-weight:700; font-size:0.85rem;">3</span>
                            Pay admission fee
                        </div>
                        <div style="display:flex; align-items:center; gap:12px; margin-bottom:12px;">
                            <span style="width:32px; height:32px; background:rgba(0,212,255,0.2); border-radius:50%; display:flex; align-items:center; justify-content:center; color:var(--accent); font-weight:700; font-size:0.85rem;">4</span>
                            Receive confirmation & start classes
                        </div>
                    </div>
                </div>

                <div class="glass-card" style="margin-bottom:24px;">
                    <h3 style="margin-bottom:16px;"><i class="fas fa-file-invoice" style="color:var(--accent);"></i> Required Documents</h3>
                    <ul style="color:var(--text-muted);">
                        <li style="padding:6px 0;"><i class="fas fa-check" style="color:var(--highlight); margin-right:8px;"></i> CNIC / B-Form Copy</li>
                        <li style="padding:6px 0;"><i class="fas fa-check" style="color:var(--highlight); margin-right:8px;"></i> 2 Passport Size Photos</li>
                        <li style="padding:6px 0;"><i class="fas fa-check" style="color:var(--highlight); margin-right:8px;"></i> Last Educational Certificate</li>
                        <li style="padding:6px 0;"><i class="fas fa-check" style="color:var(--highlight); margin-right:8px;"></i> Father's CNIC Copy</li>
                    </ul>
                </div>

                <div class="glass-card">
                    <h3 style="margin-bottom:16px;"><i class="fas fa-headset" style="color:var(--accent);"></i> Need Help?</h3>
                    <p style="color:var(--text-muted); margin-bottom:16px;">Contact our admission office for any queries</p>
                    <a href="tel:03001234567" style="color:var(--accent); display:block; margin-bottom:8px;"><i class="fas fa-phone"></i> 0300-1234567</a>
                    <a href="mailto:admissions@nexusinstitute.pk" style="color:var(--accent);"><i class="fas fa-envelope"></i> admissions@nexusinstitute.pk</a>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('admissionForm');
    if (!form) return;

    // CNIC formatting
    var cnicInput = form.querySelector('[name="cnic"]');
    if (cnicInput) {
        cnicInput.addEventListener('input', function() {
            var val = this.value.replace(/\D/g, '');
            if (val.length > 5) val = val.slice(0,5) + '-' + val.slice(5);
            if (val.length > 13) val = val.slice(0,13) + '-' + val.slice(13);
            if (val.length > 15) val = val.slice(0,15);
            this.value = val;
        });
    }

    // Phone formatting
    var phoneInput = form.querySelector('[name="phone"]');
    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            var val = this.value.replace(/\D/g, '');
            if (val.length > 4) val = val.slice(0,4) + '-' + val.slice(4);
            if (val.length > 12) val = val.slice(0,12);
            this.value = val;
        });
    }

    // Image preview
    var fileInput = form.querySelector('[name="profile_image"]');
    var preview = document.getElementById('profilePreview');
    var placeholder = document.getElementById('previewPlaceholder');
    if (fileInput && preview) {
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    if (placeholder) placeholder.style.display = 'none';
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    }

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        if (!NexusForm.validate(form)) return;

        var formData = new FormData(form);
        var btn = form.querySelector('button[type="submit"]');
        var originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';

        fetch('<?= assetUrl('api/admission.php') ?>', {
            method: 'POST',
            body: formData
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            btn.disabled = false;
            btn.innerHTML = originalText;

            var alertDiv = document.getElementById('admission-alert');
            if (data.success) {
                alertDiv.innerHTML = '<div class="alert alert-success fade-in-up"><i class="fas fa-check-circle"></i> ' + data.message + '</div>';
                form.reset();
                if (preview) { preview.style.display = 'none'; preview.src = ''; }
                if (placeholder) placeholder.style.display = '';
            } else {
                alertDiv.innerHTML = '<div class="alert alert-danger fade-in-up"><i class="fas fa-exclamation-circle"></i> ' + (data.message || 'Submission failed. Please try again.') + '</div>';
            }

            setTimeout(function() { alertDiv.innerHTML = ''; }, 6000);
        })
        .catch(function() {
            btn.disabled = false;
            btn.innerHTML = originalText;
            document.getElementById('admission-alert').innerHTML = '<div class="alert alert-danger fade-in-up"><i class="fas fa-exclamation-circle"></i> An error occurred. Please try again.</div>';
        });
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
