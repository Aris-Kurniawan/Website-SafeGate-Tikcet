<?php
require_once __DIR__ . '/../../core/safegate_repository.php';

// 1. Definisikan judul halaman
$page_title = "Detail Tiket - SafeGate";

// 2. Mulai menangkap output konten (Output Buffering)
ob_start();

$listing_id = isset($_GET['listing_id']) ? (int) $_GET['listing_id'] : 0;
$listing = $listing_id > 0 ? sg_get_listing_detail($listing_id) : null;
$bids = $listing_id > 0 ? sg_get_listing_bids($listing_id) : [];
$flash = sg_flash();

if (!$listing) {
    ?>
    <section class="container mx-auto py-5"
        style="max-width: 900px; padding-left: 1.5rem; padding-right: 1.5rem; margin-top: 4rem; margin-bottom: 5rem;">
        <div class="sg-glass rounded-4 p-5 text-center">
            <iconify-icon icon="ph:ticket-slash-bold" class="text-safegate-neon mb-3"
                style="font-size: 3rem;"></iconify-icon>
            <h1 class="h3 fw-bold text-white mb-2">Tiket Tidak Ditemukan</h1>
            <p class="text-safegate-text-sec mb-4">Detail tiket harus dibuka dari listing yang tersimpan di database.</p>
            <a href="index.php?page=penjualan" class="btn btn-safegate-neon rounded-pill fw-bold px-4">Kembali ke
                Marketplace</a>
        </div>
    </section>
    <?php
    $content = ob_get_clean();
    require_once __DIR__ . '/../../layouts/public_layout.php';
    return;
}

// Retrieve values from DB first, then GET fallback for old mock cards.
$title = $listing ? sg_h($listing['title']) : (isset($_GET['title']) ? htmlspecialchars($_GET['title']) : 'Midnight Symphony Tour');
$image = $listing ? sg_h(sg_event_image($listing['title'], $listing['image_path'] ?? '', $listing['description'] ?? '')) : (isset($_GET['image']) ? htmlspecialchars($_GET['image']) : sg_event_image('Midnight Symphony Tour'));
$date = $listing ? date('F d, Y H:i', strtotime($listing['event_date'])) : (isset($_GET['date']) ? htmlspecialchars($_GET['date']) : 'October 24, 2024');
$location = $listing ? sg_h($listing['venue'] . ', ' . $listing['city']) : (isset($_GET['location']) ? htmlspecialchars($_GET['location']) : 'The Neon Citadel, Los Angeles');

// Clean price inputs to treat them numerically or format as raw
$raw_price = $listing ? (string) ($listing['current_highest_bid'] ?: $listing['starting_bid']) : (isset($_GET['price']) ? $_GET['price'] : '180.000');
$minimum_bid = $listing ? (max((int) $listing['starting_bid'], (int) ($listing['current_highest_bid'] ?? 0)) + 10000) : 0;
$reserve_price = $listing ? (int) ($listing['reserve_price'] ?? 0) : 0;
$current_bid_value = $listing ? (int) ($listing['current_highest_bid'] ?: $listing['starting_bid']) : 0;
$listing_available = $listing && in_array($listing['listing_status'], ['active', 'promoted'], true);
$reserve_met = !$listing || $reserve_price <= 0 || $current_bid_value >= $reserve_price;
$current_user_id = sg_current_user_id();
if ($current_user_id && $listing_id > 0) {
    $hasWonPending = sg_fetch_one(
        'SELECT id FROM bids 
         WHERE listing_id = :listing_id AND bidder_id = :bidder_id AND is_winning_bid = 1 AND bid_status = "winner_pending_payment" LIMIT 1',
        ['listing_id' => $listing_id, 'bidder_id' => $current_user_id]
    );
    if ($hasWonPending) {
        header('Location: index.php?page=pembayaran&listing_id=' . $listing_id);
        exit;
    }
}
$is_own_listing = $listing && $current_user_id && (int) $listing['seller_id'] === (int) $current_user_id;
$wallet_summary = $current_user_id ? sg_get_buyer_wallet_summary((int) $current_user_id) : ['available' => 0];
$bid_deposit = sg_bid_deposit_amount();
$can_bid = $listing_available && !$is_own_listing;
$can_checkout = $listing_available && $reserve_met && !$is_own_listing;

// Parse price format: check if it's IDR or USDC
$is_usdc = false;
$price_val = 180000;
$currency_suffix = "";
$currency_prefix = "Rp. ";

