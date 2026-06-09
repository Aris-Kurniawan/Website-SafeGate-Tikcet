<?php
require_once __DIR__ . '/../../core/safegate_repository.php';

$page_title = 'Buyer Dashboard - SafeGate';
$buyer_page = 'buyer_dashboard';
$buyer_id = sg_current_user_id();
$dashboard = sg_get_buyer_dashboard($buyer_id);
$notifications = sg_get_notifications($buyer_id, 4);
$unread_notifications = sg_unread_notification_count($buyer_id);
$sellerKyc = sg_get_user_kyc_submission($buyer_id);
$sellerStatus = strtolower((string) ($sellerKyc['status'] ?? 'unsubmitted'));
$sellerCtaLabel = $sellerStatus === 'approved' ? 'Dashboard Seller' : ($sellerStatus === 'pending' ? 'Status Seller' : 'Daftar Jadi Seller');
$sellerCtaHref = $sellerStatus === 'approved' ? 'index.php?page=seller_overview' : 'index.php?page=seller_register';
$flash = sg_flash();

ob_start();
?>

<section class="sg-buyer-content">
    <?php if ($flash): ?><div class="sg-buyer-notice"><?= sg_h($flash['message']) ?></div><?php endif; ?>

    <div class="sg-buyer-titlebar">
        <div>
            <h1>Dashboard Pengguna</h1>
            <p>Kelola tiket, saldo jaminan, pembayaran, dan status escrow pembelian kamu.</p>
        </div>
        <div class="sg-buyer-actions">
            <a class="sg-buyer-btn" href="index.php?page=penjualan"><iconify-icon icon="ph:magnifying-glass"></iconify-icon> Cari Tiket</a>
            <a class="sg-buyer-btn is-neon" href="<?= sg_h($sellerCtaHref) ?>"><iconify-icon icon="ph:storefront"></iconify-icon> <?= sg_h($sellerCtaLabel) ?></a>
        </div>
    </div>

    <div class="sg-buyer-grid-3 mb-4">
        <article class="sg-buyer-kpi">
            <span>Saldo Aktif</span>
            <strong><?= sg_rupiah($dashboard['available']) ?></strong>
            <small>+2.4% vs bulan lalu</small>
        </article>
        <article class="sg-buyer-kpi is-neon">
            <span>Saldo Ditahan / Escrow</span>
            <strong><?= sg_rupiah($dashboard['held']) ?></strong>
            <small>Dikunci untuk lelang aktif</small>
        </article>
        <article class="sg-buyer-kpi">
            <span>Tiket & Bid</span>
            <strong><?= (int) $dashboard['tickets'] ?> Tiket</strong>
            <small><?= (int) $dashboard['active_bids'] ?> bid aktif</small>
        </article>
    </div>

    <div class="sg-buyer-grid-2">
        <article class="sg-buyer-panel">
            <div class="d-flex align-items-center justify-content-between gap-3 mb-2">
                <h2>Pesanan Terbaru</h2>
                <a class="sg-buyer-btn" href="index.php?page=my_tickets">My Tickets</a>
            </div>
            <?php if ($dashboard['orders']): ?>
                <div class="sg-buyer-card-list">
                    <?php foreach ($dashboard['orders'] as $ticket): ?>
                        <article class="sg-buyer-ticket-card">
                            <img src="<?= sg_h(sg_event_image($ticket['title'], $ticket['image_path'] ?? '')) ?>" alt="<?= sg_h($ticket['title']) ?>">
                            <div class="sg-buyer-ticket-body">
                                <span class="sg-buyer-chip">Escrow <?= sg_h($ticket['escrow_status']) ?></span>
                                <h2><?= sg_h($ticket['title']) ?></h2>
                                <p><?= sg_h($ticket['venue']) ?>, <?= sg_h($ticket['city']) ?> · <?= sg_h($ticket['transaction_code']) ?></p>
                                <div class="d-flex flex-wrap gap-2 mt-2">
                                    <?php if (($ticket['payment_status'] ?? 'pending') !== 'paid'): ?>
                                        <?php 
                                        $snapTokenQuery = !empty($ticket['midtrans_snap_token']) ? '&snap_token=' . urlencode($ticket['midtrans_snap_token']) : '';
                                        $paymentUrl = 'index.php?page=pembayaran&listing_id=' . (int)$ticket['listing_id'] . $snapTokenQuery;
                                        ?>
                                        <a class="sg-buyer-btn is-neon" href="<?= $paymentUrl ?>">Melanjutkan Pembayaran</a>
                                    <?php else: ?>
                                        <a class="sg-buyer-btn" href="index.php?page=ticket_verify&transaction_id=<?= (int) $ticket['transaction_id'] ?>">Verifikasi Tiket</a>
                                        <?php if (!empty($ticket['ticket_proof'])): ?>
                                            <a class="sg-buyer-btn" href="<?= sg_h($ticket['ticket_proof']) ?>" target="_blank">
                                                <iconify-icon icon="ph:eye-bold" class="align-middle me-1"></iconify-icon>Lihat
                                            </a>
                                            <a class="sg-buyer-btn" href="<?= sg_h($ticket['ticket_proof']) ?>" download="Ticket-<?= sg_h($ticket['transaction_code']) ?>.<?= pathinfo($ticket['ticket_proof'], PATHINFO_EXTENSION) ?>">
                                                <iconify-icon icon="ph:download-bold" class="align-middle me-1"></iconify-icon>Unduh
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-safegate-text-sec mt-4 mb-0">Belum ada tiket yang dibeli. Mulai dari marketplace untuk melihat tiket tersedia.</p>
            <?php endif; ?>
        </article>

        <aside class="sg-buyer-panel">
            <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
                <h2>Aktivitas Terbaru <?= $unread_notifications > 0 ? '<span class="badge bg-danger rounded-pill" style="font-size: 11px; vertical-align: middle;">' . $unread_notifications . '</span>' : '' ?></h2>
                <div class="d-flex align-items-center gap-2">
                    <?php if ($unread_notifications > 0): ?>
                        <a href="index.php?sg_action=mark_notifications_read" style="color: var(--safegate-neon); font-size: 11px; font-weight: 800; text-decoration: none; text-transform: uppercase;" class="me-2">Mark Read</a>
                    <?php endif; ?>
                    <a class="sg-buyer-btn" href="index.php?page=buyer_wallet">Wallet</a>
                </div>
            </div>
            <?php if ($notifications): ?>
                <div class="sg-buyer-card-list">
                    <?php foreach ($notifications as $notification):
                        $type = $notification['type'] ?? '';
                        $isRead = (int) ($notification['is_read'] ?? 0);
                        
                        $itemClass = '';
                        $icon = 'ph:bell-bold';
                        
                        if ($type === 'auction_won' || $type === 'payment_success' || $type === 'kyc_approved' || $type === 'escrow_released') {
                            $itemClass = 'is-success';
                            $icon = ($type === 'auction_won') ? 'ph:trophy-bold' : (($type === 'payment_success') ? 'ph:credit-card-bold' : 'ph:check-bold');
                        } elseif ($type === 'auction_lost' || $type === 'kyc_rejected') {
                            $itemClass = 'is-danger';
                            $icon = ($type === 'auction_lost') ? 'ph:x-circle-bold' : 'ph:prohibit-bold';
                        } elseif ($type === 'bid_placed' || $type === 'dispute_opened') {
                            $itemClass = 'is-warning';
                            $icon = ($type === 'bid_placed') ? 'ph:gavel-bold' : 'ph:warning-bold';
                        } else {
                            $itemClass = 'is-info';
                            $icon = 'ph:info-bold';
                        }
                        
                        if ($isRead) {
                            $itemClass .= ' is-read';
                        }
                    ?>
                        <?php 
                        $hasTxLink = !empty($notification['transaction_code']);
                        $linkUrl = $hasTxLink ? 'index.php?page=transaction_detail&code=' . urlencode($notification['transaction_code']) : '#';
                        ?>
                        <?php if ($type === 'auction_won'): ?>
                            <a href="index.php?page=pembayaran&listing_id=<?= (int) $notification['related_id'] ?>" class="sg-bid-rank <?= $itemClass ?>" style="text-decoration: none; color: inherit; display: grid;">
                        <?php elseif ($hasTxLink): ?>
                            <a href="<?= $linkUrl ?>" class="sg-bid-rank <?= $itemClass ?>" style="text-decoration: none; color: inherit; display: grid; cursor: pointer;">
                        <?php else: ?>
                            <div class="sg-bid-rank <?= $itemClass ?>">
                        <?php endif; ?>
                            <b><iconify-icon icon="<?= $icon ?>"></iconify-icon></b>
                            <div>
                                <strong>
                                    <?= sg_h($notification['title']) ?>
                                    <?php if ($hasTxLink): ?>
                                        <iconify-icon icon="ph:arrow-square-out-bold" class="ms-1" style="font-size:12px; color:var(--safegate-neon); vertical-align: middle;"></iconify-icon>
                                    <?php endif; ?>
                                </strong>
                                <span><?= sg_h($notification['body']) ?></span>
                            </div>
                            <em><?= sg_h(sg_time_ago($notification['created_at'])) ?></em>
                        <?php if ($type === 'auction_won' || $hasTxLink): ?>
                            </a>
                        <?php else: ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-safegate-text-sec mb-0">Belum ada notifikasi baru.</p>
            <?php endif; ?>
        </aside>
    </div>
</section>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/buyer_layout.php';
?>
