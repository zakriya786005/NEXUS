<?php
declare(strict_types=1);

$currentPage = 'blog';
$pageTitle = 'Blog - NEXUS COMPUTER INSTITUTE';
$pageDesc = 'Read NEXUS updates, learning tips, and institute news.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

$posts = [];
try {
    $stmt = db()->query("SELECT * FROM blogs WHERE status='published' ORDER BY published_at DESC, created_at DESC LIMIT 30");
    $posts = $stmt->fetchAll();
} catch (Throwable $e) {}
?>

<section class="page-header">
    <h1 data-aos="fade-up">Latest <span>Blog</span> Posts</h1>
    <div class="breadcrumb" data-aos="fade-up" data-aos-delay="200">
        <a href="<?= assetUrl('index.php') ?>">Home</a>
        <span>/</span>
        <span>Blog</span>
    </div>
</section>

<section class="section" style="padding-top:30px;">
    <div class="container">
        <div class="grid-3">
            <?php if (empty($posts)): ?>
                <div class="glass-card" style="grid-column:1/-1; text-align:center; padding:30px;" data-aos="fade-up">
                    <i class="fas fa-pen-nib" style="font-size:2rem; color:var(--accent); opacity:.7;"></i>
                    <h3 style="margin:10px 0 6px;">Blog Coming Soon</h3>
                    <p style="color:var(--text-muted); margin:0;">New articles will be published regularly.</p>
                </div>
            <?php else: ?>
                <?php foreach ($posts as $p): ?>
                    <div class="blog-card" data-aos="fade-up">
                        <div class="card-image">
                            <?php if (!empty($p['featured_image'])): ?>
                                <img src="<?= assetUrl('uploads/gallery/' . $p['featured_image']) ?>" alt="<?= htmlspecialchars((string)($p['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" loading="lazy" onerror="this.style.display='none'">
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title"><?= htmlspecialchars((string)($p['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h3>
                            <p class="card-desc"><?= htmlspecialchars((string)($p['excerpt'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
                            <div class="card-meta">
                                <span><i class="fas fa-calendar"></i> <?= htmlspecialchars((string)($p['published_at'] ?? $p['created_at']), ENT_QUOTES, 'UTF-8') ?></span>
                            </div>
                            <a class="card-btn" href="#">
                                <i class="fas fa-arrow-right"></i> Read More
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

