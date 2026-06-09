<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title : 'SafeGate' ?></title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <?php
    // Calculate relative path to assets dynamically based on current script location
    $assets_path = (strpos($_SERVER['SCRIPT_NAME'], 'views/') !== false) ? '../../assets' : 'assets';
    ?>
    <link href="<?= $assets_path ?>/css/global.css" rel="stylesheet">
    <link href="<?= $assets_path ?>/css/public.css" rel="stylesheet">

    <!-- Global JS Utils -->
    <script src="<?= $assets_path ?>/js/utils.js"></script>

    <!-- Iconify -->
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
</head>

<body class="bg-safegate-bg text-white">

    <?php include_once __DIR__ . '/../components/header.php'; ?>

    <!-- Render the content captured from the view -->
    <?= isset($content) ? $content : '' ?>

    <?php include_once __DIR__ . '/../components/footer.php'; ?>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>