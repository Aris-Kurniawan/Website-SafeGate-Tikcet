<?php
// Router / Entry Point Utama
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

$routes = [
    'home' => __DIR__ . '/views/public/home.php',
    'cara_kerja' => __DIR__ . '/views/public/cara_kerja.php',
    'penjualan' => __DIR__ . '/views/public/penjualan.php',
    'detail_tiket' => __DIR__ . '/views/public/detail_tiket.php',
    'pembayaran' => __DIR__ . '/views/public/pembayaran.php',
    'login' => __DIR__ . '/views/public/login.php',
    'signup' => __DIR__ . '/views/public/signup.php',
    'seller_overview' => __DIR__ . '/views/seller/overview.php',
    'sell_ticket' => __DIR__ . '/views/seller/sell_ticket.php',
    'active_listings' => __DIR__ . '/views/seller/active_listings.php',
    'wallet' => __DIR__ . '/views/seller/wallet.php',
    'transaction' => __DIR__ . '/views/seller/transaction.php',
    'settings' => __DIR__ . '/views/seller/settings.php',
];

if (!array_key_exists($page, $routes)) {
    http_response_code(404);
    $page = 'home';
}

require $routes[$page];
