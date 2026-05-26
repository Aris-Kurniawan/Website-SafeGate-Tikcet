<?php
// views/admin/transactions.php - Buku Besar Transaksi SafeGate Admin
$page_title = 'Global Transaction Ledger - SafeGate Admin';

require_once __DIR__ . '/../../core/admin_middleware.php';
require_once __DIR__ . '/../../core/safegate_repository.php';

$search_val = isset($_GET['search']) ? trim((string) $_GET['search']) : '';
$pay_status_val = $_GET['payment_status'] ?? 'all';
$escrow_status_val = $_GET['escrow_status'] ?? 'all';
$date_val = isset($_GET['date']) ? trim((string) $_GET['date']) : '';
$current_page = max(1, (int) ($_GET['p'] ?? 1));
$per_page = 10;
$transaction_filters = [
    'search' => $search_val,
    'payment_status' => $pay_status_val,
    'escrow_status' => $escrow_status_val,
    'date' => $date_val,
];
$total_transactions = sg_count_admin_transactions($transaction_filters);
$total_pages = max(1, (int) ceil($total_transactions / $per_page));
$current_page = min($current_page, $total_pages);
$transactions = sg_get_admin_transactions($transaction_filters + [
    'limit' => $per_page,
    'offset' => ($current_page - 1) * $per_page,
]);
$flash = sg_flash();

$admin_tx_page_url = static function (int $page) use ($search_val, $pay_status_val, $escrow_status_val, $date_val): string {
    return 'index.php?' . http_build_query([
        'page' => 'admin_transactions',
        'search' => $search_val,
        'payment_status' => $pay_status_val,
        'escrow_status' => $escrow_status_val,
        'date' => $date_val,
        'p' => max(1, $page),
    ]);
};

$volume_24h = 0;
$pending_count = 0;
$failed_count = 0;
foreach ($transactions as $transaction) {
    $volume_24h += (int) $transaction['total_amount'];
    if ($transaction['payment_status'] === 'pending' || $transaction['escrow_status'] === 'holding') {
        $pending_count++;
    }
    if ($transaction['payment_status'] === 'failed' || $transaction['payment_status'] === 'refunded') {
        $failed_count++;
    }
}

ob_start();
?>

<div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px;">
    <div class="sg-admin-title-section">
        <h1 style="margin: 0 0 4px;">Global Transaction Ledger</h1>
        <div style="color: var(--admin-text-muted); font-size: 13px; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;">
            Pantauan Mutasi Dana Real-Time
        </div>
    </div>
    <div class="sg-admin-status-live">
        <span class="sg-status-dot-live"></span>
        <span>Live Feed Active</span>
    </div>
</div>

<?php if ($flash): ?>
    <div style="margin-top: 18px; padding: 14px 16px; border-radius: 14px; font-weight: 800; font-size: 13px; color: <?= ($flash['type'] ?? 'success') === 'error' ? '#ff6868' : '#d9ff00' ?>; background: <?= ($flash['type'] ?? 'success') === 'error' ? 'rgba(255, 85, 85, .08)' : 'rgba(217, 255, 0, .08)' ?>; border: 1px solid <?= ($flash['type'] ?? 'success') === 'error' ? 'rgba(255, 85, 85, .2)' : 'rgba(217, 255, 0, .18)' ?>;">
        <?= sg_h($flash['message']) ?>
    </div>
<?php endif; ?>

<div class="sg-admin-kpi-grid" style="margin-top: 24px;">
    <div class="sg-admin-kpi-card">
        <div class="sg-admin-kpi-info">
            <h2 class="sg-admin-kpi-label">Visible Volume</h2>
            <div class="sg-admin-kpi-value" style="color: var(--admin-accent);"><?= sg_rupiah($volume_24h) ?></div>
            <div class="sg-admin-kpi-footer sg-admin-trend-up">
                <iconify-icon icon="ph:trend-up-bold"></iconify-icon>
                <span>From current filter result</span>
            </div>
        </div>
        <div class="sg-admin-kpi-icon"><iconify-icon icon="ph:chart-bar-fill"></iconify-icon></div>
    </div>

    <div class="sg-admin-kpi-card">
        <div class="sg-admin-kpi-info">
            <h2 class="sg-admin-kpi-label">Pending Settlements</h2>
            <div class="sg-admin-kpi-value" style="color: #00e5ff;"><?= $pending_count ?> Transaksi</div>
            <div class="sg-admin-kpi-footer" style="color: #00e5ff;">
                <iconify-icon icon="ph:clock-fill"></iconify-icon>
                <span>Needs escrow monitoring</span>
            </div>
        </div>
        <div class="sg-admin-kpi-icon"><iconify-icon icon="ph:hourglass-medium-fill"></iconify-icon></div>
    </div>

    <div class="sg-admin-kpi-card">
        <div class="sg-admin-kpi-info">
            <h2 class="sg-admin-kpi-label">Failed/Refunded</h2>
            <div class="sg-admin-kpi-value" style="color: var(--admin-danger);"><?= $failed_count ?> Transaksi</div>
            <div class="sg-admin-kpi-footer" style="color: var(--admin-danger);">
                <iconify-icon icon="ph:warning-fill"></iconify-icon>
                <span>Requires Manual Sync</span>
            </div>
        </div>
        <div class="sg-admin-kpi-icon"><iconify-icon icon="ph:x-circle-fill"></iconify-icon></div>
    </div>
