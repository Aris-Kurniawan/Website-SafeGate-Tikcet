<?php
require_once __DIR__ . '/../../core/safegate_repository.php';

$page_title = 'Dompet & Keuangan - SafeGate';
$buyer_page = 'buyer_wallet';
$buyer_id = sg_current_user_id();
$wallet = sg_get_buyer_wallet_summary($buyer_id);
$user_notifications = sg_get_notifications($buyer_id, 3);
$flash = sg_flash();

$activity_labels = [
    'top_up' => 'Top Up Saldo',
    'withdrawal' => 'Tarik Saldo',
    'bid_deposit_lock' => 'Uang Jaminan',
    'bid_deposit_refund' => 'Jaminan Kembali',
    'bid_deposit_forfeit' => 'Jaminan Hangus',
];

$activity_descriptions = [
    'top_up' => 'Bank Transfer',
    'withdrawal' => 'Transfer ke rekening tujuan',
    'bid_deposit_lock' => 'Jaminan lelang dikunci',
    'bid_deposit_refund' => 'Kalah lelang (unlocked)',
    'bid_deposit_forfeit' => 'Deposit lelang forfeited',
];

ob_start();
?>

<section class="sg-buyer-content">
    <?php if ($flash): ?><div class="sg-buyer-notice"><?= sg_h($flash['message']) ?></div><?php endif; ?>

    <div class="sg-buyer-titlebar">
        <div>
            <h1>Dompet & Keuangan</h1>
            <p>Kelola saldo aktif dan pantau jaminan lelang kamu secara real-time.</p>
        </div>
        <div class="sg-buyer-actions">
            <button class="sg-buyer-btn" type="button" onclick="document.getElementById('withdraw-form').scrollIntoView({behavior:'smooth'})">Tarik Saldo</button>
            <button class="sg-buyer-btn is-neon" type="button" onclick="document.getElementById('topup-form').scrollIntoView({behavior:'smooth'})">Top Up</button>
        </div>
    </div>

    <div class="sg-wallet-summary-grid mb-4">
        <article class="sg-buyer-kpi">
            <span>Saldo Aktif</span>
            <strong><?= sg_rupiah($wallet['available']) ?></strong>
            <small>+2.4% vs bulan lalu</small>
        </article>
        <article class="sg-buyer-kpi">
            <span>Saldo Ditahan / Escrow</span>
            <strong><?= sg_rupiah($wallet['held']) ?></strong>
            <small>Saldo ini dilindungi selama lelang aktif</small>
        </article>
        <article class="sg-buyer-kpi sg-wallet-recent-card">
            <span>Aktivitas Terbaru</span>
            <div class="sg-wallet-recent-list">
                <?php foreach (array_slice($user_notifications, 0, 2) as $notification):
                    $type = $notification['type'] ?? '';
                    $isRead = (int) ($notification['is_read'] ?? 0);
                    
                    $itemClass = '';
                    if ($type === 'auction_won' || $type === 'payment_success' || $type === 'kyc_approved' || $type === 'escrow_released') {
                        $itemClass = 'is-success';
                    } elseif ($type === 'auction_lost' || $type === 'kyc_rejected') {
                        $itemClass = 'is-danger';
                    } elseif ($type === 'bid_placed' || $type === 'dispute_opened') {
                        $itemClass = 'is-warning';
                    } else {
                        $itemClass = 'is-info';
                    }
                    
                    if ($isRead) {
                        $itemClass .= ' is-read';
                    }
                ?>
                    <?php if ($type === 'auction_won'): ?>
                        <a href="index.php?page=pembayaran&listing_id=<?= (int) $notification['related_id'] ?>" class="<?= $itemClass ?>" style="text-decoration: none; color: inherit; display: grid; grid-template-columns: 9px minmax(0, 1fr); align-items: start; column-gap: 12px; margin: 0; padding: 0;">
                    <?php else: ?>
                        <p class="<?= $itemClass ?>">
                    <?php endif; ?>
                        <i></i>
                        <span>
                            <strong><?= sg_h($notification['title']) ?></strong>
                            <small><?= sg_h($notification['body']) ?></small>
                            <em><?= strtoupper(sg_time_ago($notification['created_at'])) ?></em>
                        </span>
                    <?php if ($type === 'auction_won'): ?>
                        </a>
                    <?php else: ?>
                        </p>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php if (!$user_notifications): ?>
                    <p class="is-empty"><i></i><span><strong>Belum ada notifikasi</strong><small>Aktivitas lelang, KYC, dan tiket akan muncul di sini.</small><em>MENUNGGU AKTIVITAS</em></span></p>
                <?php endif; ?>
            </div>
            <a href="index.php?page=buyer_dashboard">Lihat Log Lengkap +</a>
        </article>
    </div>

    <div class="sg-wallet-action-grid mb-4">
        <form id="topup-form" class="sg-buyer-panel sg-buyer-form sg-wallet-action-panel" action="index.php?page=buyer_wallet" method="post">
            <input type="hidden" name="sg_action" value="buyer_wallet_topup">
            <h2>Top Up Saldo Jaminan</h2>
            <p class="text-safegate-text-sec mt-2">Saldo ini dipakai sebagai uang jaminan ketika mengikuti lelang.</p>
            <label class="sg-buyer-panel-label mt-3 mb-2 d-block">Nominal Top Up</label>
            <input type="text" name="amount" inputmode="numeric" placeholder="Rp 100.000" required>
            <div class="sg-wallet-action-spacer" aria-hidden="true"></div>
            <button class="sg-buyer-btn is-neon w-100 mt-3" type="submit">Top Up Saldo</button>
        </form>

        <form id="withdraw-form" class="sg-buyer-panel sg-buyer-form sg-wallet-action-panel sg-wallet-withdraw-panel" action="index.php?page=buyer_wallet" method="post">
            <input type="hidden" name="sg_action" value="buyer_wallet_withdraw">
            <h2>Tarik Saldo</h2>
            <p class="text-safegate-text-sec mt-2">Pencairan saldo aktif ke bank/e-wallet.</p>
            <label class="sg-buyer-panel-label mt-3 mb-2 d-block">Tujuan</label>
            <input type="text" name="destination" placeholder="Bank BCA / DANA / GoPay" required>
            <label class="sg-buyer-panel-label mt-3 mb-2 d-block">Nominal</label>
            <input type="text" name="amount" inputmode="numeric" placeholder="Rp 60.000" required>
            <div class="sg-wallet-action-spacer" aria-hidden="true"></div>
            <button class="sg-buyer-btn w-100 mt-3" type="submit">Ajukan Tarik Saldo</button>
        </form>
    </div>

    <article class="sg-buyer-panel sg-wallet-ledger" id="wallet-log">
        <div class="d-flex align-items-center justify-content-between gap-3">
            <h2>Aktivitas Keuangan Terbaru</h2>
            <span class="sg-wallet-filter"><iconify-icon icon="ph:funnel-simple"></iconify-icon> Filter</span>
        </div>
        <table class="sg-buyer-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Jenis Aktivitas</th>
                    <th>Deskripsi</th>
                    <th class="text-end">Nominal</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($wallet['activities']): ?>
                    <?php foreach ($wallet['activities'] as $activity): ?>
                        <?php
                            $sign = in_array($activity['direction'], ['credit', 'release'], true) ? '+' : '-';
                            $label = $activity_labels[$activity['type']] ?? ucwords(str_replace('_', ' ', $activity['type']));
                            $description = $activity['description'] ?: ($activity['event_title'] ?? ($activity_descriptions[$activity['type']] ?? 'Aktivitas wallet'));
                        ?>
                        <tr>
                            <td><?= date('d M Y', strtotime($activity['created_at'])) ?></td>
                            <td><span class="sg-wallet-activity-chip <?= $activity['direction'] === 'hold' ? 'is-muted' : '' ?>"><?= sg_h($label) ?></span></td>
                            <td><?= sg_h($description) ?></td>
                            <td class="text-end fw-bold sg-wallet-amount <?= $sign === '+' ? 'is-plus' : '' ?>"><?= $sign ?><?= sg_rupiah($activity['amount']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" class="text-center text-safegate-text-sec py-5">Belum ada aktivitas wallet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </article>
</section>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/buyer_layout.php';
?>
