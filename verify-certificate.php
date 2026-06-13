<?php
declare(strict_types=1);

$currentPage = 'certificates';
$pageTitle = 'Verify Certificate - NEXUS COMPUTER INSTITUTE';
$pageDesc = 'Verify NEXUS certificates using a Certificate ID.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<section class="page-header">
    <h1 data-aos="fade-up">Verify <span>Your Certificate</span></h1>
    <div class="breadcrumb" data-aos="fade-up" data-aos-delay="200">
        <a href="<?= assetUrl('index.php') ?>">Home</a>
        <span>/</span>
        <span>Verify Certificate</span>
    </div>
</section>

<section class="section" style="padding-top:30px;">
    <div class="container">
        <div class="grid-2" style="align-items:start;">
            <div class="glass-card" data-aos="fade-right">
                <h2 style="margin:0 0 10px;"><i class="fas fa-qrcode" style="color:var(--accent);"></i> Certificate Verification</h2>
                <p style="color:var(--text-muted); line-height:1.8; margin:0 0 18px;">
                    Paste your <b>Certificate ID</b> below to check authenticity.
                </p>

                <div id="verify-alert"></div>

                <form id="verifyForm" action="verify.php" method="get">
                    <div class="form-group">
                        <label>Certificate ID</label>
                        <input type="text" name="cert" class="form-control" placeholder="e.g. CERT-2026-XXXX" required>
                    </div>

                    <button type="submit" class="btn btn-primary" style="margin-top:16px; width:100%;">
                        <i class="fas fa-check-circle"></i> Verify Certificate
                    </button>
                </form>

                <div class="muted" style="margin-top:14px; font-size:13px;">
                    For QR verification, scan the QR printed on your certificate. It will open the verify page automatically.
                </div>
            </div>

            <div class="glass-card" data-aos="fade-left">
                <h3 style="margin:0 0 10px;">Quick Help</h3>
                <ul style="margin:0; padding-left:18px; color:var(--text-muted); line-height:1.9;">
                    <li>Certificate ID is shown on your certificate document.</li>
                    <li>Status will display as <b>VALID</b> or <b>INVALID</b>.</li>
                    <li>If invalid, contact NEXUS Computer Institute for correction.</li>
                </ul>

                <div style="margin-top:18px; padding:14px; border-radius:14px; border:1px solid rgba(255,255,255,.10); background:rgba(255,255,255,.04);">
                    <h4 style="margin:0 0 8px;">Need Assistance?</h4>
                    <div style="color:var(--text-muted); line-height:1.8;">
                        Call: <a href="tel:03001234567" style="color:var(--accent); font-weight:800; text-decoration:none;">0300-1234567</a>
                        <br>
                        Email: <a href="mailto:info@nexusinstitute.pk" style="color:var(--accent); font-weight:800; text-decoration:none;">info@nexusinstitute.pk</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

