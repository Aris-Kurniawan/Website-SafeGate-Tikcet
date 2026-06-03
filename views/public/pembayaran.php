<?php
require_once __DIR__ . '/../../core/safegate_repository.php';

// 1. Definisikan judul halaman
$page_title = "Pilih Metode Pembayaran - SafeGate";

$payment_result = $_GET['payment_result'] ?? '';
$order_id = $_GET['order_id'] ?? '';

if ($payment_result !== '' && $order_id !== '') {
    $transaction = sg_fetch_one(
        'SELECT t.*, e.title, e.venue, e.city, e.event_date, tl.section, tl.row, tl.seat
         FROM transactions t
         JOIN ticket_listings tl ON tl.id = t.listing_id
         JOIN events e ON e.id = tl.event_id
         WHERE t.transaction_code = :code LIMIT 1',
        ['code' => $order_id]
    );

    if ($transaction) {
        // Jika status di DB masih pending, verifikasi dengan Midtrans status API
        if ($transaction['payment_status'] === 'pending') {
            \Midtrans\Config::$serverKey = SG_MIDTRANS_SERVER_KEY;
            \Midtrans\Config::$isProduction = SG_MIDTRANS_IS_PRODUCTION;
            
            try {
                $status = \Midtrans\Transaction::status($order_id);
                $transaction_status = $status->transaction_status;
                sg_update_midtrans_transaction($transaction, $transaction_status);
                
                // Ambil ulang transaksi terupdate
                $transaction = sg_fetch_one(
                    'SELECT t.*, e.title, e.venue, e.city, e.event_date, tl.section, tl.row, tl.seat
                     FROM transactions t
                     JOIN ticket_listings tl ON tl.id = t.listing_id
                     JOIN events e ON e.id = tl.event_id
                     WHERE t.transaction_code = :code LIMIT 1',
                    ['code' => $order_id]
                );
            } catch (\Throwable $e) {
                // Bypass/Log error jika gagal menghubungi Midtrans
            }
        }
    }
    
    // Tampilkan halaman hasil pembayaran (Success, Pending, atau Gagal)
    $page_title = "Status Pembayaran - SafeGate";
    ob_start();
    ?>
    <section class="container mx-auto py-5" style="max-width: 800px; padding-left: 1.5rem; padding-right: 1.5rem; margin-top: 4rem; margin-bottom: 5rem;">
        <div class="sg-glass rounded-4 p-5 text-center position-relative overflow-hidden">
            <?php if (!$transaction): ?>
                <iconify-icon icon="ph:warning-bold" class="text-danger mb-3" style="font-size: 4.5rem;"></iconify-icon>
                <h1 class="h3 fw-bold text-white mb-2">Transaksi Tidak Ditemukan</h1>
                <p class="text-safegate-text-sec mb-4">Transaksi dengan kode <strong><?= sg_h($order_id) ?></strong> tidak valid.</p>
                <a href="index.php?page=penjualan" class="btn btn-safegate-neon rounded-pill fw-bold px-5 py-2.5">Kembali Ke Marketplace</a>
            <?php else: 
                $paymentStatus = $transaction['payment_status'];
                $eventTitle = sg_h($transaction['title']);
                $totalPaid = sg_rupiah($transaction['total_amount']);
                $section = sg_h($transaction['section']);
                $rowNum = sg_h($transaction['row']);
                $seat = sg_h($transaction['seat']);
                $eventDate = date('d F Y, H:i', strtotime($transaction['event_date']));
                $eventLocation = sg_h($transaction['venue'] . ', ' . $transaction['city']);
            ?>
                <?php if ($paymentStatus === 'paid'): ?>
                    <!-- SUCCESS LAYOUT -->
                    <div class="d-flex align-items-center justify-content-center mx-auto rounded-circle mb-4 bg-safegate-success" style="width: 80px; height: 80px; box-shadow: 0 0 30px rgba(46, 204, 113, 0.4);">
                        <iconify-icon icon="ph:check-bold" class="text-black fs-1 fw-bold"></iconify-icon>
                    </div>
                    <h1 class="h3 fw-bold text-white mb-2">Pembayaran Berhasil!</h1>
                    <p class="text-safegate-text-sec mb-4 mx-auto" style="max-width: 550px;">
                        Selamat! Tiket Anda telah berhasil diamankan. Protokol Escrow SafeGate aktif melindungi dana Anda hingga 24 jam setelah acara selesai.
                    </p>
                    
                    <!-- Ticket Details Box -->
                    <div class="text-start mx-auto p-4 mb-4 rounded-4" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06); max-width: 600px;">
                        <h4 class="text-white fw-bold mb-3 fs-5"><?= $eventTitle ?></h4>
                        <div class="row g-3 text-safegate-text-sec fs-7 mb-3">
                            <div class="col-6">
                                <span class="d-block text-uppercase fw-bold text-white-50" style="font-size: 0.65rem; letter-spacing: 0.05em;">Kode Transaksi</span>
                                <span class="fw-bold text-white"><?= sg_h($order_id) ?></span>
                            </div>
                            <div class="col-6">
                                <span class="d-block text-uppercase fw-bold text-white-50" style="font-size: 0.65rem; letter-spacing: 0.05em;">Total Terbayar</span>
                                <span class="fw-bold text-safegate-neon"><?= $totalPaid ?></span>
                            </div>
                            <div class="col-6">
                                <span class="d-block text-uppercase fw-bold text-white-50" style="font-size: 0.65rem; letter-spacing: 0.05em;">Waktu & Tempat</span>
                                <span><?= $eventDate ?><br><?= $eventLocation ?></span>
                            </div>
                            <div class="col-6">
                                <span class="d-block text-uppercase fw-bold text-white-50" style="font-size: 0.65rem; letter-spacing: 0.05em;">Posisi Kursi</span>
                                <span>Section <?= $section ?>, Row <?= $rowNum ?>, Seat <?= $seat ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                        <a href="index.php?page=my_tickets" class="btn btn-safegate-neon rounded-pill fw-bold px-5 py-2.5 sg-btn-glow">Lihat Tiket Saya</a>
                        <a href="index.php?page=penjualan" class="btn btn-outline-light rounded-pill fw-bold px-4 py-2.5">Kembali ke Beranda</a>
                    </div>
                <?php elseif ($paymentStatus === 'pending'): ?>
                    <!-- PENDING LAYOUT -->
                    <div class="d-flex align-items-center justify-content-center mx-auto rounded-circle mb-4 bg-warning animate-pulse" style="width: 80px; height: 80px; box-shadow: 0 0 30px rgba(241, 196, 15, 0.4);">
                        <iconify-icon icon="ph:clock-bold" class="text-black fs-1 fw-bold"></iconify-icon>
                    </div>
                    <h1 class="h3 fw-bold text-white mb-2">Menunggu Pembayaran</h1>
                    <p class="text-safegate-text-sec mb-4 mx-auto" style="max-width: 550px;">
                        Transaksi Anda telah dibuat. Silakan selesaikan pembayaran Anda sesuai instruksi di Midtrans. Status tiket akan diperbarui secara otomatis setelah pembayaran terverifikasi.
                    </p>
                    
                    <!-- Ticket Details Box -->
                    <div class="text-start mx-auto p-4 mb-4 rounded-4" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06); max-width: 600px;">
                        <h4 class="text-white fw-bold mb-3 fs-5"><?= $eventTitle ?></h4>
                        <div class="row g-3 text-safegate-text-sec fs-7">
                            <div class="col-6">
                                <span class="d-block text-uppercase fw-bold text-white-50" style="font-size: 0.65rem; letter-spacing: 0.05em;">Kode Transaksi</span>
                                <span class="fw-bold text-white"><?= sg_h($order_id) ?></span>
                            </div>
                            <div class="col-6">
                                <span class="d-block text-uppercase fw-bold text-white-50" style="font-size: 0.65rem; letter-spacing: 0.05em;">Jumlah Tagihan</span>
                                <span class="fw-bold text-safegate-neon"><?= $totalPaid ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                        <a href="index.php?page=my_tickets" class="btn btn-safegate-neon rounded-pill fw-bold px-5 py-2.5">Cek Status Tiket</a>
                        <a href="index.php?page=pembayaran&payment_result=pending&order_id=<?= urlencode($order_id) ?>" class="btn btn-outline-light rounded-pill fw-bold px-4 py-2.5">Refresh Status</a>
                    </div>
                <?php else: ?>
                    <!-- FAILED LAYOUT -->
                    <div class="d-flex align-items-center justify-content-center mx-auto rounded-circle mb-4 bg-danger" style="width: 80px; height: 80px; box-shadow: 0 0 30px rgba(231, 76, 60, 0.4);">
                        <iconify-icon icon="ph:x-bold" class="text-white fs-1 fw-bold"></iconify-icon>
                    </div>
                    <h1 class="h3 fw-bold text-white mb-2">Pembayaran Gagal atau Dibatalkan</h1>
                    <p class="text-safegate-text-sec mb-4 mx-auto" style="max-width: 550px;">
                        Mohon maaf, pembayaran Anda tidak dapat diproses atau telah dibatalkan. Silakan lakukan proses pembelian tiket kembali.
                    </p>
                    
                    <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                        <a href="index.php?page=penjualan" class="btn btn-safegate-neon rounded-pill fw-bold px-5 py-2.5">Kembali Ke Marketplace</a>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </section>
    <?php
    $content = ob_get_clean();
    require_once __DIR__ . '/../../layouts/public_layout.php';
    return;
}