</div>

<form method="GET" action="index.php" class="sg-admin-filter-bar" style="margin-top: 24px;">
    <input type="hidden" name="page" value="admin_transactions">
    <input type="hidden" name="p" value="1">

    <div class="sg-admin-filter-input-group">
        <iconify-icon icon="ph:magnifying-glass"></iconify-icon>
        <input type="text" name="search" placeholder="Search TX-ID, Buyer Email..." value="<?= sg_h($search_val) ?>">
    </div>

    <select name="payment_status" class="sg-admin-filter-select">
        <option value="all" <?= $pay_status_val === 'all' ? 'selected' : '' ?>>Payment Status: All</option>
        <option value="paid" <?= $pay_status_val === 'paid' ? 'selected' : '' ?>>Paid</option>
        <option value="failed" <?= $pay_status_val === 'failed' ? 'selected' : '' ?>>Failed</option>
        <option value="pending" <?= $pay_status_val === 'pending' ? 'selected' : '' ?>>Pending</option>
        <option value="refunded" <?= $pay_status_val === 'refunded' ? 'selected' : '' ?>>Refunded</option>
    </select>

    <select name="escrow_status" class="sg-admin-filter-select">
        <option value="all" <?= $escrow_status_val === 'all' ? 'selected' : '' ?>>Escrow Status: All</option>
        <option value="released" <?= $escrow_status_val === 'released' ? 'selected' : '' ?>>Released</option>
        <option value="holding" <?= $escrow_status_val === 'holding' ? 'selected' : '' ?>>Holding</option>
        <option value="refunded" <?= $escrow_status_val === 'refunded' ? 'selected' : '' ?>>Refunded</option>
        <option value="disputed" <?= $escrow_status_val === 'disputed' ? 'selected' : '' ?>>Disputed</option>
    </select>

    <input type="date" name="date" class="sg-admin-filter-date" value="<?= sg_h($date_val) ?>">

    <a class="sg-admin-btn-export-csv" href="index.php?sg_action=export_transactions&search=<?= urlencode($search_val) ?>&payment_status=<?= urlencode($pay_status_val) ?>&escrow_status=<?= urlencode($escrow_status_val) ?>&date=<?= urlencode($date_val) ?>" style="text-decoration:none;">
        <iconify-icon icon="ph:download-simple-bold"></iconify-icon>
        <span>Export to CSV</span>
    </a>

    <button type="submit" class="sg-admin-btn-apply-filter">
        <span>Apply Filter</span>
    </button>
</form>

