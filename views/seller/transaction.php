<?php
$page_title = 'Transaction History - SafeGate';
$dashboard_page = 'transaction';

$summary = [
    'buy' => 'Rp. 12,450.000',
    'sell' => 'Rp.8.290.000',
];

$transactions = [
    [
        'title' => 'Neon Pulse Music Festival',
        'id' => 'SG-TX-882190',
        'date' => 'Oct 24, 2023',
        'time' => '14:22 PM',
        'type' => 'BUY',
        'amount' => 'Rp.450.000',
        'note' => 'INCL. PROCESSING',
        'status' => 'COMPLETED',
        'status_class' => 'completed',
        'thumb' => 'neon',
    ],
    [
        'title' => 'NBA Finals: Game 7',
        'id' => 'SG-TX-773124',
        'date' => 'Oct 24, 2023',
        'time' => '14:22 PM',
        'type' => 'SELL',
        'amount' => 'Rp.1.200.000',
        'note' => 'ESCROW LOCKED',
        'status' => 'PENDING',
        'status_class' => 'pending',
        'thumb' => 'ball',
    ],
    [
        'title' => 'The Phantom of the Opera',
        'id' => 'SG-TX-666091',
        'date' => 'Oct 24, 2023',
        'time' => '14:22 PM',
        'type' => 'BUY',
        'amount' => 'Rp.225.000',
        'note' => 'REFUND INITIATED',
        'status' => 'CANCELLED',
        'status_class' => 'cancelled',
        'thumb' => 'opera',
    ],
    [
        'title' => 'Premier League: London Derby',
        'id' => 'SG-TX-551229',
        'date' => 'Oct 24, 2023',
        'time' => '14:22 PM',
        'type' => 'BUY',
        'amount' => 'Rp.1.800.000',
        'note' => 'VERIFIED DIRECT',
        'status' => 'COMPLETED',
        'status_class' => 'completed',
        'thumb' => 'stadium',
    ],
];

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

    <form class="sg-filter-bar" action="#" method="get">
        <label class="sg-search-field">
            <iconify-icon icon="ph:magnifying-glass"></iconify-icon>
            <input type="search" name="q" placeholder="Search event name or ID...">
        </label>
        <label class="sg-select-field">
            <iconify-icon icon="ph:calendar-blank"></iconify-icon>
            <select name="date_range">
                <option>Last 30 Days</option>
                <option>Last 90 Days</option>
                <option>This Year</option>
            </select>
        </label>
        <label class="sg-select-field">
            <iconify-icon icon="ph:funnel-simple"></iconify-icon>
            <select name="status">
                <option>All Status</option>
                <option>Completed</option>
                <option>Pending</option>
                <option>Cancelled</option>
            </select>
        </label>
        <button class="sg-icon-button" type="button" aria-label="Download transactions">
            <iconify-icon icon="ph:download-simple"></iconify-icon>
        </button>
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
                    <button class="sg-details-button" type="button">View<br>Details</button>
                </div>
            </article>
        <?php endforeach; ?>

        <div class="sg-table-footer">
            <strong>Showing 1 to 4 of 124 transactions</strong>
            <nav class="sg-pagination" aria-label="Transaction pages">
                <button type="button" disabled>&lsaquo;</button>
                <button type="button" class="is-active">1</button>
                <button type="button">2</button>
                <button type="button">3</button>
                <button type="button">&rsaquo;</button>
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
