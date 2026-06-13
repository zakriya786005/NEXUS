<?php
declare(strict_types=1);

$currentPage = 'gallery';
$pageTitle = 'Gallery - NEXUS COMPUTER INSTITUTE';
$pageDesc = 'Explore our campus gallery and events at NEXUS.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

$images = [];
try {
    $stmt = db()->query("SELECT * FROM gallery WHERE status='active' ORDER BY created_at DESC");
    $images = $stmt->fetchAll();
} catch (Throwable $e) {}
?>

<section class="page-header">
    <h1 data-aos="fade-up">Campus <span>Gallery</span></h1>
    <div class="breadcrumb" data-aos="fade-up" data-aos-delay="200">
        <a href="<?= assetUrl('index.php') ?>">Home</a>
        <span>/</span>
        <span>Gallery</span>
    </div>
</section>

<section class="section" style="padding-top:30px;">
    <div class="container">
        <div class="filter-buttons" data-aos="fade-up">
            <button class="filter-btn active" data-filter="all">All</button>
            <?php
            $cats = ['campus','events','workshops','seminars','activities'];
            foreach ($cats as $c):
            ?>
                <button class="filter-btn" data-filter="<?= htmlspecialchars($c, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars(ucfirst($c), ENT_QUOTES, 'UTF-8') ?></button>
            <?php endforeach; ?>
        </div>

        <div class="gallery-grid" style="margin-top:18px;">
            <?php if (empty($images)): ?>
                <div class="glass-card" style="grid-column:1/-1; padding:30px; text-align:center;" data-aos="fade-up">
                    <i class="fas fa-image" style="font-size:2rem; color:var(--accent); opacity:.7;"></i>
                    <h3 style="margin:10px 0 6px;">Gallery Coming Soon</h3>
                    <p style="color:var(--text-muted); margin:0;">New gallery updates will appear here.</p>
                </div>
            <?php else: ?>
                <?php foreach ($images as $g): ?>
                    <div class="gallery-item" data-category="<?= htmlspecialchars((string)($g['category'] ?? 'campus'), ENT_QUOTES, 'UTF-8') ?>" data-aos="fade-up">
                        <img src="<?= assetUrl('uploads/gallery/' . $g['image']) ?>" alt="<?= htmlspecialchars((string)($g['title'] ?? 'Gallery image'), ENT_QUOTES, 'UTF-8') ?>" loading="lazy">
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

