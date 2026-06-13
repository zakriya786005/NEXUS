<?php
declare(strict_types=1);

// NEXUS — Verification Report Generator
// Checks: file existence, asset tags (best-effort), session readiness, DB connectivity,
// security primitives presence, and schema sanity based on database/nexus.sql.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config/database.php';

function h(string $s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

function addItem(array &$arr, string $label, bool $ok, string $detail = ''): void {
    $arr[] = [
        'label' => $label,
        'ok' => $ok,
        'detail' => $detail,
    ];
}

function fileExists(string $path): bool {
    return is_file(__DIR__ . '/' . ltrim($path, '/'));
}

function readFileSafe(string $path): string {
    $full = __DIR__ . '/' . ltrim($path, '/');
    if (!is_file($full)) return '';
    $c = file_get_contents($full);
    return is_string($c) ? $c : '';
}

function phpContains(string $path, string $needleRegex): bool {
    $full = __DIR__ . '/' . ltrim($path, '/');
    if (!is_file($full)) return false;
    $c = file_get_contents($full);
    if (!is_string($c)) return false;
    return preg_match($needleRegex, $c) === 1;
}

$report = [
    'generated_at' => date('c'),
    'base_url' => baseUrl(),
    'file_check' => [],
    'php_check' => [],
    'asset_check' => [],
    'database_check' => [],
    'security_check' => [],
    'final' => [
        'status' => 'UNKNOWN',
        'score' => 0,
        'pass_reasons' => [],
        'fail_reasons' => [],
    ],
];

// ----------------------
// 1) FILE CHECK
// ----------------------
$requiredFiles = [
    // Root user pages
    'index.php','about.php','courses.php','admission.php','contact.php','login.php','register.php','dashboard.php','logout.php','certificates.php','attendance.php','profile.php','certificates.php',

    // Admin pages
    'admin/index.php','admin/dashboard.php','admin/students.php','admin/courses.php','admin/teachers.php','admin/admissions.php','admin/attendance.php','admin/certificates.php','admin/gallery.php','admin/notices.php','admin/messages.php','admin/reports.php','admin/settings.php','admin/logout.php','admin/login.php','admin/contacts.php','admin/blogs.php',

    // Core config/includes
    'config/database.php','config/session.php','config/constants.php','includes/header.php','includes/footer.php','includes/navbar.php','includes/sidebar.php','includes/functions.php','api/login.php','api/register.php','api/admission.php','api/course.php','api/attendance.php','api/certificate.php','api/contact.php','api/student.php','api/teacher.php','api/gallery.php','api/notice.php','api/blog.php',

    // Assets
    'assets/css/style.css','assets/css/admin.css','assets/js/main.js','assets/js/admin.js',

    // Database
    'database/nexus.sql',
];

foreach ($requiredFiles as $rf) {
    $ok = fileExists($rf);
    addItem($report['file_check'], $rf, $ok, $ok ? '' : 'Missing file');
}

// ----------------------
// 2) PHP CHECKS (sessions, includes, JSON endpoints)
// ----------------------
$phpExpect = [
    ['config/database.php', '/function\s+db\(\)/', 'PDO db() helper exists'],
    ['config/database.php', '/function\s+csrfField\(\)/', 'CSRF helper exists'],
    ['config/database.php', '/function\s+verifyCsrfToken\(string\s+\$token\)/', 'CSRF verifier exists'],
    ['login.php', '/csrfField\(\)/', 'Login page includes CSRF field'],
    ['register.php', '/csrfField\(\)/', 'Register page includes CSRF field'],
    ['api/login.php', '/jsonResponse\(\[/', 'API login returns JSON'],
    ['api/register.php', '/jsonResponse\(\[/', 'API register returns JSON'],
];

foreach ($phpExpect as [$path, $regex, $label]) {
    $ok = phpContains($path, $regex);
    addItem($report['php_check'], $label . ' (' . $path . ')', $ok, $ok ? '' : 'Expected pattern not found');
}

// ----------------------
// 3) ASSET CHECK (best-effort scanning of page HTML)
// ----------------------
$pagesToCheckAssets = [
    'index.php','login.php','register.php','contact.php','courses.php','admission.php','dashboard.php','admin/login.php','admin/index.php','admin/students.php','admin/courses.php','admin/attendance.php','admin/gallery.php','admin/notices.php','admin/contacts.php','admin/settings.php'
];

foreach ($pagesToCheckAssets as $p) {
    $content = readFileSafe($p);
    if ($content === '') {
        addItem($report['asset_check'], $p, false, 'Page missing or unreadable');
        continue;
    }

    $cssOk = preg_match('/assets\/css\/.*style\.css/', $content) === 1 || preg_match('/assets\/css\/admin\.css/', $content) === 1 || preg_match('/assets\/css\/style\.css/', $content) === 1;
    $jsOk  = preg_match('/assets\/js\//', $content) === 1;

    addItem(
        $report['asset_check'],
        $p . ' (CSS + JS tags)',
        ($cssOk && $jsOk),
        (!$cssOk ? 'CSS reference missing (best-effort)' : '') . (!$jsOk ? ' JS reference missing (best-effort)' : '')
    );
}

// ----------------------
// 4) DATABASE CHECK (connectivity + basic schema parse)
// ----------------------
$dsnOk = false;
try {
    $pdo = db();
    $stmt = $pdo->query('SELECT 1 as ok');
    $row = $stmt->fetch();
    $dsnOk = ($row && isset($row['ok']));
    addItem($report['database_check'], 'PDO connection + SELECT 1', $dsnOk, $dsnOk ? '' : 'SELECT failed');
} catch (Throwable $e) {
    addItem($report['database_check'], 'PDO connection + SELECT 1', false, $e->getMessage());
}

// Basic check against database/nexus.sql text
$sql = readFileSafe('database/nexus.sql');
$expectedTables = ['admins','students','courses','admissions','teachers','attendance','certificates','gallery','notices','contacts','settings','enrollments'];
foreach ($expectedTables as $t) {
    $ok = $sql !== '' && preg_match('/CREATE TABLE\s+`?' . preg_quote($t, '/') . '`?\s*\(/i', $sql) === 1;
    addItem($report['database_check'], 'Schema contains table: ' . $t, $ok, $ok ? '' : 'Not found in nexus.sql');
}

// ----------------------
// 5) SECURITY CHECK (password hashing, prepared statements, CSRF usage, XSS)
// ----------------------
$securityTests = [
    ['api/register.php', '/password_hash\(/', 'Password hashing in register'],
    ['api/login.php', '/password_verify\(/', 'Password verify in login'],
    ['config/database.php', '/htmlspecialchars\(/', 'XSS escaping helper'],
    ['config/database.php', '/verifyCsrfToken\(/', 'CSRF token verifier'],
];

foreach ($securityTests as [$file,$regex,$label]) {
    $ok = phpContains($file, $regex);
    addItem($report['security_check'], $label . ' (' . $file . ')', $ok, $ok ? '' : 'Pattern not found');
}

// Also scan for any suspicious non-prepared queries in api/* (best-effort)
$apiFiles = array_values(array_filter(scandir(__DIR__ . '/api'), fn($f) => str_ends_with($f, '.php')));
$unsafe = [];
foreach ($apiFiles as $af) {
    $p = 'api/' . $af;
    $content = readFileSafe($p);
    if ($content === '') continue;
    // Heuristic: look for query("SELECT ... ?" without prepare, or direct interpolation with $_POST
    if (preg_match('/db\(\)->query\(\s*".*\$(_POST|_GET|_SESSION)/s', $content) === 1) {
        $unsafe[] = $p;
    }
}
addItem($report['security_check'], 'Prepared-statement safety (heuristic scan)', count($unsafe) === 0, count($unsafe) ? 'Potential unsafe interpolation: ' . implode(', ', $unsafe) : 'No heuristic matches');

// ----------------------
// 6) Final status + score
// ----------------------
$checks = array_merge($report['file_check'], $report['php_check'], $report['asset_check'], $report['database_check'], $report['security_check']);
$total = count($checks);
$okCount = 0;
foreach ($checks as $c) {
    if (!empty($c['ok'])) $okCount++;
}
$score = $total ? (int) round(($okCount / $total) * 100) : 0;

$failReasons = [];
foreach ($checks as $c) {
    if (empty($c['ok'])) {
        $failReasons[] = $c['label'] . (isset($c['detail']) && $c['detail'] ? ' — ' . $c['detail'] : '');
    }
}

$passReasons = [];
foreach ($checks as $c) {
    if (!empty($c['ok'])) {
        $passReasons[] = $c['label'];
    }
}

$finalStatus = $score >= 90 ? '🟢 WEBSITE READY FOR DEPLOYMENT' : ($score >= 70 ? '🟡 PARTIALLY READY (FIX REQUIRED)' : '🔴 NOT READY (CRITICAL FIXES NEEDED)');
$report['final']['status'] = $finalStatus;
$report['final']['score'] = $score;
$report['final']['pass_reasons'] = $passReasons;
$report['final']['fail_reasons'] = array_slice($failReasons, 0, 20);

// ----------------------
// Render report (HTML)
// ----------------------
$sections = [
    ['title' => 'FILE CHECK REPORT', 'itemsKey' => 'file_check'],
    ['title' => 'PHP / SESSION / JSON CHECK', 'itemsKey' => 'php_check'],
    ['title' => 'ASSET CHECK (BEST-EFFORT)', 'itemsKey' => 'asset_check'],
    ['title' => 'DATABASE REPORT', 'itemsKey' => 'database_check'],
    ['title' => 'SECURITY REPORT (HEURISTICS)', 'itemsKey' => 'security_check'],
];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>NEXUS Verification Report</title>
    <link rel="stylesheet" href="<?= h(assetUrl('assets/css/style.css')) ?>">
    <style>
        body{background:#0b1220;color:#e5e7eb;font-family:Inter,system-ui;}
        .wrap{max-width:1100px;margin:24px auto;padding:0 16px;}
        .card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:16px;margin:14px 0;}
        .h{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;}
        .badge{padding:8px 12px;border-radius:999px;border:1px solid rgba(255,255,255,.12);background:rgba(255,255,255,.04);}
        table{width:100%;border-collapse:collapse;}
        td,th{padding:10px 8px;border-bottom:1px solid rgba(255,255,255,.06);font-size:13px;vertical-align:top;}
        th{text-align:left;color:#cbd5e1;}
        .ok{color:#34d399;font-weight:700;}
        .bad{color:#fb7185;font-weight:700;}
        .detail{color:#94a3b8;font-size:12px;white-space:pre-wrap;}
        .small{color:#94a3b8;font-size:12px;}
        .footer{margin-top:18px;color:#94a3b8;font-size:12px;}
        a{color:#60a5fa;}
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <div class="h">
            <div>
                <h2 style="margin:0 0 6px 0;">NEXUS COMPUTER INSTITUTE — Verification Report</h2>
                <div class="small">Generated at: <?= h($report['generated_at']) ?></div>
            </div>
            <div class="badge" style="font-weight:800;">Score: <?= (int)$report['final']['score'] ?> / 100</div>
        </div>
        <div style="margin-top:10px;font-size:18px;font-weight:900;"><?= h($report['final']['status']) ?></div>
        <div class="footer">Base URL: <span style="font-family:ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace;"><?= h($report['base_url']) ?></span></div>
    </div>

    <?php foreach ($sections as $sec): ?>
        <div class="card">
            <h3 style="margin:0 0 10px 0;"><?= h($sec['title']) ?></h3>
            <table>
                <thead>
                    <tr>
                        <th style="width:45%;">Check</th>
                        <th style="width:12%;">Status</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach (($report[$sec['itemsKey']] ?? []) as $item): ?>
                    <tr>
                        <td><?= h((string)$item['label']) ?></td>
                        <td class="<?= !empty($item['ok']) ? 'ok' : 'bad' ?>"><?= !empty($item['ok']) ? '✔ OK' : '✖ FAIL' ?></td>
                        <td class="detail"><?= h((string)($item['detail'] ?? '')) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endforeach; ?>

    <div class="card">
        <h3 style="margin:0 0 10px 0;">FINAL STATUS (Top Failures)</h3>
        <?php if (!empty($report['final']['fail_reasons'])): ?>
            <ul style="margin:0;padding-left:18px;">
                <?php foreach ($report['final']['fail_reasons'] as $r): ?>
                    <li class="detail"><?= h($r) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <div class="ok" style="font-size:14px;">No failures detected by automated checks.</div>
        <?php endif; ?>
        <div class="footer">Note: This verification is code/schema heuristic + connectivity. Functional CRUD testing requires runtime interaction with forms and API calls.</div>
    </div>

</div>
</body>
</html>

