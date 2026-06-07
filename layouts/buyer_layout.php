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
    <script defer src="<?= $assets_path ?>/js/dashboard-interactions.js"></script>
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <style>
    /* Buyer dashboard mobile responsiveness overrides */
    @media (max-width: 860px) {
        /* Force the navigation menu items to stack vertically downwards */
        .sg-buyer-sidebar .sg-buyer-nav {
            display: flex !important;
            flex-direction: column !important;
            gap: 4px !important;
            padding: 12px 16px !important;
        }

        /* Hide all sections of the sidebar when collapsed on mobile */
        .sg-buyer-frame.sg-sidebar-collapsed .sg-seller-card,
        .sg-buyer-frame.sg-sidebar-collapsed .sg-buyer-nav,
        .sg-buyer-frame.sg-sidebar-collapsed .sg-sidebar-footer {
            display: none !important;
        }

        .sg-buyer-sidebar .sg-buyer-nav a {
            width: 100% !important;
            min-height: 48px !important;
            border-radius: 6px !important;
        }

        /* Adjust layout and padding of cards and footers to match the vertical stack */
        .sg-buyer-sidebar .sg-seller-card {
            padding: 16px 20px !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
        }

        .sg-buyer-sidebar .sg-sidebar-footer {
            padding: 12px 16px !important;
            border-top: 1px solid rgba(255, 255, 255, 0.05) !important;
        }
    }
    </style>
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
                    <?php
                    $unread_count = sg_current_user_id() ? sg_unread_notification_count(sg_current_user_id()) : 0;
                    ?>
                    <a href="index.php?page=buyer_dashboard" title="Notifications" style="position: relative;">
                        <iconify-icon icon="ph:bell"></iconify-icon>
                        <?php if ($unread_count > 0): ?>
                            <span style="position: absolute; top: -6px; right: -6px; background: var(--safegate-danger, #FF4C4C); color: #fff; font-size: 8px; font-weight: 800; padding: 2px 4px; border-radius: 50%; line-height: 1; text-align: center; border: 1px solid var(--safegate-bg, #090B10); min-width: 14px;">
                                <?= $unread_count ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </div>
            </header>
            <?= isset($content) ? $content : '' ?>
        </main>
    </div>
</body>
</html>