// 2. Mulai menangkap output konten (Output Buffering)
ob_start();


$listing_id = isset($_GET['listing_id']) ? (int) $_GET['listing_id'] : 0;
$listing = $listing_id > 0 ? sg_get_listing_detail($listing_id) : null;

if (!$listing) {
?>
<section class="container mx-auto py-5" style="max-width: 900px; padding-left: 1.5rem; padding-right: 1.5rem; margin-top: 4rem; margin-bottom: 5rem;">
    <div class="sg-glass rounded-4 p-5 text-center">
        <iconify-icon icon="ph:credit-card-bold" class="text-safegate-neon mb-3" style="font-size: 3rem;"></iconify-icon>
        <h1 class="h3 fw-bold text-white mb-2">Pembayaran Tidak Bisa Diproses</h1>
        <p class="text-safegate-text-sec mb-4">Pembayaran harus berasal dari listing tiket yang valid di database.</p>
        <a href="index.php?page=penjualan" class="btn btn-safegate-neon rounded-pill fw-bold px-4">Pilih Tiket</a>
    </div>
</section>
<?php
    $content = ob_get_clean();
    require_once __DIR__ . '/../../layouts/public_layout.php';
    return;
}

// Retrieve values from DB first, then GET fallback matching Figma mockup.
$title = $listing ? sg_h($listing['title']) : (isset($_GET['title']) ? htmlspecialchars($_GET['title']) : 'Midnight Symphony Tour');
$image = $listing ? sg_h(sg_event_image($listing['title'], $listing['image_path'] ?? '', $listing['description'] ?? '')) : (isset($_GET['image']) ? htmlspecialchars($_GET['image']) : sg_event_image('Midnight Symphony Tour'));
$date = $listing ? date('d F Y, H:i', strtotime($listing['event_date'])) : (isset($_GET['date']) ? htmlspecialchars($_GET['date']) : '15 Agustus 2024, 20:00 WIB');
$location = $listing ? sg_h($listing['venue'] . ', ' . $listing['city']) : (isset($_GET['location']) ? htmlspecialchars($_GET['location']) : 'Gelora Bung Karno, Jakarta');
$seksi = $listing ? sg_h($listing['section']) : (isset($_GET['seksi']) ? htmlspecialchars($_GET['seksi']) : '102');
$baris = $listing ? sg_h($listing['row']) : (isset($_GET['baris']) ? htmlspecialchars($_GET['baris']) : 'KK');
$kursi = $listing ? sg_h($listing['seat']) : (isset($_GET['kursi']) ? htmlspecialchars($_GET['kursi']) : '14');

