<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title : 'SafeGate Dashboard' ?></title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Modular CSS -->
    <?php 
    // Calculate relative path to assets dynamically based on current script location
    $assets_path = (strpos($_SERVER['SCRIPT_NAME'], 'views/') !== false) ? '../../assets' : 'assets';
    $asset_prefix = (strpos($_SERVER['SCRIPT_NAME'], 'views/') !== false) ? '../../' : '';
    ?>
    <link href="<?= $assets_path ?>/css/global.css" rel="stylesheet">
    <link href="<?= $assets_path ?>/css/dashboard.css" rel="stylesheet">

    <!-- Global JS Utils -->
    <script src="<?= $assets_path ?>/js/utils.js"></script>
    <script defer src="<?= $assets_path ?>/js/dashboard-interactions.js"></script>

    <!-- Iconify -->
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

    <style>
    /* Global dashboard mobile responsiveness fixes */
    @media (max-width: 991.98px) {
        /* Force all layout grids to stack vertically (1 column) */
        .sg-page-overview .sg-overview-grid,
        .sg-page-sell_ticket .sg-auction-grid,
        .sg-page-wallet .sg-wallet-grid,
        .sg-page-wallet .sg-wallet-balance-grid,
        .sg-page-settings .sg-settings-grid,
        .sg-page-active_listings .sg-active-grid,
        .sg-page-transaction .sg-transaction-grid,
        .sg-overview-grid,
        .sg-auction-grid,
        .sg-wallet-grid,
        .sg-wallet-balance-grid,
        .sg-settings-grid,
        .sg-active-grid,
        .sg-metric-grid {
            grid-template-columns: 1fr !important;
            gap: 24px !important;
        }

        /* Force sidebar and main layout wrapper to stack vertically */
        .sg-dashboard-frame {
            display: block !important;
        }

        /* Ensure panels take full width and don't squeeze horizontally */
        .sg-panel, 
        .sg-chart-panel, 
        .sg-ops-panel, 
        .sg-alert-panel, 
        .sg-auction-panel,
        .sg-side-stack,
        .sg-auction-stack,
        .sg-auction-aside {
            width: 100% !important;
            max-width: 100% !important;
        }

        /* Force page wrapper section to have left/right margins and adjust size appropriately */
        .sg-vendor-page,
        .sg-wallet-page,
        .sg-overview-page,
        .sg-settings-page,
        .sg-active-page,
        .sg-auction-page,
        .sg-transaction-page,
        .sg-page-wallet .sg-wallet-page,
        .sg-page-overview .sg-overview-page,
        .sg-page-sell_ticket .sg-auction-page,
        .sg-page-settings .sg-settings-page,
        .sg-page-active_listings .sg-active-page,
        .sg-page-transaction .sg-transaction-page {
            width: calc(100% - 32px) !important;
            margin-left: auto !important;
            margin-right: auto !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        /* Transaction Page Mobile Overrides */
        .sg-page-transaction .sg-page-hero {
            display: flex !important;
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 20px !important;
            margin-bottom: 24px !important;
        }

        .sg-page-transaction .sg-transaction-hero h1 {
            font-size: clamp(32px, 8vw, 44px) !important;
            line-height: 1.1 !important;
            white-space: normal !important;
        }

        .sg-page-transaction .sg-transaction-hero h1 br {
            display: none !important;
        }

        .sg-page-transaction .sg-eyebrow {
            margin-bottom: 8px !important;
        }

        .sg-page-transaction .sg-ledger-total {
            width: 100% !important;
            margin-top: 0 !important;
            grid-template-columns: 1fr !important;
            padding: 16px 20px !important;
            gap: 16px !important;
            min-height: auto !important;
        }

        .sg-page-transaction .sg-ledger-total div+div {
            border-left: none !important;
            border-top: 1px solid rgba(255, 255, 255, 0.09) !important;
            padding-left: 0 !important;
            padding-top: 16px !important;
        }

        .sg-page-transaction .sg-filter-bar {
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
            gap: 12px !important;
            padding: 12px !important;
            margin: 20px 0 !important;
        }

        .sg-page-transaction .sg-filter-bar > .sg-search-field,
        .sg-page-transaction .sg-filter-bar > .sg-select-field {
            grid-column: span 2 !important;
            width: 100% !important;
        }

        .sg-page-transaction .sg-filter-bar > .sg-icon-button {
            grid-column: span 1 !important;
            width: 100% !important;
            min-height: 52px !important;
        }

        .sg-page-transaction .sg-table-head {
            display: none !important;
        }

        .sg-page-transaction .sg-transaction-row {
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
            grid-template-areas: 
                "event event"
                "date type"
                "amount status"
                "actions actions" !important;
            gap: 16px !important;
            padding: 20px 16px !important;
            min-height: auto !important;
            align-items: center !important;
        }

        .sg-page-transaction .sg-transaction-row > :nth-child(1) { grid-area: event; }
        .sg-page-transaction .sg-transaction-row > :nth-child(2) { grid-area: date; }
        .sg-page-transaction .sg-transaction-row > :nth-child(3) { grid-area: type; display: flex; justify-content: flex-end; }
        .sg-page-transaction .sg-transaction-row > :nth-child(4) { grid-area: amount; }
        .sg-page-transaction .sg-transaction-row > :nth-child(5) { grid-area: status; display: flex; justify-content: flex-end; }
        .sg-page-transaction .sg-transaction-row > :nth-child(6) { grid-area: actions; }

        .sg-page-transaction .sg-details-button {
            width: 100% !important;
            min-height: 48px !important;
            font-size: 16px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .sg-page-transaction .sg-details-button br {
            display: none !important;
        }

        .sg-page-transaction .sg-table-footer {
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            gap: 16px !important;
            padding: 20px 16px !important;
            min-height: auto !important;
            text-align: center !important;
        }

        .sg-page-transaction .sg-pagination {
            display: flex !important;
            flex-wrap: wrap !important;
            justify-content: center !important;
            gap: 8px !important;
        }

        /* Active Listings Page Mobile Overrides */
        .sg-page-active_listings .sg-listing-card {
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
            grid-template-areas: 
                "thumb details"
                "price status"
                "actions actions" !important;
            gap: 16px !important;
            padding: 16px !important;
            align-items: center !important;
        }

        .sg-page-active_listings .sg-listing-card .sg-listing-thumb {
            grid-area: thumb;
        }

        .sg-page-active_listings .sg-listing-card > div {
            grid-area: details;
        }

        .sg-page-active_listings .sg-listing-card strong {
            grid-area: price;
        }

        .sg-page-active_listings .sg-listing-card span {
            grid-area: status;
            justify-self: end;
        }

        .sg-page-active_listings .sg-listing-card form,
        .sg-page-active_listings .sg-listing-card a {
            grid-area: actions;
            width: 100% !important;
            display: flex !important;
            gap: 8px !important;
            justify-content: stretch !important;
        }

        .sg-page-active_listings .sg-listing-card form button,
        .sg-page-active_listings .sg-listing-card a {
            flex: 1 1 0% !important;
            min-height: 44px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .sg-page-active_listings .sg-listing-card-empty {
            grid-template-columns: 1fr !important;
            grid-template-areas: none !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            text-align: center !important;
            gap: 12px !important;
        }

        /* Wallet Page Mobile Overrides */
        .sg-page-wallet .sg-withdraw-table {
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch;
        }

        .sg-page-wallet .sg-withdraw-table table {
            width: 100% !important;
            min-width: 500px !important;
        }

        /* Overview Chart Mobile Override */
        .sg-overview-page .sg-sales-chart svg {
            height: auto !important;
            max-height: 240px !important;
        }

        /* General mobile text scaling adjustments */
        .sg-metric-card strong,
        .sg-wallet-balance-card strong {
            font-size: clamp(20px, 5vw, 28px) !important;
        }
    }
    </style>
</head>
<body class="bg-safegate-bg text-white sg-dashboard-shell sg-page-<?= htmlspecialchars($dashboard_page ?? 'dashboard') ?>">

    <div class="sg-dashboard-frame">
        <!-- Sidebar Navigation -->
        <?php 
        $sidebar_path = __DIR__ . '/../components/sidebar_dashboard.php';
        if (file_exists($sidebar_path)) {
            include_once $sidebar_path; 
        }
        ?>

        <!-- Main Dashboard Content -->
        <main class="sg-dashboard-main">
            <?= isset($content) ? $content : '' ?>
        </main>
    </div>

    <?php if (!empty($extra_scripts) && is_array($extra_scripts)): ?>
        <?php foreach ($extra_scripts as $script): ?>
            <script src="<?= $asset_prefix ?? '' ?><?= htmlspecialchars($script) ?>?v=<?= time() ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
