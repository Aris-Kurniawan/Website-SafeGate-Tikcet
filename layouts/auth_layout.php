<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title : 'SafeGate Authentication' ?></title>
    <!-- Add your CSS, Tailwind, and other head elements here -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white font-sans antialiased">
    
    <!-- Bagian Auth Layout sengaja dibuat minimalis tanpa header navigasi dan footer kompleks -->

    <!-- Render the content captured from the view -->
    <?= isset($content) ? $content : '' ?>

</body>
</html>
