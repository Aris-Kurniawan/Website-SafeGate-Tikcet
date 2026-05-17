<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title : 'SafeGate' ?></title>
    <!-- Add your CSS and other head elements here -->
</head>
<body class="bg-gray-900 text-white">
    
    <?php include_once __DIR__ . '/../components/header.php'; ?>

    <!-- Render the content captured from the view -->
    <?= isset($content) ? $content : '' ?>

    <?php include_once __DIR__ . '/../components/footer.php'; ?>

</body>
</html>