// Clean formatting to extract pure numeric values
$clean_price = str_replace(['Rp.', 'Rp', ' ', ','], '', $raw_price);
if (strpos($clean_price, '.') !== false) {
    $parts = explode('.', $clean_price);
    if (count($parts) == 2 && strlen($parts[1]) == 3) {
        $price_val = (float) str_replace('.', '', $clean_price);
    } else {
        $price_val = (float) $clean_price;
        if ($price_val < 1000) {
            $price_val = $price_val * 1000;
        }
    }
} else {
    $price_val = (float) $clean_price;
}

// Calculate breakdown dynamically
if ($is_usdc) {
    $service_fee = 0;
    $escrow_insurance = 0;
    $total_price = $price_val;

    $disp_price = number_format($price_val, 2, '.', '') . $currency_suffix;
    $disp_service = number_format($service_fee, 2, '.', '') . $currency_suffix;
    $disp_escrow = number_format($escrow_insurance, 2, '.', '') . $currency_suffix;
    $disp_total = number_format($total_price, 2, '.', '') . " TOKEN USDC";
    $disp_inst = number_format($price_val, 2, '.', '') . $currency_suffix;
    $bid_placeholder = "0.00";
    $bid_currency = "USDC";
} else {
    $service_fee = 0;
    $escrow_insurance = 0;
    $total_price = $price_val;

    $disp_price = $currency_prefix . number_format($price_val, 0, ',', '.');
    $disp_service = $currency_prefix . number_format($service_fee, 0, ',', '.');
    $disp_escrow = $currency_prefix . number_format($escrow_insurance, 0, ',', '.');
    $disp_total = $currency_prefix . number_format($total_price, 0, ',', '.') . " IDR";
    $disp_inst = $currency_prefix . number_format($price_val, 0, ',', '.');
    $bid_placeholder = "0";
    $bid_currency = "IDR";
}

// Seating parameters
$seksi = $listing ? sg_h($listing['section']) : (isset($_GET['seksi']) ? htmlspecialchars($_GET['seksi']) : '102');
$baris = $listing ? sg_h($listing['row']) : (isset($_GET['baris']) ? htmlspecialchars($_GET['baris']) : 'KK');
$kursi = $listing ? sg_h($listing['seat']) : (isset($_GET['kursi']) ? htmlspecialchars($_GET['kursi']) : '14');

// Calculate time remaining
$time_remaining = 0;
if ($listing && !empty($listing['auction_end_at'])) {
    $time_remaining = strtotime($listing['auction_end_at']) - time();
    if ($time_remaining < 0)
        $time_remaining = 0;
} elseif (!$listing) {
    $time_remaining = 2 * 3600 + 44 * 60 + 12;
}

$timer_hours = floor($time_remaining / 3600);
$timer_minutes = floor(($time_remaining % 3600) / 60);
$timer_seconds = $time_remaining % 60;
?>

<!-- Hero Banner Section -->
<section class="container-fluid py-5 position-relative overflow-hidden sg-detail-hero" style="margin-top: 2rem;">
    <!-- Ambient Light Glows -->
    <div class="sg-banner-glow"></div>
    <div class="position-absolute bg-info rounded-circle"
        style="opacity: 0.04; filter: blur(120px); width: 400px; height: 400px; bottom: -50px; right: -50px; z-index: 0; pointer-events: none;">
    </div>

    <div class="container mx-auto position-relative z-1"
        style="max-width: 1200px; padding-left: 1.5rem; padding-right: 1.5rem;">
        <div class="row align-items-center g-5">
            <!-- Left Info -->
            <div class="col-12 col-lg-7 d-flex flex-column align-items-start">
                <!-- Badges -->
                <div class="d-flex flex-wrap gap-2 mb-4">
                    <span class="sg-pill-badge sg-pill-badge-active">
                        <iconify-icon icon="ph:shield-check-fill" class="fs-6"></iconify-icon> Protokol Aktif
                    </span>
                    <span class="sg-pill-badge sg-pill-badge-active">
                        <iconify-icon icon="ph:user-focus-fill" class="fs-6"></iconify-icon> KYC Terverifikasi
                    </span>
                    <span class="sg-pill-badge sg-pill-badge-active">
                        <iconify-icon icon="ph:arrows-counter-clockwise-bold" class="fs-6"></iconify-icon> Escrow Aktif
                    </span>
                </div>

                <!-- Event Name -->
                <h1 class="display-3 fw-bold text-white mb-4 letter-spacing-tight" style="line-height: 1.1;">
                    <?= $title ?>
                </h1>

                <!-- Event Meta -->
                <div class="d-flex flex-column flex-sm-row gap-4 align-items-sm-center text-safegate-text-sec fs-6">
                    <div class="d-flex align-items-center gap-2">
                        <iconify-icon icon="ph:calendar-blank" class="text-safegate-neon fs-5"></iconify-icon>
                        <span><?= $date ?></span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <iconify-icon icon="ph:map-pin" class="text-safegate-neon fs-5"></iconify-icon>
                        <span><?= $location ?></span>
                    </div>
                </div>
            </div>

            <!-- Right Image -->
            <div class="col-12 col-lg-5">
                <div class="sg-hero-img-container">
                    <img src="<?= $image ?>" alt="<?= $title ?>" class="w-100 h-100 object-fit-cover">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Details Section -->
