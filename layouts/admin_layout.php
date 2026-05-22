<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title : 'SafeGate Admin Command Center' ?></title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <?php 
    // Calculate relative path to assets dynamically based on current script location
    $assets_path = (strpos($_SERVER['SCRIPT_NAME'], 'views/') !== false) ? '../../assets' : 'assets';
    ?>
    <!-- Styled Theme & Base Styles -->
    <link href="<?= $assets_path ?>/css/global.css" rel="stylesheet">
    <link href="<?= $assets_path ?>/css/admin.css" rel="stylesheet">

    <!-- Global JS Utils & Admin Interactions -->
    <script src="<?= $assets_path ?>/js/utils.js"></script>
    <script defer src="<?= $assets_path ?>/js/admin_charts.js"></script>

    <!-- Iconify -->
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
</head>
<body class="sg-admin-shell">
    
    <div class="sg-admin-frame">
        <!-- Sidebar Navigation -->
        <?php include_once __DIR__ . '/../components/admin_sidebar.php'; ?>

        <!-- Main Content Area -->
        <main class="sg-admin-main">
            <?= isset($content) ? $content : '' ?>
        </main>
    </div>

</body>
</html>
