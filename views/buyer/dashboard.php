<?php
require_once __DIR__ . '/../../core/safegate_repository.php';

$page_title = 'Buyer Dashboard - SafeGate';
$buyer_page = 'buyer_dashboard';
$buyer_id = sg_current_user_id();
$dashboard = sg_get_buyer_dashboard($buyer_id);
$notifications = sg_get_notifications($buyer_id, 4);
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
            <a class="sg-buyer-btn is-neon" href="index.php?page=seller_overview"><iconify-icon icon="ph:storefront"></iconify-icon> Dashboard Seller</a>
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
                                <a class="sg-buyer-btn" href="index.php?page=ticket_verify&transaction_id=<?= (int) $ticket['transaction_id'] ?>">Verifikasi Tiket</a>
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
                <h2>Aktivitas Terbaru</h2>
                <a class="sg-buyer-btn" href="index.php?page=buyer_wallet">Wallet</a>
            </div>
            <?php if ($notifications): ?>
                <div class="sg-buyer-card-list">
                    <?php foreach ($notifications as $notification): ?>
                        <div class="sg-bid-rank">
                            <b><iconify-icon icon="ph:bell"></iconify-icon></b>
                            <div>
                                <strong><?= sg_h($notification['title']) ?></strong>
                                <span><?= sg_h($notification['body']) ?></span>
                            </div>
                            <em><?= sg_h(sg_time_ago($notification['created_at'])) ?></em>
                        </div>
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
