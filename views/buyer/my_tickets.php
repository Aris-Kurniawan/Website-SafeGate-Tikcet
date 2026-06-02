<?php
require_once __DIR__ . '/../../core/safegate_repository.php';

$page_title = 'My Tickets - SafeGate';
$buyer_page = 'my_tickets';
$buyer_id = sg_current_user_id();
$tickets = sg_get_buyer_tickets($buyer_id);
$bids = sg_get_buyer_bids($buyer_id);
$flash = sg_flash();

ob_start();
?>

<section class="sg-buyer-content">
    <?php if ($flash): ?>
        <div class="sg-buyer-notice"><?= sg_h($flash['message']) ?></div><?php endif; ?>

    <div class="sg-buyer-titlebar">
        <div>
            <h1>My Tickets</h1>
            <p>Daftar tiket yang sudah dibeli dan status verifikasi penggunaan tiket.</p>
        </div>
        <div class="sg-buyer-actions">
            <a class="sg-buyer-btn is-neon" href="index.php?page=penjualan"><iconify-icon icon="ph:plus"></iconify-icon>
                Cari Tiket</a>
        </div>
    </div>

    <?php if (!$tickets && !$bids): ?>
        <article class="sg-buyer-panel text-center py-5">
            <iconify-icon icon="ph:ticket" class="text-safegate-neon mb-3" style="font-size:54px;"></iconify-icon>
            <h2>Belum Ada Tiket</h2>
            <p class="text-safegate-text-sec mt-2 mb-4">Setelah pembayaran berhasil, tiket kamu akan muncul di dashboard
                ini.</p>
            <a href="index.php?page=penjualan" class="sg-buyer-btn is-neon">Buka Marketplace</a>
        </article>
    <?php elseif ($tickets): ?>
        <div class="sg-buyer-card-list">
            <?php foreach ($tickets as $ticket): ?>
                <article class="sg-buyer-ticket-card">
                    <img src="<?= sg_h(sg_event_image($ticket['title'], $ticket['image_path'] ?? '')) ?>"
                        alt="<?= sg_h($ticket['title']) ?>">
                    <div class="sg-buyer-ticket-body">
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <span class="sg-buyer-chip">Escrow <?= sg_h($ticket['escrow_status']) ?></span>
                            <span
                                class="sg-buyer-chip is-muted"><?= sg_h(str_replace('_', ' ', $ticket['buyer_ticket_status'] ?? 'pending_use')) ?></span>
                            <?php if (!empty($ticket['dispute_status'])): ?>
                                <span class="sg-buyer-chip is-danger">Dispute <?= sg_h($ticket['dispute_status']) ?></span>
                            <?php endif; ?>
                        </div>
                        <h2><?= sg_h($ticket['title']) ?></h2>
                        <p><?= sg_h($ticket['venue']) ?>, <?= sg_h($ticket['city']) ?> ·
                            <?= date('d M Y', strtotime($ticket['event_date'])) ?></p>
                        <div class="row g-2 mb-3">
                            <div class="col-4">
                                <div class="rounded-2 p-2" style="background:#090b10;"><small
                                        class="text-safegate-text-sec d-block">Section</small><strong><?= sg_h($ticket['section']) ?></strong>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="rounded-2 p-2" style="background:#090b10;"><small
                                        class="text-safegate-text-sec d-block">Row</small><strong><?= sg_h($ticket['row']) ?></strong>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="rounded-2 p-2" style="background:#090b10;"><small
                                        class="text-safegate-text-sec d-block">Seat</small><strong><?= sg_h($ticket['seat']) ?></strong>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div>
                                <span class="text-safegate-text-sec d-block" style="font-size:12px;">Total Payment</span>
                                <strong class="fs-5"><?= sg_rupiah($ticket['total_amount']) ?></strong>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <a class="sg-buyer-btn"
                                    href="index.php?page=transaction_detail&code=<?= urlencode($ticket['transaction_code']) ?>">Detail</a>
                                <?php if (($ticket['buyer_ticket_status'] ?? 'pending_use') === 'pending_use'): ?>
                                    <a class="sg-buyer-btn is-neon"
                                        href="index.php?page=ticket_verify&transaction_id=<?= (int) $ticket['transaction_id'] ?>">Verifikasi
                                        Tiket</a>
                                <?php else: ?>
                                    <a class="sg-buyer-btn"
                                        href="index.php?page=ticket_verify&transaction_id=<?= (int) $ticket['transaction_id'] ?>">Lihat
                                        Verifikasi</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($bids): ?>
        <div class="sg-buyer-titlebar" style="margin-top: <?= $tickets ? '4rem' : '1rem' ?>;">
            <div>
                <h2>Bidding Tickets</h2>
                <p>Status penawaran lelang tiket Anda saat ini.</p>
            </div>
        </div>
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <?php foreach ($bids as $bid):
                $isOutbid = $bid['bid_status'] === 'outbid';
                $isWinner = $bid['is_winning_bid'] == 1;
                $isListingActive = in_array($bid['listing_status'], ['active', 'promoted']);

                if ($isOutbid) {
                    $statusLabel = 'Kalah (Outbid)';
                    $statusColor = '#f03e3e';
                } elseif ($isWinner && $isListingActive) {
                    $statusLabel = 'Tertinggi Sementara';
                    $statusColor = 'var(--safegate-neon)';
                } elseif ($isWinner && !$isListingActive) {
                    if ($bid['bid_status'] === 'paid') {
                        $statusLabel = 'Menang (Lunas)';
                        $statusColor = 'var(--safegate-neon)';
                    } else {
                        $statusLabel = 'Menang (Belum Bayar)';
                        $statusColor = '#ffd98a';
                    }
                } else {
                    $statusLabel = 'Selesai';
                    $statusColor = 'var(--safegate-text-sec)';
                }
                ?>
                <article
                    style="display: flex; align-items: center; justify-content: space-between; padding: 1.25rem; background: #0c0e14; border: 1px solid #1a1d27; border-radius: 12px; opacity: <?= $isOutbid ? '0.7' : '1' ?>;">
                    <div style="display: flex; align-items: center; gap: 1.25rem; flex: 1;">
                        <div
                            style="width: 48px; height: 48px; background: rgba(200, 255, 0, 0.05); border: 1px solid rgba(200, 255, 0, 0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--safegate-neon);">
                            <iconify-icon icon="ph:ticket" style="font-size: 24px;"></iconify-icon>
                        </div>
                        <div>
                            <h3 style="margin: 0 0 4px 0; font-size: 1.1rem; color: #fff; font-weight: 600;">
                                <?= sg_h($bid['title']) ?></h3>
                            <p style="margin: 0; font-size: 0.85rem; color: var(--safegate-text-sec);">
                                <?= sg_h($bid['venue']) ?> · Sec <?= sg_h($bid['section']) ?>, Row <?= sg_h($bid['row']) ?>,
                                Seat <?= sg_h($bid['seat']) ?>
                            </p>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 2rem;">
                        <div style="text-align: right;">
                            <strong
                                style="font-size: 1.15rem; color: #fff; display: block;"><?= sg_rupiah($bid['bid_amount']) ?></strong>
                        </div>
                        <div style="width: 160px;">
                            <span
                                style="font-size: 0.85rem; font-weight: 600; color: <?= $statusColor ?>; display: block;"><?= $statusLabel ?></span>
                            <span
                                style="font-size: 0.75rem; color: var(--safegate-text-sec);"><?= $isListingActive ? 'Lelang Berlangsung' : 'Lelang Berakhir' ?></span>
                        </div>
                        <div>
                            <a href="index.php?page=detail_tiket&listing_id=<?= (int) $bid['listing_id'] ?>"
                                style="padding: 8px 16px; border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; color: #fff; text-decoration: none; font-size: 0.85rem; transition: 0.2s; white-space: nowrap;">Lihat
                                Lelang</a>
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