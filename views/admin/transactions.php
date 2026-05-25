<?php
// views/admin/transactions.php - Buku Besar Transaksi SafeGate Admin
$page_title = 'Global Transaction Ledger - SafeGate Admin';

// Satpam Check
require_once __DIR__ . '/../../core/admin_middleware.php';

// Ambil filter query parameter jika di-submit untuk UX yang dinamis
$search_val = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';
$pay_status_val = isset($_GET['payment_status']) ? $_GET['payment_status'] : 'all';
$escrow_status_val = isset($_GET['escrow_status']) ? $_GET['escrow_status'] : 'all';
$date_val = isset($_GET['date']) ? htmlspecialchars($_GET['date']) : '';

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

<!-- KPI Cards Grid -->
<div class="sg-admin-kpi-grid" style="margin-top: 24px;">
    <!-- 24H Volume -->
    <div class="sg-admin-kpi-card">
        <div class="sg-admin-kpi-info">
            <h2 class="sg-admin-kpi-label">24h Volume</h2>
            <div class="sg-admin-kpi-value" style="color: var(--admin-accent);" data-rupiah="128500000">Rp 128.500.000</div>
            <div class="sg-admin-kpi-footer sg-admin-trend-up">
                <iconify-icon icon="ph:trend-up-bold"></iconify-icon>
                <span>+14.2% from previous cycle</span>
            </div>
        </div>
        <div class="sg-admin-kpi-icon">
            <iconify-icon icon="ph:chart-bar-fill"></iconify-icon>
        </div>
    </div>

    <!-- Pending Settlements -->
    <div class="sg-admin-kpi-card">
        <div class="sg-admin-kpi-info">
            <h2 class="sg-admin-kpi-label">Pending Settlements</h2>
            <div class="sg-admin-kpi-value" style="color: #00e5ff;">42 Transaksi</div>
            <div class="sg-admin-kpi-footer" style="color: #00e5ff;">
                <iconify-icon icon="ph:clock-fill"></iconify-icon>
                <span>Average wait: 1.4 hrs</span>
            </div>
        </div>
        <div class="sg-admin-kpi-icon">
            <iconify-icon icon="ph:hourglass-medium-fill"></iconify-icon>
        </div>
    </div>

    <!-- Failed/Expired -->
    <div class="sg-admin-kpi-card">
        <div class="sg-admin-kpi-info">
            <h2 class="sg-admin-kpi-label">Failed/Expired</h2>
            <div class="sg-admin-kpi-value" style="color: var(--admin-danger);">5 Transaksi</div>
            <div class="sg-admin-kpi-footer" style="color: var(--admin-danger);">
                <iconify-icon icon="ph:warning-fill"></iconify-icon>
                <span>Requires Manual Sync</span>
            </div>
        </div>
        <div class="sg-admin-kpi-icon">
            <iconify-icon icon="ph:x-circle-fill"></iconify-icon>
        </div>
    </div>
</div>

<!-- Filter Bar Section -->
<form method="GET" action="index.php" class="sg-admin-filter-bar" style="margin-top: 24px;">
    <!-- Hidden input to keep route active -->
    <input type="hidden" name="page" value="admin_transactions">

    <!-- Search box -->
    <div class="sg-admin-filter-input-group">
        <iconify-icon icon="ph:magnifying-glass"></iconify-icon>
        <input type="text" name="search" placeholder="Search TX-ID, Buyer Email..." value="<?= $search_val ?>">
    </div>

    <!-- Payment Status select -->
    <select name="payment_status" class="sg-admin-filter-select" onchange="this.style.color='var(--admin-text)'">
        <option value="all" <?= $pay_status_val === 'all' ? 'selected' : '' ?>>Payment Status: All</option>
        <option value="paid" <?= $pay_status_val === 'paid' ? 'selected' : '' ?>>Paid</option>
        <option value="failed" <?= $pay_status_val === 'failed' ? 'selected' : '' ?>>Failed</option>
        <option value="pending" <?= $pay_status_val === 'pending' ? 'selected' : '' ?>>Pending</option>
    </select>

    <!-- Escrow Status select -->
    <select name="escrow_status" class="sg-admin-filter-select" onchange="this.style.color='var(--admin-text)'">
        <option value="all" <?= $escrow_status_val === 'all' ? 'selected' : '' ?>>Escrow Status: All</option>
        <option value="released" <?= $escrow_status_val === 'released' ? 'selected' : '' ?>>Released</option>
        <option value="held" <?= $escrow_status_val === 'held' ? 'selected' : '' ?>>Held</option>
        <option value="na" <?= $escrow_status_val === 'na' ? 'selected' : '' ?>>N/A</option>
    </select>

    <!-- Date Picker picker input -->
    <input type="date" name="date" class="sg-admin-filter-date" value="<?= $date_val ?>" onchange="this.style.color='var(--admin-text)'">

    <!-- Export to CSV button -->
    <button type="button" class="sg-admin-btn-export-csv" onclick="exportToCSV()">
        <iconify-icon icon="ph:download-simple-bold"></iconify-icon>
        <span>Export to CSV</span>
    </button>

    <!-- Apply Filter button -->
    <button type="submit" class="sg-admin-btn-apply-filter">
        <span>Apply Filter</span>
    </button>
