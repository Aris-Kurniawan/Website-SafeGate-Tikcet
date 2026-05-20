<?php
$asset_prefix = (strpos($_SERVER['SCRIPT_NAME'], 'views/') !== false) ? '../../' : '';
$page_title = isset($page_title) ? $page_title : 'SafeGate Seller Dashboard';
$dashboard_page = isset($dashboard_page) ? $dashboard_page : '';
$extra_scripts = isset($extra_scripts) ? $extra_scripts : [];
$css_version = file_exists(__DIR__ . '/../assets/css/style.css') ? filemtime(__DIR__ . '/../assets/css/style.css') : time();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= $asset_prefix ?>assets/css/style.css?v=<?= $css_version ?>" rel="stylesheet">
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
</head>
<body class="sg-dashboard-shell">
    <header class="sg-dashboard-topbar">
        <div class="sg-brand-lockup">
            <span class="sg-brand-dot"></span>
            <a href="<?= $asset_prefix ?>index.php?page=home" class="sg-brand-name">SafeGate</a>
            <span class="sg-identity-pill">
                <iconify-icon icon="ph:shield-check-fill"></iconify-icon>
                Seller Identity: Verified
            </span>
        </div>

        <nav class="sg-topnav" aria-label="Seller navigation">
            <a class="<?= $dashboard_page === 'marketplace' ? 'is-active' : '' ?>" href="<?= $asset_prefix ?>index.php?page=penjualan">Marketplace</a>
            <a class="<?= in_array($dashboard_page, ['sell_ticket', 'transaction']) ? 'is-active' : '' ?>" href="<?= $asset_prefix ?>index.php?page=sell_ticket"><?= $dashboard_page === 'transaction' ? 'Sell<br>Tickets' : 'Work Space' ?></a>
        </nav>

        <div class="sg-top-icons">
            <button type="button" aria-label="Notifications"><iconify-icon icon="ph:bell"></iconify-icon></button>
            <button type="button" aria-label="Account"><iconify-icon icon="ph:user-circle"></iconify-icon></button>
        </div>
    </header>

    <div class="sg-dashboard-frame">
        <?php include __DIR__ . '/../components/sidebar_dashboard.php'; ?>
        <main class="sg-dashboard-main">
            <?= isset($content) ? $content : '' ?>
        </main>
    </div>

    <?php foreach ($extra_scripts as $script): ?>
        <?php $script_version = file_exists(__DIR__ . '/../' . $script) ? filemtime(__DIR__ . '/../' . $script) : time(); ?>
        <script src="<?= $asset_prefix . $script ?>?v=<?= $script_version ?>"></script>
    <?php endforeach; ?>
</body>
</html>