<div class="sg-admin-table-panel" style="margin-top: 24px;">
    <div class="sg-admin-table-responsive">
        <table class="sg-admin-table">
            <thead>
                <tr>
                    <th>TX ID & Date</th>
                    <th>Entities</th>
                    <th>Event</th>
                    <th>Amount & Fee</th>
                    <th>Payment</th>
                    <th>Escrow</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!$transactions): ?>
                    <tr>
                        <td colspan="7" style="padding: 28px; color: var(--admin-text-muted);">Belum ada transaksi di database. Data akan muncul otomatis setelah tabel `transactions` terisi.</td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($transactions as $transaction): ?>
                    <tr>
                        <td class="sg-admin-timestamp" style="font-weight: 700; color: #FFF;">
                            <?= sg_h($transaction['transaction_code']) ?>
                            <span style="display: block; font-size: 11px; font-weight: 500; color: var(--admin-text-muted); margin-top: 4px;"><?= date('d M Y, H:i', strtotime($transaction['created_at'])) ?></span>
                        </td>
                        <td>
                            <a href="mailto:<?= sg_h($transaction['buyer_email']) ?>" class="sg-admin-entity-link"><?= sg_h($transaction['buyer_email']) ?></a>
                            <span class="sg-admin-entity-arrow">-&gt;</span>
                            <span class="sg-admin-entity-merchant"><?= sg_h($transaction['seller_name']) ?></span>
                        </td>
                        <td style="color: #E2E8F0; font-weight: 600;"><?= sg_h($transaction['event_title']) ?></td>
                        <td class="sg-admin-amount-fee">
                            <strong style="color: #FFF; font-size: 15px;"><?= sg_rupiah($transaction['total_amount']) ?></strong>
                            <span class="sg-fee-text">Fee: <?= sg_rupiah($transaction['platform_revenue']) ?></span>
                        </td>
                        <td><span class="sg-badge-status-dot is-<?= sg_h($transaction['payment_status']) ?>"><?= sg_h(ucwords($transaction['payment_status'])) ?></span></td>
                        <td><span class="sg-badge-status-dot is-<?= $transaction['escrow_status'] === 'holding' ? 'held' : sg_h($transaction['escrow_status']) ?>"><?= sg_h(ucwords($transaction['escrow_status'])) ?></span></td>
                        <td>
                            <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                                <a class="sg-admin-btn-more" aria-label="View Details" href="index.php?page=transaction_detail&code=<?= urlencode($transaction['transaction_code']) ?>" style="display:inline-flex; align-items:center; justify-content:center; text-decoration:none;">
                                    <iconify-icon icon="ph:eye-fill"></iconify-icon>
                                </a>
                                <?php if ($transaction['payment_status'] === 'paid' && in_array($transaction['escrow_status'], ['holding', 'disputed'], true)): ?>
                                    <form action="index.php?page=admin_transactions" method="post" style="margin:0;">
                                        <input type="hidden" name="sg_action" value="admin_settle_transaction">
                                        <input type="hidden" name="transaction_id" value="<?= (int) $transaction['id'] ?>">
                                        <input type="hidden" name="decision" value="release">
                                        <button type="submit" class="sg-admin-btn-action is-green" style="padding:8px 10px; font-size:11px;" onclick="return confirm('Lepas escrow transaksi <?= sg_h($transaction['transaction_code']) ?> ke seller?')">Release</button>
                                    </form>
                                    <form action="index.php?page=admin_transactions" method="post" style="margin:0;">
                                        <input type="hidden" name="sg_action" value="admin_settle_transaction">
                                        <input type="hidden" name="transaction_id" value="<?= (int) $transaction['id'] ?>">
                                        <input type="hidden" name="decision" value="refund">
                                        <button type="submit" class="sg-admin-btn-action is-peach" style="padding:8px 10px; font-size:11px;" onclick="return confirm('Refund transaksi <?= sg_h($transaction['transaction_code']) ?> ke buyer?')">Refund</button>
                                    </form>
                                <?php else: ?>
                                    <span style="font-size:11px; font-weight:800; color:var(--admin-text-muted); text-transform:uppercase;">Final</span>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="sg-admin-table-footer-row">
        <div class="sg-admin-record-count">
            <?php if ($total_transactions > 0): ?>
                Displaying <?= (($current_page - 1) * $per_page) + 1 ?>-<?= min($current_page * $per_page, $total_transactions) ?> of <?= $total_transactions ?> records
            <?php else: ?>
                Displaying 0 records
            <?php endif; ?>
        </div>
        <div class="sg-admin-pagination">
            <?php if ($current_page > 1): ?>
                <a href="<?= sg_h($admin_tx_page_url($current_page - 1)) ?>" class="sg-admin-page-btn" aria-label="Previous page"><iconify-icon icon="ph:caret-left-bold"></iconify-icon></a>
            <?php else: ?>
                <button type="button" class="sg-admin-page-btn is-disabled" aria-label="Previous page"><iconify-icon icon="ph:caret-left-bold"></iconify-icon></button>
            <?php endif; ?>

            <?php for ($page_number = 1; $page_number <= $total_pages; $page_number++): ?>
                <a href="<?= sg_h($admin_tx_page_url($page_number)) ?>" class="sg-admin-page-btn <?= $page_number === $current_page ? 'is-active' : '' ?>"><?= $page_number ?></a>
            <?php endfor; ?>

            <?php if ($current_page < $total_pages): ?>
                <a href="<?= sg_h($admin_tx_page_url($current_page + 1)) ?>" class="sg-admin-page-btn" aria-label="Next page"><iconify-icon icon="ph:caret-right-bold"></iconify-icon></a>
            <?php else: ?>
                <button type="button" class="sg-admin-page-btn is-disabled" aria-label="Next page"><iconify-icon icon="ph:caret-right-bold"></iconify-icon></button>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/admin_layout.php';
?>
