<?php
require_once __DIR__ . '/../../core/safegate_repository.php';

$page_title = 'Riwayat Pembelian Tiket - SafeGate';
$buyer_page = 'buyer_transactions';
$buyer_id = sg_current_user_id();
$rows = sg_get_buyer_transaction_rows($buyer_id);

ob_start();
?>

<section class="sg-buyer-content">
    <div class="sg-buyer-titlebar">
        <div>
            <h1>Riwayat Pembelian Tiket</h1>
            <p>Daftar tiket yang pernah kamu beli, lengkap dengan status pembayaran dan escrow.</p>
        </div>
        <div class="sg-buyer-actions">
            <a class="sg-buyer-btn is-neon" href="index.php?page=penjualan">Beli Tiket</a>
        </div>
    </div>

    <article class="sg-buyer-panel">
        <table class="sg-buyer-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Event</th>
                    <th>Status</th>
                    <th>Escrow</th>
                    <th class="text-end">Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($rows): ?>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><strong><?= sg_h($row['transaction_code']) ?></strong><br><span class="text-safegate-text-sec"><?= date('d M Y, H:i', strtotime($row['created_at'])) ?></span></td>
                            <td><?= sg_h($row['title']) ?><br><span class="text-safegate-text-sec"><?= sg_h($row['venue']) ?>, <?= sg_h($row['city']) ?></span></td>
                            <td><span class="sg-buyer-chip"><?= sg_h($row['payment_status']) ?></span></td>
                            <td><span class="sg-buyer-chip is-muted"><?= sg_h($row['escrow_status']) ?></span></td>
                            <td class="text-end fw-bold"><?= sg_rupiah($row['total_amount']) ?></td>
                            <td><a class="sg-buyer-btn" href="index.php?page=transaction_detail&code=<?= urlencode($row['transaction_code']) ?>">Detail</a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center text-safegate-text-sec py-5">Belum ada tiket yang dibeli dari akun ini.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </article>
</section>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/buyer_layout.php';
?>