<section class="container mx-auto py-5"
    style="max-width: 1200px; padding-left: 1.5rem; padding-right: 1.5rem; margin-bottom: 5rem;">
    <?php if ($flash): ?>
        <div class="rounded-4 p-3 mb-4 fw-semibold"
            style="background: <?= ($flash['type'] ?? 'success') === 'error' ? 'rgba(255,85,85,.08)' : 'rgba(217,255,0,.08)' ?>; border: 1px solid <?= ($flash['type'] ?? 'success') === 'error' ? 'rgba(255,85,85,.22)' : 'rgba(217,255,0,.18)' ?>; color: <?= ($flash['type'] ?? 'success') === 'error' ? '#ff6868' : 'var(--safegate-neon)' ?>;">
            <?= sg_h($flash['message']) ?>
        </div>
    <?php endif; ?>

    <?php if (!$listing_available): ?>
        <div class="rounded-4 p-3 mb-4 fw-semibold"
            style="background: rgba(255, 85, 85, .08); border: 1px solid rgba(255, 85, 85, .22); color: #ff6868;">
            Listing ini sudah <?= sg_h(str_replace('_', ' ', $listing['listing_status'] ?? 'tidak tersedia')) ?>, jadi tidak
            bisa ditawar atau dibeli lagi.
        </div>
    <?php endif; ?>

    <?php if ($is_own_listing): ?>
        <div class="rounded-4 p-3 mb-4 fw-semibold d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3"
            style="background: rgba(217, 255, 0, .08); border: 1px solid rgba(217, 255, 0, .2); color: var(--safegate-neon);">
            <span>Tiket ini adalah listing milik Anda, jadi tidak bisa dibeli atau ditawar dari akun sendiri.</span>
            <a href="index.php?page=active_listings" class="btn btn-safegate-neon rounded-pill fw-bold px-4 py-2"
                style="font-size:.75rem;">Kelola Listing</a>
        </div>
    <?php endif; ?>

    <div class="row g-5">
        <!-- Left Side: Seats & Bidding -->
        <div class="col-12 col-lg-8">
            <div class="d-flex flex-column gap-5">

                <!-- Seat Information Card -->
                <div class="p-4 p-md-5 rounded-4 sg-glass">
                    <h3 class="h4 fw-bold text-white mb-4 d-flex align-items-center gap-3"
                        style="letter-spacing: -0.02em;">
                        <iconify-icon icon="ph:chair-bold" class="text-safegate-neon fs-3"></iconify-icon>
                        Informasi Tempat Duduk
                    </h3>

                    <!-- Grid Seats -->
                    <div class="row g-3 mb-4">
                        <div class="col-4">
                            <div class="sg-seat-card">
                                <div class="sg-seat-label">Seksi</div>
                                <div class="sg-seat-value"><?= $seksi ?></div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="sg-seat-card">
                                <div class="sg-seat-label">Baris</div>
                                <div class="sg-seat-value"><?= $baris ?></div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="sg-seat-card">
                                <div class="sg-seat-label">Kursi</div>
                                <div class="sg-seat-value"><?= $kursi ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Transfer Restriction Box -->
                    <div class="d-flex gap-3 p-4 rounded-3 align-items-start"
                        style="background: rgba(9, 11, 16, 0.4); border: 1px solid rgba(255, 255, 255, 0.03);">
                        <iconify-icon icon="ph:info-bold"
                            class="text-safegate-neon fs-4 mt-0.5 flex-shrink-0"></iconify-icon>
                        <div>
                            <h4 class="text-white fw-bold mb-1" style="font-size: 0.9rem;">Transfer Dibatasi</h4>
                            <p class="text-safegate-text-sec mb-0" style="font-size: 0.8rem; line-height: 1.6;">
                                Tiket ini dikunci secara kriptografis ke protokol SafeGate. Tiket hanya dapat dijual
                                kembali atau ditransfer dalam ekosistem terverifikasi kami untuk mencegah calo dan
                                penipuan.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Auction (Lelang) Section -->
                <div class="p-4 p-md-5 rounded-4 sg-glass position-relative overflow-hidden">
                    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
                        <h3 class="h4 fw-bold text-white mb-0 d-flex align-items-center gap-3"
                            style="letter-spacing: -0.02em;">
                            <iconify-icon icon="ph:gavel-bold" class="text-safegate-neon fs-3"></iconify-icon>
                            Lelang
                        </h3>
                        <span
                            class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 rounded-pill fw-bold text-uppercase"
                            style="font-size: 0.6rem; letter-spacing: 0.05em;">Lelang Langsung</span>
                    </div>

                    <div class="row g-4 align-items-end mt-2">
                        <!-- Countdown Timer -->
                        <div class="col-12 col-md-5">
                            <p class="text-safegate-text-sec fw-bold text-uppercase mb-3"
                                style="font-size: 0.65rem; letter-spacing: 0.1em;">Lelang berakhir dalam</p>
                            <div class="d-flex align-items-center gap-2">
                                <div>
                                    <div id="timer-hours" class="sg-timer-number">
                                        <?= str_pad($timer_hours, 2, '0', STR_PAD_LEFT) ?>
                                    </div>
                                    <div class="sg-timer-label">Jam</div>
                                </div>
                                <div class="sg-timer-colon">:</div>
                                <div>
                                    <div id="timer-minutes" class="sg-timer-number">
                                        <?= str_pad($timer_minutes, 2, '0', STR_PAD_LEFT) ?>
                                    </div>
                                    <div class="sg-timer-label">Menit</div>
                                </div>
                                <div class="sg-timer-colon">:</div>
                                <div>
                                    <div id="timer-seconds" class="sg-timer-number">
                                        <?= str_pad($timer_seconds, 2, '0', STR_PAD_LEFT) ?>
                                    </div>
                                    <div class="sg-timer-label">Detik</div>
                                </div>
                            </div>
                        </div>

                        <!-- Bid Entry -->
                        <div class="col-12 col-md-7">
                            <p class="text-safegate-text-sec fw-bold text-uppercase mb-3"
                                style="font-size: 0.65rem; letter-spacing: 0.1em;">Masukkan Penawaran</p>
                            <?php if ($current_user_id && !$is_own_listing && $wallet_summary['available'] < $bid_deposit): ?>
                                <div class="mb-3 p-3 rounded-3"
                                    style="background:rgba(255,210,64,.08);border:1px solid rgba(255,210,64,.2);color:#ffd98a;font-size:.78rem;">
                                    Saldo aktif kurang. Anda harus memiliki saldo aktif minimal
                                    <?= sg_rupiah($bid_deposit) ?> di <a href="index.php?page=buyer_wallet"
                                        style="color:var(--safegate-neon);font-weight:800;">Wallet & Escrow</a> untuk ikut
                                    lelang.
                                </div>
                            <?php endif; ?>
                            <form class="d-flex flex-column gap-3"
                                action="index.php?page=detail_tiket&listing_id=<?= (int) $listing_id ?>" method="post">
                                <input type="hidden" name="sg_action" value="submit_bid">
                                <input type="hidden" name="listing_id" value="<?= (int) $listing_id ?>">
                                <div class="sg-bid-input-wrapper">
                                    <input type="text" id="bid-amount" name="bid_amount"
                                        placeholder="<?= $is_own_listing ? 'Listing milik Anda' : ($listing ? sg_rupiah($minimum_bid) : $bid_placeholder) ?>"
                                        class="sg-bid-input" <?= $can_bid ? 'required' : 'disabled' ?>>
                                    <span class="sg-bid-currency"><?= $bid_currency ?></span>
                                </div>
                                <button type="submit"
                                    class="btn btn-light w-100 rounded-pill fw-bold py-3 transition-all"
                                    style="font-size: 0.85rem; border: none; letter-spacing: 0.05em;" <?= $can_bid ? '' : 'disabled' ?>>
                                    <?= $is_own_listing ? 'Listing Milik Anda' : ($listing_available ? 'Kirim Tawaran' : 'Lelang Tidak Tersedia') ?>
                                </button>
                            </form>
                        </div>
                    </div>

                    <?php if ($listing): ?>
                        <?php if ($reserve_price > 0): ?>
                            <div class="mt-3 p-3 rounded-3"
                                style="background: <?= $reserve_met ? 'rgba(0,255,170,.06)' : 'rgba(255,210,64,.06)' ?>; border: 1px solid <?= $reserve_met ? 'rgba(0,255,170,.18)' : 'rgba(255,210,64,.2)' ?>;">
                                <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
                                    <span class="text-safegate-text-sec fw-bold text-uppercase"
                                        style="font-size: .65rem; letter-spacing: .1em;">Reserve Price</span>
                                    <strong
                                        style="color: <?= $reserve_met ? '#00ffaa' : '#ffd24d' ?>;"><?= sg_rupiah($reserve_price) ?></strong>
                                </div>
                                <p class="mb-0 mt-2"
                                    style="font-size:.76rem; color: <?= $reserve_met ? '#bfffe9' : '#ffd98a' ?>;">
                                    <?= $reserve_met ? 'Reserve price sudah tercapai. Tiket bisa dibeli.' : 'Bid tertinggi belum mencapai reserve price, jadi tiket belum bisa dibeli.' ?>
                                </p>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

            </div>
        </div>

        <!-- Right Side: Pay Breakdown & Protection -->
        <div class="col-12 col-lg-4">
            <div class="sticky-top" style="top: 6.5rem; z-index: 10;">

                <!-- Bidding Leaderboard Card -->
                <div class="sg-summary-card"
                    style="padding: 0; background: rgba(15, 17, 26, 0.6); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 1rem; overflow: hidden; margin-bottom: 1.5rem;">
                    <div style="padding: 1.5rem 1.5rem 1rem;">
                        <h3 class="fw-bold text-white text-center mb-0"
                            style="font-size: 1rem; letter-spacing: 0.1em; text-transform: uppercase;">Bidding
                            Leaderboard</h3>
                    </div>

                    <div class="d-flex justify-content-between text-safegate-text-sec px-4 pb-3 text-uppercase fw-bold"
                        style="font-size: 0.65rem; letter-spacing: 0.05em; border-bottom: 1px solid rgba(255, 255, 255, 0.05);">
                        <div style="width: 15%;">Rank</div>
                        <div style="width: 45%;">Bidder ID</div>
                        <div style="width: 40%; text-align: right;">Amount</div>
                    </div>

                    <div class="d-flex flex-column">
                        <?php if (!empty($bids)): ?>
                            <?php foreach (array_slice($bids, 0, 5) as $rank => $bid): ?>
                                <?php $isTop = ($rank === 0); ?>
                                <div class="d-flex justify-content-between align-items-center px-4 py-3"
                                    style="border-bottom: 1px solid rgba(255, 255, 255, 0.03); <?= $isTop ? 'background: rgba(217, 255, 0, 0.03);' : '' ?>">
                                    <div style="width: 15%;">
                                        <?php if ($isTop): ?>
                                            <span
                                                class="d-inline-flex align-items-center justify-content-center fw-bold text-dark rounded-circle"
                                                style="background: var(--safegate-neon); width: 24px; height: 24px; font-size: 0.75rem;">1</span>
                                        <?php else: ?>
                                            <span class="text-safegate-text-sec fw-bold ps-2"
                                                style="font-size: 0.85rem;"><?= $rank + 1 ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div style="width: 45%; font-size: 0.85rem;"
                                        class="fw-bold <?= $isTop ? 'text-white' : 'text-safegate-text-sec' ?>">
                                        <?= isset($bid['bidder_id']) ? 'Bidder #' . str_pad((string) $bid['bidder_id'], 3, '0', STR_PAD_LEFT) : sg_h($bid['full_name']) ?>
                                    </div>
                                    <div style="width: 40%; text-align: right; font-size: 0.85rem;"
                                        class="fw-bold <?= $isTop ? 'text-safegate-neon' : 'text-white' ?>">
                                        <?= sg_rupiah($bid['bid_amount']) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="px-4 py-4 text-center text-safegate-text-sec" style="font-size: 0.85rem;">
                                Belum ada tawaran. Jadilah penawar pertama!
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Buyer Protection Card -->
                <div class="sg-protection-card">
                    <div class="sg-protection-icon">
                        <iconify-icon icon="ph:shield-check-fill" class="fs-4"></iconify-icon>
                    </div>
                    <div>
                        <h4 class="text-white fw-bold mb-1" style="font-size: 0.85rem;">Perlindungan Pembeli</h4>
                        <p class="text-safegate-text-sec mb-0" style="font-size: 0.72rem; line-height: 1.4;">
                            Dana ditahan di escrow hingga 24 jam setelah acara selesai.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- Success Modal / Popup Overlay -->
