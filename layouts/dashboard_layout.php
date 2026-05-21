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
    <link href="<?= $assets_path ?>/css/global-safegate.css" rel="stylesheet">
    <link href="<?= $assets_path ?>/css/dashboard.css" rel="stylesheet">

    <!-- Global JS Utils -->
    <script src="<?= $assets_path ?>/js/utils.js"></script>
    <script defer src="<?= $assets_path ?>/js/dashboard-interactions.js"></script>

    <!-- Iconify -->
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
</head>
<body class="bg-safegate-bg text-white sg-dashboard-shell sg-page-<?= htmlspecialchars($dashboard_page ?? 'dashboard') ?>">
    
    <!-- Topbar (Optional, can be included from components if extracted) -->
    
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
            <script src="<?= $asset_prefix ?? '' ?><?= htmlspecialchars($script) ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