</form>

<!-- Ledger Table Card Panel -->
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
                <!-- Row 1: Eras Tour Ticket -->
                <tr>
                    <td class="sg-admin-timestamp" style="font-weight: 700; color: #FFF;">
                        SG-TX-9921
                        <span style="display: block; font-size: 11px; font-weight: 500; color: var(--admin-text-muted); margin-top: 4px;">24 Oct 2024, 14:22</span>
                    </td>
                    <td>
                        <a href="mailto:alex@email.com" class="sg-admin-entity-link">alex@email.com</a>
                        <span class="sg-admin-entity-arrow">→</span>
                        <span class="sg-admin-entity-merchant">Vendor #412</span>
                    </td>
                    <td style="color: #E2E8F0; font-weight: 600;">1x Ticket - The Eras Tour</td>
                    <td class="sg-admin-amount-fee">
                        <strong style="color: #FFF; font-size: 15px;">Rp 2.500.000</strong>
                        <span class="sg-fee-text">Fee: Rp 125.000</span>
                    </td>
                    <td>
                        <span class="sg-badge-status-dot is-paid">Paid</span>
                    </td>
                    <td>
                        <span class="sg-badge-status-dot is-released">Released</span>
                    </td>
                    <td>
                        <button class="sg-admin-btn-more" aria-label="View Details" onclick="viewTransaction('SG-TX-9921')">
                            <iconify-icon icon="ph:eye-fill"></iconify-icon>
                        </button>
                    </td>
                </tr>

                <!-- Row 2: Luxury Watch -->
                <tr>
                    <td class="sg-admin-timestamp" style="font-weight: 700; color: #FFF;">
                        SG-TX-9922
                        <span style="display: block; font-size: 11px; font-weight: 500; color: var(--admin-text-muted); margin-top: 4px;">24 Oct 2024, 15:10</span>
                    </td>
                    <td>
                        <a href="mailto:user_88@domain.io" class="sg-admin-entity-link">user_88@domain.io</a>
                        <span class="sg-admin-entity-arrow">→</span>
                        <span class="sg-admin-entity-merchant">Merchant Alpha</span>
                    </td>
                    <td style="color: #E2E8F0; font-weight: 600;">Luxury Watch (Pre-owned)</td>
                    <td class="sg-admin-amount-fee">
                        <strong style="color: #FFF; font-size: 15px;">Rp 1.500.000</strong>
                        <span class="sg-fee-text">Fee: Rp 75.000</span>
                    </td>
                    <td>
                        <span class="sg-badge-status-dot is-paid">Paid</span>
                    </td>
                    <td>
                        <span class="sg-badge-status-dot is-held">Held</span>
                    </td>
                    <td>
                        <button class="sg-admin-btn-more" aria-label="View Details" onclick="viewTransaction('SG-TX-9922')">
                            <iconify-icon icon="ph:eye-fill"></iconify-icon>
                        </button>
                    </td>
                </tr>

                <!-- Row 3: Bulk Digital Assets (Failed) -->
                <tr>
                    <td class="sg-admin-timestamp" style="font-weight: 700; color: #FFF;">
                        SG-TX-9923
                        <span style="display: block; font-size: 11px; font-weight: 500; color: var(--admin-text-muted); margin-top: 4px;">24 Oct 2024, 16:05</span>
                    </td>
                    <td>
                        <a href="mailto:crypto_whale@web3.com" class="sg-admin-entity-link">crypto_whale@web3.com</a>
                        <span class="sg-admin-entity-arrow">→</span>
                        <span class="sg-admin-entity-merchant">Unknown Node</span>
                    </td>
                    <td style="color: #E2E8F0; font-weight: 600;">Bulk Digital Assets</td>
                    <td class="sg-admin-amount-fee">
                        <strong style="color: #FFF; font-size: 15px;">Rp 5.000.000</strong>
                        <span class="sg-fee-text">Fee: Rp 250.000</span>
                    </td>
                    <td>
                        <span class="sg-badge-status-dot is-failed">Failed</span>
                    </td>
                    <td>
                        <span class="sg-badge-status-dot is-na">N/A</span>
                    </td>
                    <td>
                        <button class="sg-admin-btn-sync" onclick="forceSyncTransaction('SG-TX-9923')">
                            <iconify-icon icon="ph:arrows-clockwise-bold"></iconify-icon>
                            <span>Force Sync</span>
                        </button>
                    </td>
                </tr>

                <!-- Row 4: iPhone 16 Pro Max (Pending) -->
                <tr>
                    <td class="sg-admin-timestamp" style="font-weight: 700; color: #FFF;">
                        SG-TX-9924
                        <span style="display: block; font-size: 11px; font-weight: 500; color: var(--admin-text-muted); margin-top: 4px;">24 Oct 2024, 16:40</span>
                    </td>
                    <td>
                        <a href="mailto:sarah_smith@mail.com" class="sg-admin-entity-link">sarah_smith@mail.com</a>
                        <span class="sg-admin-entity-arrow">→</span>
                        <span class="sg-admin-entity-merchant">Gadget Hub</span>
                    </td>
                    <td style="color: #E2E8F0; font-weight: 600;">iPhone 16 Pro Max</td>
                    <td class="sg-admin-amount-fee">
                        <strong style="color: #FFF; font-size: 15px;">Rp 22.000.000</strong>
                        <span class="sg-fee-text">Fee: Rp 440.000</span>
                    </td>
                    <td>
                        <span class="sg-badge-status-dot is-pending">Pending</span>
                    </td>
                    <td>
                        <span class="sg-badge-status-dot is-held">Held</span>
                    </td>
                    <td>
                        <button class="sg-admin-btn-more" aria-label="View Details" onclick="viewTransaction('SG-TX-9924')">
                            <iconify-icon icon="ph:eye-fill"></iconify-icon>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination Footer -->
    <div class="sg-admin-table-footer-row">
        <div class="sg-admin-record-count">
            Displaying 4 of 2,491 records
        </div>
        <div class="sg-admin-pagination">
            <button type="button" class="sg-admin-page-btn is-disabled" aria-label="Previous page">
                <iconify-icon icon="ph:caret-left-bold"></iconify-icon>
            </button>
            <a href="#" class="sg-admin-page-btn is-active">1</a>
            <a href="#" class="sg-admin-page-btn">2</a>
            <a href="#" class="sg-admin-page-btn">3</a>
            <button type="button" class="sg-admin-page-btn" aria-label="Next page">
                <iconify-icon icon="ph:caret-right-bold"></iconify-icon>
            </button>
        </div>
    </div>
</div>

<script>
// Pemicu warna font untuk input select setelah diubah nilainya
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".sg-admin-filter-select, .sg-admin-filter-date").forEach(el => {
        if(el.value && el.value !== "all") {
            el.style.color = "var(--admin-text)";
        }
    });
});

function exportToCSV() {
    alert("System Ledger Export:\nDownloading Global Transaction Ledger as CSV...\nTransaction files will save locally in your Downloads folder.");
}

function viewTransaction(txId) {
    alert("Viewing transaction details for: " + txId + "\nTransaction state is active on the mainnet node.");
}

function forceSyncTransaction(txId) {
    if (confirm("Initiate FORCE RESYNC for " + txId + " on smart contract ledger?")) {
        alert("Sync command dispatched. Re-validating block signatures...");
    }
}
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/admin_layout.php';
?>