<div class="modal fade" id="checkoutSuccessModal" tabindex="-1" aria-hidden="true" style="backdrop-filter: blur(8px);">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg text-center p-5"
            style="background: var(--safegate-surface); border: 1px solid rgba(255,255,255,0.06) !important;">
            <div class="d-flex align-items-center justify-content-center mx-auto rounded-circle mb-4 bg-safegate-neon"
                style="width: 72px; height: 72px; box-shadow: 0 0 20px rgba(217, 255, 0, 0.45);">
                <iconify-icon icon="ph:check-bold" class="text-black fs-1 fw-bold"></iconify-icon>
            </div>
            <h3 class="h3 fw-bold text-white mb-2">Transaksi Diproses!</h3>
            <p class="text-safegate-text-sec mb-4 fs-6" style="line-height: 1.5;">
                Escrow aman telah aktif. Tiket Anda sedang dikunci secara kriptografis dan dialokasikan ke profil Anda.
            </p>
            <button type="button" class="btn btn-outline-safegate-neon rounded-pill px-5 fw-bold py-2.5"
                data-bs-dismiss="modal">
                SELESAI
            </button>
        </div>
    </div>
</div>

<!-- JS Interactions -->
<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Start countdown dynamically
        let hours = <?= (int) $timer_hours ?>;
        let minutes = <?= (int) $timer_minutes ?>;
        let seconds = <?= (int) $timer_seconds ?>;

        const hEl = document.getElementById("timer-hours");
        const mEl = document.getElementById("timer-minutes");
        const sEl = document.getElementById("timer-seconds");

        if (hEl && mEl && sEl) {
            const interval = setInterval(() => {
                if (seconds > 0) {
                    seconds--;
                } else {
                    if (minutes > 0) {
                        minutes--;
                        seconds = 59;
                    } else {
                        if (hours > 0) {
                            hours--;
                            minutes = 59;
                            seconds = 59;
                        } else {
                            clearInterval(interval);
                            window.location.reload();
                        }
                    }
                }
                hEl.textContent = String(hours).padStart(2, '0');
                mEl.textContent = String(minutes).padStart(2, '0');
                sEl.textContent = String(seconds).padStart(2, '0');
            }, 1000);
        }
    });

    // Checkout modal trigger
    function checkoutTicket() {
        const listingAvailable = <?= $listing_available ? 'true' : 'false' ?>;
        if (!listingAvailable) {
            alert('Listing ini sudah tidak tersedia untuk dibeli.');
            return;
        }

        const isOwnListing = <?= $is_own_listing ? 'true' : 'false' ?>;
        if (isOwnListing) {
            alert('Tiket ini adalah listing milik Anda sendiri. Gunakan dashboard seller untuk mengelolanya.');
            return;
        }

        const reserveMet = <?= $reserve_met ? 'true' : 'false' ?>;
        if (!reserveMet) {
            alert('Bid tertinggi belum mencapai reserve price. Naikkan tawaran dulu sebelum membeli tiket.');
            return;
        }

        const title = encodeURIComponent("<?= $title ?>");
        const price = encodeURIComponent("<?= $raw_price ?>");
        const image = encodeURIComponent("<?= $image ?>");
        const date = encodeURIComponent("<?= $date ?>");
        const location = encodeURIComponent("<?= $location ?>");
        const seksi = encodeURIComponent("<?= $seksi ?>");
        const baris = encodeURIComponent("<?= $baris ?>");
        const kursi = encodeURIComponent("<?= $kursi ?>");
        const listingId = "<?= (int) $listing_id ?>";

        const listingQuery = listingId && listingId !== "0" ? `&listing_id=${listingId}` : "";
        window.location.href = `index.php?page=pembayaran&title=${title}&price=${price}&image=${image}&date=${date}&location=${location}&seksi=${seksi}&baris=${baris}&kursi=${kursi}${listingQuery}`;
    }
</script>

<!-- Bootstrap JS dependency bundle for Modal support -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<?php
// 3. Simpan konten ke dalam variabel
$content = ob_get_clean();

// 4. Panggil Layout Publik yang sudah berisi Header dan Footer
require_once __DIR__ . '/../../layouts/public_layout.php';
?>