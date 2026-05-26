<?php
require_once __DIR__ . '/../../core/safegate_repository.php';

$page_title = 'Tiket Saya - SafeGate';
$buyer_id = sg_current_user_id();
$tickets = sg_get_buyer_tickets($buyer_id);
$notifications = sg_get_notifications($buyer_id, 4);
$unread_notifications = sg_unread_notification_count($buyer_id);
$flash = sg_flash();

ob_start();
?>

<section class="container mx-auto py-5" style="max-width: 1200px; padding-left: 1.5rem; padding-right: 1.5rem; margin-top: 3rem; margin-bottom: 5rem;">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-4 mb-5">
        <div>
            <p class="text-safegate-neon fw-bold text-uppercase mb-2" style="font-size: .75rem; letter-spacing: .12em;">Buyer Vault</p>
            <h1 class="display-5 fw-bold text-white mb-3 letter-spacing-tight">Tiket Saya</h1>
            <p class="text-safegate-text-sec mb-0" style="max-width: 38rem;">Daftar tiket yang sudah kamu beli lewat escrow SafeGate.</p>
        </div>
        <a href="index.php?page=penjualan" class="btn btn-safegate-neon rounded-pill fw-bold px-4 py-2">Cari Tiket</a>
    </div>

    <?php if ($flash): ?>
        <div class="rounded-4 p-3 mb-4" style="background: rgba(217,255,0,.08); border: 1px solid rgba(217,255,0,.18); color: var(--safegate-neon); font-weight: 700;">
            <?= sg_h($flash['message']) ?>
        </div>
    <?php endif; ?>

    <?php if ($notifications): ?>
        <div class="sg-glass rounded-4 p-4 mb-4" style="border: 1px solid rgba(255,255,255,.07);">
            <div class="d-flex justify-content-between align-items-center gap-3 mb-3">
                <h2 class="h5 fw-bold text-white mb-0">Notifikasi</h2>
                <?php if ($unread_notifications > 0): ?>
                    <a href="index.php?sg_action=mark_notifications_read" class="text-safegate-neon fw-bold text-decoration-none" style="font-size:.75rem;">Tandai dibaca</a>
                <?php endif; ?>
            </div>
            <div class="row g-3">
                <?php foreach ($notifications as $notification): ?>
                    <div class="col-12 col-md-6">
                        <div class="rounded-3 p-3 h-100" style="background: rgba(9,11,16,.35); border:1px solid <?= (int) $notification['is_read'] ? 'rgba(255,255,255,.06)' : 'rgba(217,255,0,.22)' ?>;">
                            <div class="d-flex align-items-start gap-3">
                                <iconify-icon icon="ph:bell-ringing" class="text-safegate-neon fs-5"></iconify-icon>
                                <div>
                                    <strong class="text-white d-block"><?= sg_h($notification['title']) ?></strong>
                                    <span class="text-safegate-text-sec" style="font-size:.82rem;"><?= sg_h($notification['body']) ?></span>
                                    <small class="d-block text-safegate-text-sec mt-1"><?= sg_h(sg_time_ago($notification['created_at'])) ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!$tickets): ?>
        <div class="sg-glass rounded-4 p-5 text-center">
            <iconify-icon icon="ph:ticket" class="text-safegate-neon display-2 mb-3"></iconify-icon>
            <h2 class="h4 fw-bold text-white mb-2">Belum Ada Tiket</h2>
            <p class="text-safegate-text-sec mb-4">Setelah pembayaran berhasil, tiket kamu akan muncul di sini.</p>
            <a href="index.php?page=penjualan" class="btn btn-outline-safegate-neon rounded-pill fw-bold px-4">Buka Marketplace</a>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($tickets as $ticket): ?>
                <div class="col-12 col-lg-6">
                    <article class="sg-glass rounded-4 overflow-hidden h-100" style="border: 1px solid rgba(255,255,255,.07);">
                        <div class="row g-0 h-100">
                            <div class="col-12 col-md-4">
                                <img src="<?= sg_h(sg_event_image($ticket['title'], $ticket['image_path'] ?? '')) ?>" alt="<?= sg_h($ticket['title']) ?>" class="w-100 h-100 object-fit-cover" style="min-height: 180px;">
                            </div>
                            <div class="col-12 col-md-8 p-4 d-flex flex-column">
                                <div class="d-flex justify-content-between gap-3 mb-3">
                                    <span class="badge rounded-pill text-black bg-safegate-neon fw-bold">ESCROW <?= sg_h(strtoupper($ticket['escrow_status'])) ?></span>
                                    <span class="text-safegate-text-sec fw-bold" style="font-size:.72rem;"><?= sg_h($ticket['transaction_code']) ?></span>
                                </div>
                                <h2 class="h4 fw-bold text-white mb-2"><?= sg_h($ticket['title']) ?></h2>
                                <p class="text-safegate-text-sec mb-3" style="font-size:.9rem;">
                                    <iconify-icon icon="ph:map-pin"></iconify-icon>
                                    <?= sg_h($ticket['venue']) ?>, <?= sg_h($ticket['city']) ?>
                                </p>
                                <div class="row g-2 mb-4">
                                    <div class="col-4"><div class="rounded-3 p-2 bg-black bg-opacity-25"><small class="text-safegate-text-sec d-block">Section</small><strong><?= sg_h($ticket['section']) ?></strong></div></div>
                                    <div class="col-4"><div class="rounded-3 p-2 bg-black bg-opacity-25"><small class="text-safegate-text-sec d-block">Row</small><strong><?= sg_h($ticket['row']) ?></strong></div></div>
                                    <div class="col-4"><div class="rounded-3 p-2 bg-black bg-opacity-25"><small class="text-safegate-text-sec d-block">Seat</small><strong><?= sg_h($ticket['seat']) ?></strong></div></div>
                                </div>
                                <div class="mt-auto d-flex justify-content-between align-items-end gap-3">
                                    <div>
                                        <small class="text-safegate-text-sec d-block">Total Payment</small>
                                        <strong class="text-white fs-5"><?= sg_rupiah($ticket['total_amount']) ?></strong>
                                    </div>
                                    <a class="btn btn-outline-safegate-neon rounded-pill fw-bold px-3" href="index.php?page=transaction_detail&code=<?= urlencode($ticket['transaction_code']) ?>">Detail</a>
                                </div>

                                <div class="mt-4 pt-3" style="border-top:1px solid rgba(255,255,255,.08);">
                                    <?php if (!empty($ticket['dispute_status'])): ?>
                                        <div class="rounded-3 px-3 py-2 fw-bold" style="background:rgba(0,229,255,.08); border:1px solid rgba(0,229,255,.2); color:#00e5ff; font-size:.82rem;">
                                            Dispute status: <?= sg_h(strtoupper(str_replace('_', ' ', $ticket['dispute_status']))) ?>
                                        </div>
                                    <?php elseif (in_array($ticket['escrow_status'], ['holding', 'disputed'], true)): ?>
                                        <details class="mt-1">
                                            <summary class="text-safegate-neon fw-bold" style="cursor:pointer; font-size:.85rem;">Ajukan Dispute</summary>
                                            <form class="mt-3" action="index.php?page=my_tickets" method="post">
                                                <input type="hidden" name="sg_action" value="open_dispute">
                                                <input type="hidden" name="transaction_id" value="<?= (int) $ticket['transaction_id'] ?>">
                                                <textarea name="buyer_claim" class="form-control bg-black bg-opacity-25 border-secondary text-white rounded-3 mb-3" rows="3" minlength="12" required placeholder="Contoh: tiket tidak bisa dipakai / detail tiket tidak sesuai..."></textarea>
                                                <button type="submit" class="btn btn-outline-danger rounded-pill fw-bold px-3 py-2" onclick="return confirm('Buka dispute untuk transaksi ini? Escrow akan ditandai disputed sampai admin meninjau.')">Kirim Dispute</button>
                                            </form>
                                        </details>
                                    <?php else: ?>
                                        <small class="text-safegate-text-sec">Escrow sudah selesai, dispute tidak tersedia.</small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/public_layout.php';
?>
