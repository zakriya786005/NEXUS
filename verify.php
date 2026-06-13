<?php
declare(strict_types=1);

// NEXUS — Certificate Verification Endpoint
// Usage:
//   verify.php?cert=<certificate_id>
// Returns HTML view. If Accept: application/json, returns JSON.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config/database.php';

function h(string $s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

function respondJson(array $payload, int $statusCode = 200): never {
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload);
    exit;
}

function isJsonRequest(): bool {
    $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
    return stripos($accept, 'application/json') !== false;
}

function redirectHome(): never {
    header('Location: index.php');
    exit;
}

$certIdRaw = $_GET['cert'] ?? '';
$certId = is_string($certIdRaw) ? trim($certIdRaw) : '';

if ($certId === '') {
    if (isJsonRequest()) {
        respondJson(['ok' => false, 'error' => 'Missing cert parameter'], 400);
    }

    http_response_code(400);
    ?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Certificate Verification</title>
        <link rel="stylesheet" href="<?= h(assetUrl('assets/css/style.css')) ?>">
        <style>
            body{background:#0b1220;color:#e5e7eb;font-family:Inter,system-ui;}
            .wrap{max-width:900px;margin:80px auto;padding:0 16px;}
            .card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:20px;}
            .bad{color:#fb7185;font-weight:800;}
            a{color:#60a5fa;}
        </style>
    </head>
    <body>
        <div class="wrap">
            <div class="card">
                <h2 class="bad">Invalid request</h2>
                <p>Missing <code>cert</code> parameter.</p>
                <p><a href="index.php">Go to home</a></p>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

try {
    $stmt = db()->prepare(
        "SELECT cert.*, c.title AS course_title
         FROM certificates cert
         JOIN courses c ON cert.course_id = c.id
         WHERE cert.certificate_id = ?"
    );
    $stmt->execute([$certId]);
    $certificate = $stmt->fetch();

    if (!$certificate) {
        $payload = ['ok' => false, 'status' => 'not_found', 'certificate_id' => $certId];
        if (isJsonRequest()) respondJson($payload, 404);

        http_response_code(404);
        ?>
        <!doctype html>
        <html lang="en">
        <head>
            <meta charset="utf-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1" />
            <title>Certificate Verification</title>
            <link rel="stylesheet" href="<?= h(assetUrl('assets/css/style.css')) ?>">
            <style>
                body{background:#0b1220;color:#e5e7eb;font-family:Inter,system-ui;}
                .wrap{max-width:900px;margin:80px auto;padding:0 16px;}
                .card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:20px;}
                .bad{color:#fb7185;font-weight:800;}
                a{color:#60a5fa;}
                code{background:rgba(255,255,255,.06);padding:2px 6px;border-radius:8px;}
            </style>
        </head>
        <body>
            <div class="wrap">
                <div class="card">
                    <h2 class="bad">Certificate not found</h2>
                    <p>Certificate ID: <code><?= h($certId) ?></code></p>
                    <p><a href="index.php">Go to home</a></p>
                </div>
            </div>
        </body>
        </html>
        <?php
        exit;
    }

    $status = (string)($certificate['status'] ?? 'inactive');
    $isValid = $status === 'active';

    $payload = [
        'ok' => true,
        'status' => $status,
        'is_valid' => $isValid,
        'certificate_id' => $certificate['certificate_id'] ?? null,
        'student_name' => $certificate['student_name'] ?? null,
        'course_name' => $certificate['course_name'] ?? ($certificate['course_title'] ?? null),
        'issue_date' => $certificate['issue_date'] ?? null,
        'grade' => $certificate['grade'] ?? null,
        'percentage' => $certificate['percentage'] ?? null,
        'verification_url' => baseUrl() . 'verify.php?cert=' . urlencode((string)($certificate['certificate_id'] ?? '')),
    ];

    if (isJsonRequest()) {
        respondJson($payload, 200);
    }

    $badgeClass = $isValid ? 'ok' : 'bad';
    $badgeText = $isValid ? 'VALID' : (strtolower($status) === 'revoked' ? 'REVOKED' : 'INVALID');

    ?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Certificate Verification</title>
        <link rel="stylesheet" href="<?= h(assetUrl('assets/css/style.css')) ?>">
        <style>
            body{background:#0b1220;color:#e5e7eb;font-family:Inter,system-ui;}
            .wrap{max-width:900px;margin:60px auto;padding:0 16px;}
            .card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:20px;}
            .ok{color:#34d399;font-weight:900;}
            .bad{color:#fb7185;font-weight:900;}
            table{width:100%;border-collapse:collapse;margin-top:14px;}
            td{padding:10px 8px;border-bottom:1px solid rgba(255,255,255,.06);vertical-align:top;}
            th{text-align:left;color:#cbd5e1;padding:10px 8px;border-bottom:1px solid rgba(255,255,255,.06);font-size:13px;}
            .badge{display:inline-block;padding:8px 12px;border-radius:999px;border:1px solid rgba(255,255,255,.12);background:rgba(255,255,255,.04);margin-top:10px;}
            .grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:16px;}
            @media (max-width:720px){.grid{grid-template-columns:1fr;}}
            code{background:rgba(255,255,255,.06);padding:2px 6px;border-radius:8px;}
        </style>
    </head>
    <body>
        <div class="wrap">
            <div class="card">
                <h1 style="margin:0;">Certificate Verification</h1>
                <div class="badge">
                    <span class="<?= h($badgeClass) ?>" style="font-size:18px;"><?= h($badgeText) ?></span>
                </div>

                <div class="grid">
                    <div>
                        <h3 style="margin:18px 0 8px;">Certificate Details</h3>
                        <table>
                            <tr><th>Certificate ID</th><td><code><?= h((string)$payload['certificate_id']) ?></code></td></tr>
                            <tr><th>Student Name</th><td><?= h((string)$payload['student_name']) ?></td></tr>
                            <tr><th>Course</th><td><?= h((string)$payload['course_name']) ?></td></tr>
                            <tr><th>Issue Date</th><td><?= h((string)$payload['issue_date']) ?></td></tr>
                            <?php if (!empty($payload['grade'])): ?>
                            <tr><th>Grade</th><td><?= h((string)$payload['grade']) ?></td></tr>
                            <?php endif; ?>
                            <?php if (!empty($payload['percentage'])): ?>
                            <tr><th>Percentage</th><td><?= h((string)$payload['percentage']) ?></td></tr>
                            <?php endif; ?>
                        </table>
                    </div>

                    <div>
                        <h3 style="margin:18px 0 8px;">Verification Reference</h3>
                        <p style="color:#94a3b8; margin:0 0 10px;">Scan this QR token or use this URL to verify again:</p>
                        <p><a href="<?= h($payload['verification_url']) ?>" target="_blank">Verify again</a></p>
                        <?php if (!empty($certificate['qr_code'])): ?>
                            <div style="margin-top:14px;">
                                <h4 style="margin:0 0 8px;color:#cbd5e1;">QR on record</h4>
                                <img
                                    src="<?= h(assetUrl('uploads/certificates/' . $certificate['qr_code'])) ?>"
                                    alt="Certificate QR"
                                    style="max-width:260px;width:100%;border-radius:12px;border:1px solid rgba(255,255,255,.1);background:#fff;"
                                >
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (!$isValid): ?>
                    <p style="margin-top:16px;color:#fb7185;font-weight:700;">This certificate is not active.</p>
                <?php endif; ?>

                <p style="margin-top:18px;color:#94a3b8;font-size:13px;">
                    If you believe this is incorrect, contact NEXUS Computer Institute.
                </p>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;

} catch (Throwable $e) {
    if (isJsonRequest()) {
        respondJson(['ok' => false, 'error' => 'Server error', 'detail' => $e->getMessage()], 500);
    }

    http_response_code(500);
    ?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Certificate Verification</title>
        <link rel="stylesheet" href="<?= h(assetUrl('assets/css/style.css')) ?>">
        <style>
            body{background:#0b1220;color:#e5e7eb;font-family:Inter,system-ui;}
            .wrap{max-width:900px;margin:80px auto;padding:0 16px;}
            .card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:20px;}
            .bad{color:#fb7185;font-weight:900;}
        </style>
    </head>
    <body>
        <div class="wrap">
            <div class="card">
                <h2 class="bad">Verification failed</h2>
                <p><?= h($e->getMessage()) ?></p>
                <p>Please try again later.</p>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

