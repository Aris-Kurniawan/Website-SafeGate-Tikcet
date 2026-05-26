<?php
require_once __DIR__ . '/../../core/safegate_repository.php';

$page_title = 'Transaction History - SafeGate';
$dashboard_page = 'transaction';

$seller_id = sg_current_user_id();
$search = trim((string) ($_GET['q'] ?? ''));
$dateRange = $_GET['date_range'] ?? 'Last 30 Days';
$statusFilter = $_GET['status'] ?? 'All Status';
$currentPage = max(1, (int) ($_GET['p'] ?? 1));
$requestedPage = $currentPage;
$perPage = 10;
$ledger = sg_get_seller_transactions($seller_id, [
    'q' => $search,
    'date_range' => $dateRange,
    'status' => $statusFilter,
    'limit' => $perPage,
    'offset' => ($currentPage - 1) * $perPage,
]);
$summary = $ledger['summary'];
$transactions = $ledger['transactions'];
$totalTransactions = (int) ($ledger['total'] ?? count($transactions));
$totalPages = max(1, (int) ceil($totalTransactions / $perPage));
$currentPage = min($currentPage, $totalPages);
if ($requestedPage !== $currentPage && $totalTransactions > 0) {
    $ledger = sg_get_seller_transactions($seller_id, [
        'q' => $search,
        'date_range' => $dateRange,
        'status' => $statusFilter,
        'limit' => $perPage,
        'offset' => ($currentPage - 1) * $perPage,
    ]);
    $summary = $ledger['summary'];
    $transactions = $ledger['transactions'];
}

$transactionPageUrl = static function (int $page) use ($search, $dateRange, $statusFilter): string {
    return 'index.php?' . http_build_query([
        'page' => 'transaction',
        'q' => $search,
        'date_range' => $dateRange,
        'status' => $statusFilter,
        'p' => max(1, $page),
    ]);
};

ob_start();
?>