// Price parameters
$raw_price = $listing ? (string) ($listing['current_highest_bid'] ?: $listing['starting_bid']) : (isset($_GET['price']) ? $_GET['price'] : '180.000');
$reserve_price = $listing ? (int) ($listing['reserve_price'] ?? 0) : 0;
$current_bid_value = $listing ? (int) ($listing['current_highest_bid'] ?: $listing['starting_bid']) : 0;
$reserve_met = !$listing || $reserve_price <= 0 || $current_bid_value >= $reserve_price;
$current_user_id = sg_current_user_id();
$is_own_listing = $listing && $current_user_id && (int) $listing['seller_id'] === (int) $current_user_id;

if ($is_own_listing) {
?>
<section class="container mx-auto py-5" style="max-width: 900px; padding-left: 1.5rem; padding-right: 1.5rem; margin-top: 4rem; margin-bottom: 5rem;">
    <div class="sg-glass rounded-4 p-5 text-center">
        <iconify-icon icon="ph:storefront-bold" class="text-safegate-neon mb-3" style="font-size: 3rem;"></iconify-icon>
        <h1 class="h3 fw-bold text-white mb-2">Ini Listing Milik Anda</h1>
        <p class="text-safegate-text-sec mb-4">Tiket yang Anda jual tidak bisa dibeli dari akun sendiri. Kelola listing melalui dashboard seller.</p>
        <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
            <a href="index.php?page=active_listings" class="btn btn-safegate-neon rounded-pill fw-bold px-4">Kelola Listing</a>
            <a href="index.php?page=penjualan" class="btn btn-outline-safegate-neon rounded-pill fw-bold px-4">Cari Tiket Lain</a>
        </div>
    </div>
</section>
<?php
    $content = ob_get_clean();
    require_once __DIR__ . '/../../layouts/public_layout.php';
    return;
}

