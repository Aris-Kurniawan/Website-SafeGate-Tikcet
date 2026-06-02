<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? sg_h($page_title) : 'SafeGate Buyer Portal' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php $assets_path = (strpos($_SERVER['SCRIPT_NAME'], 'views/') !== false) ? '../../assets' : 'assets'; ?>
    <link href="<?= $assets_path ?>/css/global.css" rel="stylesheet">
    <link href="<?= $assets_path ?>/css/dashboard.css" rel="stylesheet">
    <script src="<?= $assets_path ?>/js/utils.js"></script>
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
</head>
<body class="bg-safegate-bg text-white sg-buyer-shell sg-buyer-page-<?= sg_h($buyer_page ?? 'dashboard') ?>">
    <div class="sg-buyer-frame">
        <?php include __DIR__ . '/../components/buyer_sidebar.php'; ?>
        <main class="sg-buyer-main">
            <header class="sg-buyer-topbar">
                <div class="sg-buyer-node"><iconify-icon icon="ph:map-pin"></iconify-icon> Main Net Node #0412</div>
                <div class="sg-buyer-top-actions">
                    <a href="index.php?page=home" title="Home"><iconify-icon icon="ph:house"></iconify-icon></a>
                    <a href="index.php?page=buyer_wallet" title="Wallet"><iconify-icon icon="ph:wallet"></iconify-icon></a>
                </div>
            </header>
            <?= isset($content) ? $content : '' ?>
        </main>
    </div>
</body>
</html>
