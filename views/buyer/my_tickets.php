<?php
require_once __DIR__ . '/../../core/safegate_repository.php';

$page_title = 'My Tickets - SafeGate';
$buyer_page = 'my_tickets';
$buyer_id = sg_current_user_id();
$tickets = sg_get_buyer_tickets($buyer_id);
$flash = sg_flash();

ob_start();
?>

<section class="sg-buyer-content">
    <?php if ($flash): ?><div class="sg-buyer-notice"><?= sg_h($flash['message']) ?></div><?php endif; ?>

    <div class="sg-buyer-titlebar">
        <div>
            <h1>My Tickets</h1>
            <p>Daftar tiket yang sudah dibeli dan status verifikasi penggunaan tiket.</p>
        </div>
        <div class="sg-buyer-actions">
            <a class="sg-buyer-btn is-neon" href="index.php?page=penjualan"><iconify-icon icon="ph:plus"></iconify-icon> Cari Tiket</a>
        </div>
    </div>

    <?php if (!$tickets): ?>
        <article class="sg-buyer-panel text-center py-5">
            <iconify-icon icon="ph:ticket" class="text-safegate-neon mb-3" style="font-size:54px;"></iconify-icon>
            <h2>Belum Ada Tiket</h2>
            <p class="text-safegate-text-sec mt-2 mb-4">Setelah pembayaran berhasil, tiket kamu akan muncul di dashboard ini.</p>
            <a href="index.php?page=penjualan" class="sg-buyer-btn is-neon">Buka Marketplace</a>
        </article>
    <?php else: ?>
        <div class="sg-buyer-card-list">
            <?php foreach ($tickets as $ticket): ?>
                <article class="sg-buyer-ticket-card">
                    <img src="<?= sg_h(sg_event_image($ticket['title'], $ticket['image_path'] ?? '')) ?>" alt="<?= sg_h($ticket['title']) ?>">
                    <div class="sg-buyer-ticket-body">
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <span class="sg-buyer-chip">Escrow <?= sg_h($ticket['escrow_status']) ?></span>
                            <span class="sg-buyer-chip is-muted"><?= sg_h(str_replace('_', ' ', $ticket['buyer_ticket_status'] ?? 'pending_use')) ?></span>
                            <?php if (!empty($ticket['dispute_status'])): ?>
                                <span class="sg-buyer-chip is-danger">Dispute <?= sg_h($ticket['dispute_status']) ?></span>
                            <?php endif; ?>
                        </div>
                        <h2><?= sg_h($ticket['title']) ?></h2>
                        <p><?= sg_h($ticket['venue']) ?>, <?= sg_h($ticket['city']) ?> · <?= date('d M Y', strtotime($ticket['event_date'])) ?></p>
                        <div class="row g-2 mb-3">
                            <div class="col-4"><div class="rounded-2 p-2" style="background:#090b10;"><small class="text-safegate-text-sec d-block">Section</small><strong><?= sg_h($ticket['section']) ?></strong></div></div>
                            <div class="col-4"><div class="rounded-2 p-2" style="background:#090b10;"><small class="text-safegate-text-sec d-block">Row</small><strong><?= sg_h($ticket['row']) ?></strong></div></div>
                            <div class="col-4"><div class="rounded-2 p-2" style="background:#090b10;"><small class="text-safegate-text-sec d-block">Seat</small><strong><?= sg_h($ticket['seat']) ?></strong></div></div>
                        </div>
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div>
                                <span class="text-safegate-text-sec d-block" style="font-size:12px;">Total Payment</span>
                                <strong class="fs-5"><?= sg_rupiah($ticket['total_amount']) ?></strong>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <a class="sg-buyer-btn" href="index.php?page=transaction_detail&code=<?= urlencode($ticket['transaction_code']) ?>">Detail</a>
                                <?php if (($ticket['buyer_ticket_status'] ?? 'pending_use') === 'pending_use'): ?>
                                    <a class="sg-buyer-btn is-neon" href="index.php?page=ticket_verify&transaction_id=<?= (int) $ticket['transaction_id'] ?>">Verifikasi Tiket</a>
                                <?php else: ?>
                                    <a class="sg-buyer-btn" href="index.php?page=ticket_verify&transaction_id=<?= (int) $ticket['transaction_id'] ?>">Lihat Verifikasi</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/buyer_layout.php';
?>
