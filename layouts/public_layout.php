<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title : 'SafeGate' ?></title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'safegate-neon': '#D9FF00',
                        'safegate-bg': '#090B10',
                        'safegate-surface': '#12161F',
                        'safegate-success': '#00FFA3',
                        'safegate-danger': '#FF4C4C',
                        'safegate-text-sec': '#8E95A3'
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="bg-safegate-bg text-white font-sans antialiased selection:bg-safegate-neon selection:text-black">
    
    <?php include_once __DIR__ . '/../components/header.php'; ?>

    <!-- Render the content captured from the view -->
    <?= isset($content) ? $content : '' ?>

    <?php include_once __DIR__ . '/../components/footer.php'; ?>

</body>
</html>
