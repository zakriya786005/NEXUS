<?php
declare(strict_types=1);

$currentPage = 'testimonials';
$pageTitle = 'Testimonials - NEXUS COMPUTER INSTITUTE';
$pageDesc = 'Hear what our students say about learning at NEXUS.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<section class="page-header">
    <h1 data-aos="fade-up">Student <span>Testimonials</span></h1>
    <div class="breadcrumb" data-aos="fade-up" data-aos-delay="200">
        <a href="<?= assetUrl('index.php') ?>">Home</a>
        <span>/</span>
        <span>Testimonials</span>
    </div>
</section>

<section class="section" style="padding-top:30px;">
    <div class="container">
        <div class="grid-3">
            <?php
            $testimonials = [
                ['name'=>'Ali Raza','rating'=>5,'review'=>'The course content is modern and the guidance is excellent. I gained real skills and confidence.'],
                ['name'=>'Hira Khan','rating'=>5,'review'=>'NEXUS made learning easy and practical. Projects helped me understand concepts deeply.'],
                ['name'=>'Usman Ali','rating'=>5,'review'=>'Instructors are professional and supportive. The job placement support helped a lot.'],
                ['name'=>'Sara Ahmed','rating'=>4,'review'=>'Great learning environment with structured lessons and friendly staff.'],
                ['name'=>'Fatima Zahra','rating'=>5,'review'=>'The AI & ML training was exceptional and up-to-date with industry standards.'],
                ['name'=>'Ahmed Khan','rating'=>4,'review'=>'Web development course helped me build a real portfolio. I’m now working as a developer.'],
            ];
            foreach ($testimonials as $t):
            ?>
                <div class="testimonial-card" data-aos="fade-up">
                    <div class="star-rating" style="margin-bottom:8px;">
                        <?php for($i=1;$i<=5;$i++): ?>
                            <i class="fas fa-star" style="color:<?= $i <= (int)$t['rating'] ? '#FFD700' : 'rgba(255,255,255,.25)' ?>;"></i>
                        <?php endfor; ?>
                    </div>
                    <p class="quote-text" style="color:var(--text-muted); line-height:1.8;">“<?= htmlspecialchars((string)$t['review'], ENT_QUOTES, 'UTF-8') ?>”</p>
                    <div class="quote-author" style="margin-top:14px;">
                        <div class="avatar" style="width:42px;height:42px;border-radius:14px;background:rgba(0,212,255,.12);border:1px solid rgba(0,212,255,.22);display:flex;align-items:center;justify-content:center;font-weight:900;"><?= strtoupper(substr((string)$t['name'],0,2)) ?></div>
                        <div class="author-info">
                            <h4 style="margin:0; font-size:15px; font-weight:900;"><?= htmlspecialchars((string)$t['name'], ENT_QUOTES, 'UTF-8') ?></h4>
                            <span style="color:var(--text-muted); font-size:12px;">Verified Student</span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

