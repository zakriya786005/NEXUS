<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/config/database.php';

// Check auth BEFORE any HTML output
if (!isLoggedIn()) {
    redirect('login.php');
}

$studentId = (int) $_SESSION['student_id'];
$student = null;
$enrolledCourses = [];
$attendanceData = [];
$certificates = [];
$attPercentage = 0;

try {
    $stmt = db()->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->execute([$studentId]);
    $student = $stmt->fetch();

    // Enrolled courses
    $stmt = db()->prepare("SELECT c.*, e.enrollment_date, e.fee_paid FROM enrollments e JOIN courses c ON e.course_id = c.id WHERE e.student_id = ? AND e.status = 'active'");
    $stmt->execute([$studentId]);
    $enrolledCourses = $stmt->fetchAll();

    // Attendance
    $stmt = db()->prepare("SELECT a.*, c.title as course_title FROM attendance a LEFT JOIN courses c ON a.course_id = c.id WHERE a.student_id = ? ORDER BY a.date DESC LIMIT 30");
    $stmt->execute([$studentId]);
    $attendanceData = $stmt->fetchAll();

    $totalAtt = count($attendanceData);
    $presentAtt = count(array_filter($attendanceData, fn($r) => $r['status'] === 'present'));
    $attPercentage = $totalAtt > 0 ? round(($presentAtt / $totalAtt) * 100, 1) : 0;

    // Certificates
    $stmt = db()->prepare("SELECT cert.*, c.title as course_title FROM certificates cert JOIN courses c ON cert.course_id = c.id WHERE cert.student_id = ? AND cert.status = 'active'");
    $stmt->execute([$studentId]);
    $certificates = $stmt->fetchAll();

} catch (Exception $e) {}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    try {
        $imageUpdate = '';
        $uploaded = '';
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $uploaded = uploadImage($_FILES['profile_image'], 'students');
            if ($uploaded) $imageUpdate = ", profile_image = ?";
        }
        if ($imageUpdate) {
            $stmt = db()->prepare("UPDATE students SET name = ?, phone = ?, address = ? $imageUpdate WHERE id = ?");
            $stmt->execute([$name, $phone, $address, $uploaded, $studentId]);
        } else {
            $stmt = db()->prepare("UPDATE students SET name = ?, phone = ?, address = ? WHERE id = ?");
            $stmt->execute([$name, $phone, $address, $studentId]);
        }
        $_SESSION['student_name'] = $name;
        $successMsg = 'Profile updated successfully!';
        // Refresh student data
        $stmt = db()->prepare("SELECT * FROM students WHERE id = ?");
        $stmt->execute([$studentId]);
        $student = $stmt->fetch();
    } catch (Exception $e) {
        $errorMsg = 'Failed to update profile.';
    }
}

if (!$student) {
    redirect('logout.php');
}

$currentPage = 'dashboard';
$pageTitle = 'Student Dashboard - NEXUS COMPUTER INSTITUTE';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<section class="page-header" style="padding: 140px 0 40px;">
    <h1 data-aos="fade-up">My <span>Dashboard</span></h1>
    <div class="breadcrumb" data-aos="fade-up" data-aos-delay="100">
        <span>Welcome, <?= sanitize($student['name']) ?></span>
    </div>
</section>