<section class="sg-transaction-page">
    <div class="sg-page-hero sg-transaction-hero">
        <div>
            <p class="sg-eyebrow">Institutional Ledger</p>
            <h1>Transaction<br>History</h1>
        </div>
        <div class="sg-ledger-total" aria-label="Transaction totals">
            <div>
                <span>Total Buy</span>
                <strong><?= $summary['buy'] ?></strong>
            </div>
            <div>
                <span>Total Sell</span>
                <strong class="text-safegate-neon"><?= $summary['sell'] ?></strong>
            </div>
        </div>
    </div>

    <form class="sg-filter-bar" action="index.php" method="get">
        <input type="hidden" name="page" value="transaction">
        <input type="hidden" name="p" value="1">
        <label class="sg-search-field">
            <iconify-icon icon="ph:magnifying-glass"></iconify-icon>
            <input type="search" name="q" value="<?= sg_h($search) ?>" placeholder="Search event name or ID..." autocomplete="off">
        </label>
        <label class="sg-select-field">
            <iconify-icon icon="ph:calendar-blank"></iconify-icon>
            <select name="date_range">
                <option <?= $dateRange === 'Last 30 Days' ? 'selected' : '' ?>>Last 30 Days</option>
                <option <?= $dateRange === 'Last 90 Days' ? 'selected' : '' ?>>Last 90 Days</option>
                <option <?= $dateRange === 'This Year' ? 'selected' : '' ?>>This Year</option>
            </select>
        </label>
        <label class="sg-select-field">
            <iconify-icon icon="ph:funnel-simple"></iconify-icon>
            <select name="status">
                <option <?= $statusFilter === 'All Status' ? 'selected' : '' ?>>All Status</option>
                <option <?= $statusFilter === 'Completed' ? 'selected' : '' ?>>Completed</option>
                <option <?= $statusFilter === 'Pending' ? 'selected' : '' ?>>Pending</option>
                <option <?= $statusFilter === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
            </select>
        </label>
        <button class="sg-icon-button" type="submit" aria-label="Apply transaction filter" title="Apply filter">
            <iconify-icon icon="ph:magnifying-glass-bold"></iconify-icon>
        </button>
        <a class="sg-icon-button" href="index.php?sg_action=export_seller_transactions&q=<?= urlencode($search) ?>&date_range=<?= urlencode($dateRange) ?>&status=<?= urlencode($statusFilter) ?>" aria-label="Download transactions" title="Download CSV" style="display:inline-flex; align-items:center; justify-content:center; text-decoration:none;">
            <iconify-icon icon="ph:download-simple"></iconify-icon>
        </a>
    </form>

    <section class="sg-transaction-card">
        <div class="sg-table-head">
            <span>Event Details</span>
            <span>Date</span>
            <span>Type</span>
            <span>Amount</span>
            <span>Status</span>
            <span>Actions</span>
        </div>

        <?php if (!$transactions): ?>
            <article class="sg-transaction-row sg-transaction-empty">
                <div>
                    <div>
                        <h2>Belum ada transaksi yang cocok</h2>
                        <p>Coba ubah search, date range, atau status filter.</p>
                    </div>
                </div>
            </article>
        <?php endif; ?>

        <?php foreach ($transactions as $transaction): ?>
            <article class="sg-transaction-row">
                <div class="sg-event-cell">
                    <div class="sg-ticket-thumb sg-ticket-thumb-<?= $transaction['thumb'] ?>" aria-hidden="true"></div>
                    <div>
                        <h2><?= htmlspecialchars($transaction['title']) ?></h2>
                        <p>ID: <?= htmlspecialchars($transaction['id']) ?></p>
                    </div>
                </div>
                <div class="sg-date-cell">
                    <strong><?= htmlspecialchars($transaction['date']) ?></strong>
                    <span><?= htmlspecialchars($transaction['time']) ?></span>
                </div>
                <div>
                    <span class="sg-type-pill <?= $transaction['type'] === 'SELL' ? 'is-sell' : 'is-buy' ?>"><?= htmlspecialchars($transaction['type']) ?></span>
                </div>
                <div class="sg-amount-cell">
                    <strong><?= htmlspecialchars($transaction['amount']) ?></strong>
                    <span><?= htmlspecialchars($transaction['note']) ?></span>
                </div>
                <div>
                    <span class="sg-status sg-status-<?= $transaction['status_class'] ?>">
                        <i></i><?= htmlspecialchars($transaction['status']) ?>
                    </span>
                </div>
                <div>
                    <a class="sg-details-button" href="index.php?page=transaction_detail&code=<?= urlencode($transaction['id']) ?>" style="display:inline-flex; align-items:center; justify-content:center; text-decoration:none;">View<br>Details</a>
                </div>
            </article>
        <?php endforeach; ?>

        <div class="sg-table-footer">
            <strong>
                <?php if ($totalTransactions > 0): ?>
                    Showing <?= (($currentPage - 1) * $perPage) + 1 ?> to <?= min($currentPage * $perPage, $totalTransactions) ?> of <?= $totalTransactions ?> transactions from database
                <?php else: ?>
                    Showing 0 transactions from database
                <?php endif; ?>
            </strong>
            <nav class="sg-pagination" aria-label="Transaction pages">
                <?php if ($currentPage > 1): ?>
                    <a href="<?= sg_h($transactionPageUrl($currentPage - 1)) ?>" aria-label="Previous page">&lsaquo;</a>
                <?php else: ?>
                    <button type="button" disabled>&lsaquo;</button>
                <?php endif; ?>

                <?php for ($pageNumber = 1; $pageNumber <= $totalPages; $pageNumber++): ?>
                    <a href="<?= sg_h($transactionPageUrl($pageNumber)) ?>" class="<?= $pageNumber === $currentPage ? 'is-active' : '' ?>"><?= $pageNumber ?></a>
                <?php endfor; ?>

                <?php if ($currentPage < $totalPages): ?>
                    <a href="<?= sg_h($transactionPageUrl($currentPage + 1)) ?>" aria-label="Next page">&rsaquo;</a>
                <?php else: ?>
                    <button type="button" disabled>&rsaquo;</button>
                <?php endif; ?>
            </nav>
        </div>
    </section>

    <p class="sg-ledger-footnote">
        <iconify-icon icon="ph:shield-check"></iconify-icon>
        All transactions are secured by blockchain-backed cryptographic proof and institutional-grade escrow protocols.
    </p>
</section>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/dashboard_layout.php';
?>