if ($listing && !$reserve_met) {
?>
<section class="container mx-auto py-5" style="max-width: 900px; padding-left: 1.5rem; padding-right: 1.5rem; margin-top: 4rem; margin-bottom: 5rem;">
    <div class="sg-glass rounded-4 p-5 text-center">
        <iconify-icon icon="ph:gavel-bold" class="text-safegate-neon mb-3" style="font-size: 3rem;"></iconify-icon>
        <h1 class="h3 fw-bold text-white mb-2">Reserve Price Belum Tercapai</h1>
        <p class="text-safegate-text-sec mb-4">
            Bid tertinggi saat ini <?= sg_rupiah($current_bid_value) ?>. Checkout baru bisa dilakukan setelah bid mencapai minimal <?= sg_rupiah($reserve_price) ?>.
        </p>
        <a href="index.php?page=detail_tiket&listing_id=<?= (int) $listing_id ?>" class="btn btn-safegate-neon rounded-pill fw-bold px-4">Kembali ke Lelang</a>
    </div>
</section>
<?php
    $content = ob_get_clean();
    require_once __DIR__ . '/../../layouts/public_layout.php';
    return;
}

// Same parsing logic as detail_tiket.php to ensure matching values and currency formats
$is_usdc = false;
$price_val = 180000;
$currency_suffix = "";
$currency_prefix = "Rp. ";

$clean_price = str_replace(['Rp.', 'Rp', ' ', ','], '', $raw_price);
if (strpos($clean_price, '.') !== false) {
    $parts = explode('.', $clean_price);
    if (count($parts) == 2 && strlen($parts[1]) == 3) {
        $price_val = (float)str_replace('.', '', $clean_price);
    } else {
        $price_val = (float)$clean_price;
        if ($price_val < 1000) {
            $price_val = $price_val * 1000;
        }
    }
} else {
    $price_val = (float)$clean_price;
}

// Calculate dynamic breakdown
$service_fee = round($price_val * 0.05);
$escrow_insurance = round($price_val * 0.11);
$total_price = $price_val + $service_fee + $escrow_insurance;

$disp_price = $currency_prefix . number_format($price_val, 0, ',', '.');
$disp_service = $currency_prefix . number_format($service_fee, 0, ',', '.');
$disp_escrow = $currency_prefix . number_format($escrow_insurance, 0, ',', '.');
$disp_total = $currency_prefix . number_format($total_price, 0, ',', '.') . " IDR";
$back_url = $listing
    ? 'index.php?page=detail_tiket&listing_id=' . urlencode((string) $listing_id)
    : 'index.php?page=detail_tiket&title=' . urlencode($title) . '&price=' . urlencode($raw_price) . '&image=' . urlencode($image) . '&date=' . urlencode($date) . '&location=' . urlencode($location) . '&seksi=' . urlencode($seksi) . '&baris=' . urlencode($baris) . '&kursi=' . urlencode($kursi);
?>

<?php if (isset($_GET['snap_token'])): ?>
    <script src="<?= SG_MIDTRANS_IS_PRODUCTION ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' ?>" data-client-key="<?= SG_MIDTRANS_CLIENT_KEY ?>"></script>
