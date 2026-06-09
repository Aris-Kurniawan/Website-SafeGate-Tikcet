<?php
// Router / Entry Point Utama
require_once __DIR__ . '/core/request_handlers.php';

// Pseudo-cronjob: tutup lelang yang expired otomatis pada tiap page load
sg_run_cronjobs();

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

require_once 'config/database.php';

$routes = [
    'home' => __DIR__ . '/views/public/home.php',
    'cara_kerja' => __DIR__ . '/views/public/cara_kerja.php',
    'penjualan' => __DIR__ . '/views/public/penjualan.php',
    'detail_tiket' => __DIR__ . '/views/public/detail_tiket.php',
    'pembayaran' => __DIR__ . '/views/public/pembayaran.php',
    'transaction_detail' => __DIR__ . '/views/public/transaction_detail.php',
    'login' => __DIR__ . '/views/public/login.php',
    'signup' => __DIR__ . '/views/public/signup.php',
    'buyer_dashboard' => __DIR__ . '/views/buyer/dashboard.php',
    'my_tickets' => __DIR__ . '/views/buyer/my_tickets.php',
    'buyer_wallet' => __DIR__ . '/views/buyer/wallet.php',
    'buyer_transactions' => __DIR__ . '/views/buyer/transactions.php',
    'buyer_profile' => __DIR__ . '/views/buyer/profile_settings.php',
    'seller_register' => __DIR__ . '/views/buyer/seller_register.php',
    'ticket_verify' => __DIR__ . '/views/buyer/ticket_verify.php',
    'seller_overview' => __DIR__ . '/views/seller/overview.php',
    'sell_ticket' => __DIR__ . '/views/seller/sell_ticket.php',
    'active_listings' => __DIR__ . '/views/seller/active_listings.php',
    'wallet' => __DIR__ . '/views/seller/wallet.php',
    'transaction' => __DIR__ . '/views/seller/transaction.php',
    'settings' => __DIR__ . '/views/seller/settings.php',
    'admin_overview' => __DIR__ . '/views/admin/overview.php',
    'admin_transactions' => __DIR__ . '/views/admin/transactions.php',
    'admin_disputes' => __DIR__ . '/views/admin/disputes.php',
    'admin_kyc' => __DIR__ . '/views/admin/kyc_center.php',
    'admin_login' => __DIR__ . '/views/admin/login.php',
    'admin_signup' => __DIR__ . '/views/admin/signup.php',
    'midtrans_notification' => __DIR__ . '/views/public/midtrans_notification.php',
];

if (!array_key_exists($page, $routes)) {
    http_response_code(404);
    $page = 'home';
}

sg_require_route_access($page);

require $routes[$page];
