<?php
declare(strict_types=1);

$currentPage = 'teachers';
$pageTitle = 'Teachers - NEXUS COMPUTER INSTITUTE';
$pageDesc = 'Meet our expert instructors at NEXUS Computer Institute.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

$teachers = [];
try {
    $stmt = db()->query("SELECT * FROM teachers WHERE status='active' ORDER BY name ASC");
    $teachers = $stmt->fetchAll();
} catch (Throwable $e) {}

$imgFallback = assetUrl('assets/images/default-teacher.png');
?>

<section class="page-header">
    <h1 data-aos="fade-up">Meet Our <span>Teachers</span></h1>
    <div class="breadcrumb" data-aos="fade-up" data-aos-delay="200">
        <a href="<?= assetUrl('index.php') ?>">Home</a>
        <span>/</span>
        <span>Teachers</span>
    </div>
</section>

<section class="section" style="padding-top:30px;">
    <div class="container">
        <div class="grid-3">
            <?php if (empty($teachers)): ?>
                <div class="glass-card" style="grid-column:1/-1; text-align:center; padding:30px;" data-aos="fade-up">
                    <i class="fas fa-users" style="font-size:2rem; color:var(--accent); opacity:.7;"></i>
                    <h3 style="margin:10px 0 6px;">Teachers Coming Soon</h3>
                    <p style="color:var(--text-muted); margin:0;">Our instructor profiles are being updated. Please check back later.</p>
                </div>
            <?php else: ?>
                <?php foreach ($teachers as $t): ?>
                    <div class="teacher-card" data-aos="fade-up">
                        <div class="teacher-image">
                            <?php if (!empty($t['image'])): ?>
                                <img src="<?= assetUrl('uploads/teachers/' . $t['image']) ?>" alt="<?= htmlspecialchars((string)($t['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" loading="lazy"
                                     onerror="this.onerror=null;this.src='<?= $imgFallback ?>';">
                            <?php else: ?>
                                <img src="<?= $imgFallback ?>" alt="Teacher" loading="lazy">
                            <?php endif; ?>
                        </div>
                        <div class="teacher-info">
                            <h3><?= htmlspecialchars((string)($t['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h3>
                            <div class="designation"><?= htmlspecialchars((string)($t['designation'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                            <div class="skills">
                                <?php
                                $dept = (string)($t['department'] ?? '');
                                $exp = (string)($t['experience'] ?? '');
                                echo htmlspecialchars(trim($dept . ' | ' . $exp), ENT_QUOTES, 'UTF-8');
                                ?>
                            </div>
                            <?php if (!empty($t['qualification']) || !empty($t['bio'])): ?>
                                <p class="teacher-bio" style="color:var(--text-muted); margin-top:10px; line-height:1.7;">
                                    <?= htmlspecialchars(trim((string)($t['bio'] ?? '')), ENT_QUOTES, 'UTF-8') ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