<?php endif; ?>

<style>
.sg-payment-selected {
    border: 2px solid var(--safegate-neon) !important;
    background: rgba(217, 255, 0, 0.04) !important;
    box-shadow: 0 0 25px rgba(217, 255, 0, 0.1) !important;
}
.sg-payment-unselected {
    border: 1px solid rgba(255, 255, 255, 0.05) !important;
    background: rgba(18, 22, 31, 0.4) !important;
}
.sg-payment-unselected:hover {
    border-color: rgba(255, 255, 255, 0.15) !important;
    background: rgba(18, 22, 31, 0.6) !important;
    transform: translateY(-2px);
}
</style>

<!-- Payment Method Main Content -->
<section class="container mx-auto py-5" style="max-width: 1200px; padding-left: 1.5rem; padding-right: 1.5rem; margin-top: 2rem; margin-bottom: 5rem;">
    <div class="row g-5">
        
        <!-- Left column: Payment options selection -->
        <div class="col-12 col-lg-7 d-flex flex-column gap-3">
            
            <!-- Back to Event Arrow link -->
            <div>
                <a href="<?= sg_h($back_url) ?>" 
                   class="text-safegate-text-sec hover-neon d-inline-flex align-items-center gap-2 mb-4 text-decoration-none fw-bold text-uppercase" 
                   style="letter-spacing: 0.08em; font-size: 0.75rem;">
                    <iconify-icon icon="ph:arrow-left-bold" class="fs-6"></iconify-icon> KEMBALI KE ACARA
                </a>
            </div>

            <!-- Title -->
            <h1 class="display-5 fw-bold text-white mb-2 letter-spacing-tight">Pilih Metode Pembayaran</h1>
            
            <!-- Security lock label -->
            <div class="d-flex align-items-center gap-2 mb-4 text-safegate-text-sec">
                <iconify-icon icon="ph:lock-keyhole-fill" class="text-safegate-success fs-5"></iconify-icon>
                <span class="fs-7">Semua transaksi dienkripsi dengan standar AES-256.</span>
            </div>

            <!-- List of Modular Payment Options -->
            <div class="sg-payment-options-list">
                <?php
                // 1. Bank Transfer
                $id = 'bank';
                $icon = 'ph:bank-bold';
                $title_opt = 'Transfer Bank';
                $subtitle_opt = 'Virtual Account (BCA, Mandiri, BNI, BRI)';
                $selected = false;
                include __DIR__ . '/../../components/payment_option_card.php';

                // 2. E-Wallet
                $id = 'wallet';
                $icon = 'ph:wallet-bold';
                $title_opt = 'E-Wallet';
                $subtitle_opt = 'DANA, GoPay, OVO, ShopeePay';
                $selected = false;
                include __DIR__ . '/../../components/payment_option_card.php';

                // 3. Crypto Wallet (Selected by default)
                $id = 'crypto';
                $icon = 'ph:currency-btc-bold';
                $title_opt = 'Crypto Wallet';
                $subtitle_opt = 'USDC Network (Polygon / Ethereum)';
                $selected = true;
                include __DIR__ . '/../../components/payment_option_card.php';
                ?>
            </div>

        </div>

        <!-- Right column: Order Summary Card -->
        <div class="col-12 col-lg-5">
            <div class="sticky-top" style="top: 6.5rem; z-index: 10;">
                
                <div class="sg-summary-card">
                    <!-- Section Title -->
                    <h3 class="sg-summary-title" style="letter-spacing: 0.1em; font-size: 0.75rem;">RINGKASAN PESANAN</h3>

                    <!-- Event Name -->
                    <h4 class="text-white fw-bold mb-3 fs-5 letter-spacing-tight"><?= $title ?></h4>

                    <!-- Event Metadata -->
                    <div class="d-flex flex-column gap-2 mb-4 text-safegate-text-sec" style="font-size: 0.8rem;">
                        <div class="d-flex align-items-center gap-2">
                            <iconify-icon icon="ph:calendar-blank" class="text-safegate-neon fs-6"></iconify-icon>
                            <span><?= $date ?></span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <iconify-icon icon="ph:map-pin" class="text-safegate-neon fs-6"></iconify-icon>
                            <span><?= $location ?></span>
                        </div>
                    </div>

                    <!-- Seating Info Box Grid -->
                    <div class="row g-2 py-3 my-4" style="border-top: 1px solid rgba(255, 255, 255, 0.05); border-bottom: 1px solid rgba(255, 255, 255, 0.05); background: rgba(255,255,255,0.01); border-radius: 0.5rem;">
                        <div class="col-4 text-center">
                            <div class="text-safegate-text-sec fw-bold" style="letter-spacing: 0.08em; font-size: 0.65rem;">SEKSI</div>
                            <div class="fw-bold text-white fs-5 mt-1"><?= $seksi ?></div>
                        </div>
                        <div class="col-4 text-center" style="border-left: 1px solid rgba(255, 255, 255, 0.05); border-right: 1px solid rgba(255, 255, 255, 0.05);">
                            <div class="text-safegate-text-sec fw-bold" style="letter-spacing: 0.08em; font-size: 0.65rem;">BARIS</div>
                            <div class="fw-bold text-white fs-5 mt-1"><?= $baris ?></div>
                        </div>
                        <div class="col-4 text-center">
                            <div class="text-safegate-text-sec fw-bold" style="letter-spacing: 0.08em; font-size: 0.65rem;">KURSI</div>
                            <div class="fw-bold text-white fs-5 mt-1"><?= $kursi ?></div>
                        </div>
                    </div>

                    <!-- Breakdown details -->
                    <div class="sg-summary-row mt-4">
                        <span class="sg-summary-label">Harga Tiket Dasar</span>
                        <span class="sg-summary-value"><?= $disp_price ?></span>
                    </div>
                    <div class="sg-summary-row">
                        <span class="sg-summary-label">Biaya Layanan</span>
                        <span class="sg-summary-value"><?= $disp_service ?></span>
                    </div>
                    <div class="sg-summary-row mb-4">
                        <span class="sg-summary-label">Asuransi Escrow *</span>
                        <span class="sg-summary-value"><?= $disp_escrow ?></span>
                    </div>

                    <!-- Total amount box -->
                    <div class="sg-total-box d-flex flex-column align-items-start gap-1">
                        <span class="text-safegate-text-sec fw-bold text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.1em;">TOTAL PEMBAYARAN</span>
                        <div class="sg-total-price"><?= $disp_total ?></div>
                    </div>

                    <!-- Buyer protection escrow badge component -->
                    <div class="mb-4">
                        <?php
                        $escrow_title = "Perlindungan Pembeli";
                        $escrow_text = "Dana Anda ditahan dengan aman di brankas Escrow hingga 24 jam setelah acara selesai.";
                        $is_small = false;
                        include __DIR__ . '/../../components/escrow_badge.php';
                        ?>
                    </div>

                    <form id="paymentForm" action="index.php?page=pembayaran" method="post">
                        <input type="hidden" name="sg_action" value="checkout_payment">
                        <input type="hidden" name="listing_id" value="<?= (int) $listing_id ?>">
                        <input id="paymentMethodInput" type="hidden" name="payment_method" value="usdc">
                    </form>

                    <!-- Action Button -->
                    <button type="button" onclick="executePayment()" class="btn btn-safegate-neon w-100 rounded-pill fw-bold py-3 text-uppercase letter-spacing-wide sg-btn-glow" style="font-size: 0.9rem;">
                        KONFIRMASI & BAYAR SEKARANG
                    </button>
                </div>

            </div>
        </div>

    </div>
