<?php
declare(strict_types=1);

$currentPage = 'certificates';
$pageTitle = 'Certificates - NEXUS COMPUTER INSTITUTE';
$pageDesc = 'Verify certificates issued by NEXUS Computer Institute.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

$certificates = [];
try {
    $stmt = db()->query("SELECT * FROM certificates WHERE status='active' ORDER BY created_at DESC LIMIT 6");
    $certificates = $stmt->fetchAll();
} catch (Throwable $e) {}

$studentMaybe = null;
?>

<section class="page-header">
    <h1 data-aos="fade-up">Certificate <span>Verification</span></h1>
    <div class="breadcrumb" data-aos="fade-up" data-aos-delay="200">
        <a href="<?= assetUrl('index.php') ?>">Home</a>
        <span>/</span>
        <span>Certificates</span>
    </div>
</section>

<section class="section" style="padding-top:30px;">
    <div class="container">
        <div class="grid-2">
            <div class="glass-card" data-aos="fade-right">
                <h2 style="margin-bottom:8px;"><i class="fas fa-qrcode" style="color:var(--accent);"></i> Verify Your Certificate</h2>
                <p style="color:var(--text-muted); margin:0 0 18px; line-height:1.8;">
                    Enter your <b>Certificate ID</b> to verify authenticity instantly.
                </p>

                <form action="verify.php" method="GET" onsubmit="return true;">
                    <input type="hidden" name="cert" value="">
                    <div class="form-group">
                        <label>Certificate ID</label>
                        <input type="text" name="cert" class="form-control" placeholder="e.g. CERT-2026-XXXX" required>
                    </div>
                    <button class="btn btn-primary" type="submit" style="margin-top:14px; width:100%;">
                        <i class="fas fa-check-circle"></i> Verify Certificate
                    </button>
                </form>

                <div class="muted" style="margin-top:12px; font-size:13px; color:var(--text-muted);">
                    Tip: You can also scan the QR code printed on your certificate.
                </div>
            </div>

            <div class="glass-card" data-aos="fade-left">
                <h3 style="margin-bottom:14px;">Recently Issued</h3>
                <?php if (empty($certificates)): ?>
                    <p style="color:var(--text-muted); margin:0;">No active certificates found.</p>
                <?php else: ?>
                    <div style="display:flex; flex-direction:column; gap:10px;">
                        <?php foreach ($certificates as $c): ?>
                            <div style="display:flex; justify-content:space-between; gap:12px; align-items:flex-start; padding:10px 12px; border:1px solid var(--glass-border); border-radius:14px; background:rgba(255,255,255,.03);">
                                <div>
                                    <div style="font-weight:900;">#<?= htmlspecialchars((string)($c['certificate_id'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                                    <div style="color:var(--text-muted); font-size:13px; margin-top:4px;">
                                        <?= htmlspecialchars((string)($c['course_name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                                    </div>
                                </div>
                                <a class="btn btn-light" href="<?= assetUrl('verify.php?cert=' . urlencode((string)($c['certificate_id'] ?? ''))) ?>" target="_blank">
                                    Verify
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

