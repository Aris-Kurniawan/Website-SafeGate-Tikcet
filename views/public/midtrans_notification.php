<?php
// views/public/midtrans_notification.php
// Webhook endpoint untuk menerima notifikasi HTTP POST dari Midtrans.

header('Content-Type: application/json');

// Mencegah output rendering layout HTML
ob_start();

require_once __DIR__ . '/../../core/safegate_repository.php';

if (!defined('SG_MIDTRANS_SERVER_KEY')) {
    ob_end_clean();
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Midtrans server key is not configured']);
    exit;
}

// Konfigurasi Midtrans
\Midtrans\Config::$serverKey = SG_MIDTRANS_SERVER_KEY;
\Midtrans\Config::$isProduction = SG_MIDTRANS_IS_PRODUCTION;

try {
    // Membaca input payload notifikasi dan memverifikasinya secara aman ke Midtrans API
    $notif = new \Midtrans\Notification();
    
    $orderId = $notif->order_id;
    $transactionStatus = $notif->transaction_status;
    
    // Cari detail transaksi berdasarkan transaction_code di DB
    $transaction = sg_fetch_one('SELECT * FROM transactions WHERE transaction_code = :code LIMIT 1', ['code' => $orderId]);
    
    if (!$transaction) {
        ob_end_clean();
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Transaction not found for code: ' . $orderId]);
        exit;
    }
    
    // Update status transaksi & listing di DB sesuai status dari Midtrans
    sg_update_midtrans_transaction($transaction, $transactionStatus);
    
    ob_end_clean();
    echo json_encode([
        'status' => 'success',
        'message' => 'Notification handled successfully',
        'order_id' => $orderId,
        'status_updated_to' => $transactionStatus
    ]);
    exit;
} catch (\Throwable $e) {
    ob_end_clean();
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Error processing notification: ' . $e->getMessage()
    ]);
    exit;
}