<section class="section" style="padding-top: 20px;">
    <div class="container">
        <?php if (!empty($successMsg)): ?>
            <div class="alert alert-success fade-in-up"><i class="fas fa-check-circle"></i> <?= sanitize($successMsg) ?></div>
        <?php endif; ?>
        <?php if (!empty($errorMsg)): ?>
            <div class="alert alert-danger fade-in-up"><i class="fas fa-exclamation-circle"></i> <?= sanitize($errorMsg) ?></div>
        <?php endif; ?>

        <!-- Stats -->
        <div class="stats-grid" style="margin-bottom: 40px;">
            <div class="stat-card" data-aos="fade-up">
                <div class="stat-icon"><i class="fas fa-book"></i></div>
                <div class="stat-number" style="font-size:2rem;"><?= count($enrolledCourses) ?></div>
                <div class="stat-label">Enrolled Courses</div>
            </div>
            <div class="stat-card" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-icon"><i class="fas fa-clipboard-check"></i></div>
                <div class="stat-number" style="font-size:2rem;"><?= $attPercentage ?>%</div>
                <div class="stat-label">Attendance Rate</div>
            </div>
            <div class="stat-card" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-icon"><i class="fas fa-certificate"></i></div>
                <div class="stat-number" style="font-size:2rem;"><?= count($certificates) ?></div>
                <div class="stat-label">Certificates</div>
            </div>
            <div class="stat-card" data-aos="fade-up" data-aos-delay="300">
                <div class="stat-icon"><i class="fas fa-id-card"></i></div>
                <div class="stat-number" style="font-size:1.3rem;"><?= sanitize($student['enrollment_id'] ?? 'N/A') ?></div>
                <div class="stat-label">Enrollment ID</div>
            </div>
        </div>

        <div class="grid-2">
            <!-- Profile Section -->
            <div class="glass-card" data-aos="fade-right">
                <h3 style="margin-bottom:20px;"><i class="fas fa-user-circle" style="color:var(--accent);"></i> My Profile</h3>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="update_profile" value="1">

                    <div style="text-align:center; margin-bottom:20px;">
                        <div style="width:100px; height:100px; border-radius:50%; margin:0 auto; overflow:hidden; border:3px solid var(--accent);">
                            <?php if ($student['profile_image']): ?>
                                <img src="<?= assetUrl('uploads/students/' . $student['profile_image']) ?>" style="width:100%; height:100%; object-fit:cover;">
                            <?php else: ?>
                                <div style="width:100%; height:100%; background:linear-gradient(135deg, var(--accent), var(--highlight)); display:flex; align-items:center; justify-content:center; font-size:2.5rem; color:var(--primary);">
                                    <i class="fas fa-user"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" class="form-control" value="<?= sanitize($student['name']) ?>" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" value="<?= sanitize($student['email']) ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" name="phone" class="form-control" value="<?= sanitize($student['phone'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <textarea name="address" class="form-control" rows="2"><?= sanitize($student['address'] ?? '') ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Update Photo</label>
                        <input type="file" name="profile_image" class="form-control" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Update Profile</button>
                </form>
            </div>

            <!-- Enrolled Courses -->
            <div data-aos="fade-left">
                <div class="glass-card" style="margin-bottom:24px;">
                    <h3 style="margin-bottom:16px;"><i class="fas fa-book-open" style="color:var(--accent);"></i> Enrolled Courses</h3>
                    <?php if (empty($enrolledCourses)): ?>
                        <p style="color:var(--text-muted); text-align:center; padding:20px;">No courses enrolled yet. <a href="<?= assetUrl('admission.php') ?>">Apply Now</a></p>
                    <?php else: ?>
                        <?php foreach ($enrolledCourses as $ec): ?>
                        <div style="display:flex; gap:16px; align-items:center; padding:12px 0; border-bottom:1px solid var(--glass-border);">
                            <div style="width:48px; height:48px; min-width:48px; border-radius:12px; background:rgba(0,212,255,0.15); display:flex; align-items:center; justify-content:center; color:var(--accent);">
                                <i class="fas fa-laptop-code"></i>
                            </div>
                            <div style="flex:1;">
                                <h4 style="font-size:0.95rem;"><?= sanitize($ec['title']) ?></h4>
                                <p style="color:var(--text-muted); font-size:0.8rem;"><?= sanitize($ec['duration'] ?? '') ?> | <?= sanitize($ec['instructor'] ?? '') ?></p>
                            </div>
                            <span class="status-badge active" style="padding:4px 12px; border-radius:50px; font-size:0.75rem; background:rgba(100,255,218,0.15); color:var(--highlight);">Active</span>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Certificates -->
                <div class="glass-card">
                    <h3 style="margin-bottom:16px;"><i class="fas fa-certificate" style="color:var(--highlight);"></i> Certificates</h3>
                    <?php if (empty($certificates)): ?>
                        <p style="color:var(--text-muted); text-align:center; padding:20px;">No certificates yet. Complete a course to earn one!</p>
                    <?php else: ?>
                        <?php foreach ($certificates as $cert): ?>
                        <div style="display:flex; gap:16px; align-items:center; padding:12px 0; border-bottom:1px solid var(--glass-border);">
                            <div style="width:48px; height:48px; min-width:48px; border-radius:12px; background:rgba(100,255,218,0.15); display:flex; align-items:center; justify-content:center; color:var(--highlight);">
                                <i class="fas fa-award"></i>
                            </div>
                            <div style="flex:1;">
                                <h4 style="font-size:0.95rem;"><?= sanitize($cert['course_title']) ?></h4>
                                <p style="color:var(--text-muted); font-size:0.8rem;">#<?= sanitize($cert['certificate_number']) ?> | <?= date('M d, Y', strtotime($cert['issue_date'] ?? $cert['created_at'])) ?></p>
                            </div>
                            <?php if ($cert['grade']): ?>
                                <span style="color:var(--accent); font-weight:700;"><?= sanitize($cert['grade']) ?></span>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Recent Attendance -->
        <?php if (!empty($attendanceData)): ?>
        <div class="glass-card" style="margin-top:40px;" data-aos="fade-up">
            <h3 style="margin-bottom:16px;"><i class="fas fa-calendar-check" style="color:var(--accent);"></i> Recent Attendance</h3>
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th style="padding:10px; text-align:left; color:var(--text-muted); font-size:0.8rem; border-bottom:1px solid var(--glass-border);">Date</th>
                            <th style="padding:10px; text-align:left; color:var(--text-muted); font-size:0.8rem; border-bottom:1px solid var(--glass-border);">Course</th>
                            <th style="padding:10px; text-align:left; color:var(--text-muted); font-size:0.8rem; border-bottom:1px solid var(--glass-border);">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($attendanceData as $att): ?>
                        <tr>
                            <td style="padding:10px; border-bottom:1px solid var(--glass-border); font-size:0.88rem;"><?= date('M d, Y', strtotime($att['date'])) ?></td>
                            <td style="padding:10px; border-bottom:1px solid var(--glass-border); font-size:0.88rem;"><?= sanitize($att['course_title'] ?? 'General') ?></td>
                            <td style="padding:10px; border-bottom:1px solid var(--glass-border);">
                                <span style="padding:4px 12px; border-radius:50px; font-size:0.75rem; font-weight:600;
                                    background:<?= $att['status'] === 'present' ? 'rgba(100,255,218,0.15)' : ($att['status'] === 'absent' ? 'rgba(255,71,87,0.15)' : 'rgba(255,165,2,0.15)') ?>;
                                    color:<?= $att['status'] === 'present' ? 'var(--highlight)' : ($att['status'] === 'absent' ? '#ff4757' : 'var(--admin-warning, #ffa502)') ?>;">
                                    <?= ucfirst(sanitize($att['status'])) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