</section>

<!-- Success Modal / Popup Overlay -->
<div class="modal fade" id="paymentSuccessModal" tabindex="-1" aria-hidden="true" style="backdrop-filter: blur(8px);">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg text-center p-5" style="background: var(--safegate-surface); border: 1px solid rgba(255,255,255,0.06) !important;">
            <div class="d-flex align-items-center justify-content-center mx-auto rounded-circle mb-4 bg-safegate-neon" style="width: 72px; height: 72px; box-shadow: 0 0 20px rgba(217, 255, 0, 0.45);">
                <iconify-icon icon="ph:check-bold" class="text-black fs-1 fw-bold"></iconify-icon>
            </div>
            <h3 class="h3 fw-bold text-white mb-2">Pembayaran Sukses!</h3>
            <p class="text-safegate-text-sec mb-4 fs-6" style="line-height: 1.5;">
                Selamat! Tiket Anda telah berhasil dikunci dan ditransfer secara aman. Protokol Escrow SafeGate aktif melindungi dana Anda.
            </p>
            <a href="index.php?page=penjualan" class="btn btn-outline-safegate-neon rounded-pill px-5 fw-bold py-2.5">
                KEMBALI KE MARKETPLACE
            </a>
        </div>
    </div>
</div>

<script>
    let activePaymentMethod = 'crypto';

    // Interactive function to switch payment method selections
    function selectPaymentMethod(id) {
        activePaymentMethod = id;
        const methodInput = document.getElementById('paymentMethodInput');
        if (methodInput) {
            methodInput.value = id === 'bank' ? 'bank_transfer' : (id === 'wallet' ? 'dana' : 'usdc');
        }
        const cards = document.querySelectorAll('.sg-payment-option-card');
        
        cards.forEach(card => {
            const cardId = card.getAttribute('data-id');
            const iconBox = card.querySelector('.sg-method-icon-box');
            const radioIndicator = card.querySelector('.sg-radio-indicator');
            
            // Remove right checkmark if exists
            const checkmark = card.querySelector('.rounded-circle');
            if (checkmark) {
                checkmark.remove();
            }

            if (cardId === id) {
                card.classList.remove('sg-payment-unselected');
                card.classList.add('sg-payment-selected');
                if (iconBox) iconBox.style.color = 'var(--safegate-neon)';
                if (radioIndicator) {
                    radioIndicator.innerHTML = '<iconify-icon icon="ph:radio-button-fill" class="text-safegate-neon fs-4"></iconify-icon>';
                }
                
                // Add right checkmark dynamically
                const checkmarkDiv = document.createElement('div');
                checkmarkDiv.className = 'd-flex align-items-center justify-content-center rounded-circle flex-shrink-0';
                checkmarkDiv.style.width = '28px';
                checkmarkDiv.style.height = '28px';
                checkmarkDiv.style.background = 'rgba(217, 255, 0, 0.1)';
                checkmarkDiv.style.border = '1px solid rgba(217, 255, 0, 0.3)';
                checkmarkDiv.style.color = 'var(--safegate-neon)';
                checkmarkDiv.innerHTML = '<iconify-icon icon="ph:check-bold" class="fs-6"></iconify-icon>';
                card.appendChild(checkmarkDiv);
            } else {
                card.classList.remove('sg-payment-selected');
                card.classList.add('sg-payment-unselected');
                if (iconBox) iconBox.style.color = 'var(--safegate-text-sec)';
                if (radioIndicator) {
                    radioIndicator.innerHTML = '<iconify-icon icon="ph:circle-bold" class="text-safegate-text-sec fs-4"></iconify-icon>';
                }
            }
        });
    }

    // Trigger success payment callback
    function executePayment() {
        const listingId = "<?= (int) $listing_id ?>";
        if (listingId && listingId !== "0") {
            document.getElementById('paymentForm')?.submit();
            return;
        }

        const myModal = new bootstrap.Modal(document.getElementById('paymentSuccessModal'));
        myModal.show();
    }

    <?php if (isset($_GET['snap_token'])): ?>
    window.addEventListener('DOMContentLoaded', (event) => {
        if (typeof snap !== 'undefined') {
            snap.pay('<?= sg_h($_GET['snap_token']) ?>', {
                onSuccess: function(result) {
                    window.location.href = 'index.php?page=pembayaran&payment_result=success&order_id=' + result.order_id;
                },
                onPending: function(result) {
                    window.location.href = 'index.php?page=pembayaran&payment_result=pending&order_id=' + result.order_id;
                },
                onError: function(result) {
                    window.location.href = 'index.php?page=pembayaran&payment_result=error&order_id=' + result.order_id;
                },
                onClose: function() {
                    window.location.href = 'index.php?page=pembayaran&listing_id=<?= (int)$listing_id ?>';
                }
            });
        }
    });
    <?php endif; ?>
</script>

<!-- Bootstrap JS dependency bundle for Modal support -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<?php
// 3. Simpan konten ke dalam variabel
$content = ob_get_clean();

// 4. Panggil Layout Publik yang sudah berisi Header dan Footer
require_once __DIR__ . '/../../layouts/public_layout.php';
?>
