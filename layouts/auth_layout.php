<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title : 'SafeGate Authentication' ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="assets/css/global.css?v=<?= time() ?>" rel="stylesheet">
    <link href="assets/css/auth.css?v=<?= time() ?>" rel="stylesheet">

    <!-- Global JS Utils -->
    <script src="assets/js/utils.js"></script>
</head>

<body>

    <!-- Bagian Auth Layout sengaja dibuat minimalis tanpa header navigasi dan footer kompleks -->

    <!-- Render the content captured from the view -->
    <?= isset($content) ? $content : '' ?>

    <!-- Custom Animation Script for Auth Pages -->
    <script src="assets/js/auth_animation.js"></script>
</body>

</html>