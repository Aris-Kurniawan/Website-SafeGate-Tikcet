<?php
require_once __DIR__ . '/../../core/safegate_repository.php';

$page_title = 'Verifikasi Tiket - SafeGate';
$buyer_page = 'my_tickets';
$buyer_id = sg_current_user_id();
$transaction_id = (int) ($_GET['transaction_id'] ?? 0);
$ticket = sg_get_buyer_ticket_for_verification($transaction_id, (int) $buyer_id);
$flash = sg_flash();

ob_start();
?>

<section class="sg-buyer-content">
    <?php if ($flash): ?><div class="sg-buyer-notice"><?= sg_h($flash['message']) ?></div><?php endif; ?>

    <div class="sg-buyer-titlebar">
        <div>
            <h1>Verifikasi Tiket</h1>
            <p>Konfirmasi apakah tiket bisa digunakan. Jika bermasalah, dana tetap ditahan dan laporan dikirim ke admin.</p>
        </div>
        <a class="sg-buyer-btn" href="index.php?page=my_tickets">Kembali</a>
    </div>

    <?php if (!$ticket): ?>
        <article class="sg-buyer-panel text-center py-5">
            <h2>Tiket Tidak Ditemukan</h2>
            <p class="text-safegate-text-sec mt-2">Tiket ini tidak ada di akun pembeli kamu.</p>
        </article>
    <?php else: ?>
        <div class="sg-buyer-grid-2">
            <article class="sg-buyer-panel">
                <img src="<?= sg_h(sg_event_image($ticket['title'], $ticket['image_path'] ?? '')) ?>" alt="<?= sg_h($ticket['title']) ?>" class="w-100 rounded-3 mb-4" style="max-height:320px; object-fit:cover;">
                <span class="sg-buyer-chip"><?= sg_h($ticket['transaction_code']) ?></span>
                <h2 class="mt-3"><?= sg_h($ticket['title']) ?></h2>
                <p class="text-safegate-text-sec"><?= sg_h($ticket['venue']) ?>, <?= sg_h($ticket['city']) ?> · <?= date('d M Y H:i', strtotime($ticket['event_date'])) ?></p>
                <div class="row g-2 mt-3">
                    <div class="col-4"><div class="rounded-2 p-3" style="background:#090b10;"><small class="text-safegate-text-sec d-block">Section</small><strong><?= sg_h($ticket['section']) ?></strong></div></div>
                    <div class="col-4"><div class="rounded-2 p-3" style="background:#090b10;"><small class="text-safegate-text-sec d-block">Row</small><strong><?= sg_h($ticket['row']) ?></strong></div></div>
                    <div class="col-4"><div class="rounded-2 p-3" style="background:#090b10;"><small class="text-safegate-text-sec d-block">Seat</small><strong><?= sg_h($ticket['seat']) ?></strong></div></div>
                </div>
            </article>

            <aside class="sg-buyer-panel">
                <h2>Status Penggunaan</h2>
                <p class="text-safegate-text-sec mt-2">Status saat ini: <span class="sg-buyer-chip"><?= sg_h(str_replace('_', ' ', $ticket['buyer_ticket_status'])) ?></span></p>

                <?php if ($ticket['buyer_ticket_status'] === 'confirmed_used'): ?>
                    <div class="sg-buyer-notice mt-4">Tiket sudah dikonfirmasi valid. Escrow dilepas ke seller.</div>
                <?php elseif ($ticket['buyer_ticket_status'] === 'reported_issue'): ?>
                    <?php if ($ticket['escrow_status'] === 'released'): ?>
                        <div class="sg-buyer-notice mt-4" style="background:rgba(255,185,0,.08);border-color:rgba(255,185,0,.2);color:#ffb900;">Sengketa Selesai: Keputusan Admin menyatakan tiket valid, dana dilepas ke seller.</div>
                    <?php elseif ($ticket['escrow_status'] === 'refunded'): ?>
                        <div class="sg-buyer-notice mt-4" style="background:rgba(217,255,0,.08);border-color:rgba(217,255,0,.2);color:var(--safegate-neon);">Sengketa Selesai: Keputusan Admin menyetujui klaim Anda. Dana di-refund ke saldo dompet pembeli Anda.</div>
                    <?php else: ?>
                        <div class="sg-buyer-notice mt-4" style="background:rgba(255,88,88,.08);border-color:rgba(255,88,88,.2);color:#ff6868;">Laporan masalah sudah dikirim. Admin akan meninjau sengketa.</div>
                    <?php endif; ?>
                <?php else: ?>
                    <form class="sg-buyer-form mt-4" action="index.php?page=ticket_verify&transaction_id=<?= (int) $ticket['id'] ?>" method="post">
                        <input type="hidden" name="sg_action" value="buyer_confirm_ticket">
                        <input type="hidden" name="transaction_id" value="<?= (int) $ticket['id'] ?>">
                        <button type="submit" class="sg-buyer-btn is-neon w-100" onclick="return confirm('Konfirmasi tiket valid dan lepaskan escrow ke seller?')">Tiket Bisa Digunakan</button>
                    </form>

                    <form class="sg-buyer-form mt-4" action="index.php?page=ticket_verify&transaction_id=<?= (int) $ticket['id'] ?>" method="post">
                        <input type="hidden" name="sg_action" value="buyer_report_ticket">
                        <input type="hidden" name="transaction_id" value="<?= (int) $ticket['id'] ?>">
                        <label class="sg-buyer-panel-label mb-2 d-block">Laporan Masalah</label>
                        <textarea name="buyer_claim" minlength="12" required placeholder="Contoh: tiket tidak bisa discan di gate / QR invalid / detail tiket tidak sesuai."></textarea>
                        <button type="submit" class="sg-buyer-btn w-100 mt-3" onclick="return confirm('Kirim laporan dan tahan escrow untuk investigasi admin?')">Laporkan & Minta Refund</button>
                    </form>
                <?php endif; ?>
            </aside>
        </div>
    <?php endif; ?>
</section>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/buyer_layout.php';
?>
