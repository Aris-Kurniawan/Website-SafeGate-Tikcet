<?php
// views/admin/overview.php - Pusat Komando Admin SafeGate
$page_title = 'System Command Center - SafeGate';

// Pastikan hanya admin level 4 yang masuk
require_once __DIR__ . '/../../core/admin_middleware.php';

ob_start();
?>

<div class="sg-admin-title-section">
    <h1>System Command Center</h1>
    <div class="sg-admin-server-status">
        <span class="sg-status-dot"></span>
        <span>Server: All Nodes Nominal</span>
    </div>
</div>

<!-- KPI Cards Row -->
<div class="sg-admin-kpi-grid">
    <!-- Card 1: Total Escrow Locked -->
    <div class="sg-admin-kpi-card">
        <div class="sg-admin-kpi-info">
            <h2 class="sg-admin-kpi-label">Total Escrow Locked</h2>
            <div class="sg-admin-kpi-value" data-rupiah="4250000000">Rp 4.250.000.000</div>
            <div class="sg-admin-kpi-footer sg-admin-trend-up">
                <iconify-icon icon="ph:trend-up-bold"></iconify-icon>
                <span>+12.4% from last week</span>
            </div>
        </div>
        <div class="sg-admin-kpi-icon">
            <iconify-icon icon="ph:wallet-fill"></iconify-icon>
        </div>
    </div>

    <!-- Card 2: Net Revenue (5% Fee) -->
    <div class="sg-admin-kpi-card">
        <div class="sg-admin-kpi-info">
            <h2 class="sg-admin-kpi-label">Net Revenue (5% Fee)</h2>
            <div class="sg-admin-kpi-value sg-admin-glow-text" data-rupiah="212500000">Rp 212.500.000</div>
            <div class="sg-admin-kpi-footer sg-admin-smart-contract-text">
                <iconify-icon icon="ph:cpu-fill"></iconify-icon>
                <span>Auto-calculated by Smart Contract</span>
            </div>
        </div>
        <div class="sg-admin-kpi-icon">
            <iconify-icon icon="ph:currency-circle-dollar-fill"></iconify-icon>
        </div>
    </div>

    <!-- Card 3: Pending KYC -->
    <div class="sg-admin-kpi-card">
        <div class="sg-admin-kpi-info">
            <h2 class="sg-admin-kpi-label">Pending KYC</h2>
            <div class="sg-admin-kyc-header">
                <div class="sg-admin-kpi-value">14 Users</div>
                <button class="sg-admin-btn-review" onclick="location.href='index.php?page=admin_kyc'">Review Now</button>
            </div>
            <div class="sg-admin-progress-container">
                <div class="sg-admin-progress-bar" style="width: 85%;"></div>
            </div>
        </div>
        <div class="sg-admin-kpi-icon">
            <iconify-icon icon="ph:identification-card-fill"></iconify-icon>
        </div>
    </div>
</div>

<!-- Row 2: Visual Chart & Alerts -->
<div class="sg-admin-main-grid">
    <!-- Chart Panel -->
    <div class="sg-admin-panel">
        <div class="sg-admin-panel-header">
            <h3>Transaction Volume (7 Days)</h3>
            <div class="sg-admin-volume-indicator">
                <span class="sg-indicator-dot"></span>
                <span>Volume</span>
            </div>
        </div>
        <div class="sg-admin-chart-wrapper">
            <svg viewBox="0 0 540 280" style="width:100%; height:100%;">
                <!-- Horizontal Grid Lines -->
                <line class="sg-chart-grid-line" x1="0" y1="70" x2="540" y2="70" />
                <line class="sg-chart-grid-line" x1="0" y1="140" x2="540" y2="140" />
                <line class="sg-chart-grid-line" x1="0" y1="210" x2="540" y2="210" />
                <line class="sg-chart-grid-line" x1="0" y1="280" x2="540" y2="280" stroke="rgba(255,255,255,0.1)" stroke-width="1" />

                <!-- 7 Bars of Neon Green -->
                <!-- x starts at 30, ends at 510. x spacing = (510-30)/6 = 80 -->
                <!-- height coordinate space is 0 to 280 -->
                <rect class="sg-chart-bar-rect" x="30" y="190" width="40" height="90" data-height="90" data-y="190" data-label="Oct 18" data-value="Rp 90.000.000" />
                <rect class="sg-chart-bar-rect" x="110" y="130" width="40" height="150" data-height="150" data-y="130" data-label="Oct 19" data-value="Rp 150.000.000" />
                <rect class="sg-chart-bar-rect" x="190" y="150" width="40" height="130" data-height="130" data-y="150" data-label="Oct 20" data-value="Rp 130.000.000" />
                <rect class="sg-chart-bar-rect" x="270" y="80" width="40" height="200" data-height="200" data-y="80" data-label="Oct 21" data-value="Rp 200.000.000" />
                <rect class="sg-chart-bar-rect" x="350" y="180" width="40" height="100" data-height="100" data-y="180" data-label="Oct 22" data-value="Rp 100.000.000" />
                <rect class="sg-chart-bar-rect" x="430" y="40" width="40" height="240" data-height="240" data-y="40" data-label="Oct 23" data-value="Rp 240.000.000" />
                <rect class="sg-chart-bar-rect" x="510" y="100" width="40" height="180" data-height="180" data-y="100" data-label="Oct 24" data-value="Rp 180.000.000" />
            </svg>
        </div>
    </div>

    <!-- System Alerts Panel -->
    <div class="sg-admin-panel">
        <div class="sg-admin-panel-header">
            <h3>System Alerts</h3>
        </div>
        <div class="sg-admin-alerts-list">
            <!-- Alert 1 -->
            <div class="sg-admin-alert-item">
                <div class="sg-admin-alert-icon is-success">
                    <iconify-icon icon="ph:check-bold"></iconify-icon>
                </div>
                <div class="sg-admin-alert-content">
                    <p class="sg-admin-alert-text">Admin B approved KYC User #412</p>
                    <span class="sg-admin-alert-time">2 mins ago</span>
                </div>
            </div>

            <!-- Alert 2 -->
            <div class="sg-admin-alert-item">
                <div class="sg-admin-alert-icon is-warning">
                    <iconify-icon icon="ph:warning-bold"></iconify-icon>
                </div>
                <div class="sg-admin-alert-content">
                    <p class="sg-admin-alert-text">Dispute opened for Ticket #991</p>
                    <span class="sg-admin-alert-time">14 mins ago</span>
                </div>
            </div>

            <!-- Alert 3 -->
            <div class="sg-admin-alert-item">
                <div class="sg-admin-alert-icon is-info">
                    <iconify-icon icon="ph:info-bold"></iconify-icon>
                </div>
                <div class="sg-admin-alert-content">
                    <p class="sg-admin-alert-text">Large withdrawal detected: Rp 45M</p>
                    <span class="sg-admin-alert-time">45 mins ago</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Row 3: Priority Action Queue -->
<div class="sg-admin-table-panel">
    <div class="sg-admin-table-header-row">
        <h3 class="sg-admin-table-title">Priority Action Queue</h3>
        <button class="sg-admin-btn-export" onclick="exportLedger()">Export Ledger</button>
    </div>
    
    <div class="sg-admin-table-responsive">
        <table class="sg-admin-table">
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>Case Type</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Row 1: KYC REQUEST -->
                <tr>
                    <td class="sg-admin-timestamp">Oct 24, 14:32:01</td>
                    <td>
                        <span class="sg-admin-badge-case is-kyc">KYC REQUEST</span>
                    </td>
                    <td>
                        <div class="sg-admin-user-cell">
                            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=80" alt="Alexander Sterling" class="sg-admin-user-avatar">
                            <span class="sg-admin-user-name">Alexander Sterling</span>
                        </div>
                    </td>
                    <td>
                        <button class="sg-admin-btn-action is-green" onclick="location.href='index.php?page=admin_kyc'">Review</button>
                    </td>
                </tr>
                
                <!-- Row 2: ESCROW DISPUTE -->
                <tr>
                    <td class="sg-admin-timestamp">Oct 24, 14:15:44</td>
                    <td>
                        <span class="sg-admin-badge-case is-dispute">ESCROW DISPUTE</span>
                    </td>
                    <td>
                        <span class="sg-admin-case-desc">Ticket #412A - Hardware Delivery Failure</span>
                    </td>
                    <td>
                        <button class="sg-admin-btn-action is-peach" onclick="location.href='index.php?page=admin_disputes'">Investigate</button>
                    </td>
                </tr>
                
                <!-- Row 3: AUDIT LOG -->
                <tr>
                    <td class="sg-admin-timestamp">Oct 24, 13:58:20</td>
                    <td>
                        <span class="sg-admin-badge-case is-audit">AUDIT LOG</span>
                    </td>
                    <td>
                        <span class="sg-admin-case-desc">System Kernel Update - Node #02 Success</span>
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; justify-content: flex-start;">
                            <button class="sg-admin-btn-more" aria-label="More options" onclick="showMoreOptions('Oct 24, 13:58:20')">
                                <iconify-icon icon="ph:dots-three-outline-fill"></iconify-icon>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
function exportLedger() {
    alert("Exporting SafeGate Global Ledger...\nDownload will start shortly in CSV format.");
}

function showMoreOptions(timestamp) {
    alert("Audit log entry for " + timestamp + " is healthy.\nNo anomaly detected.");
}
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/admin_layout.php';
?>
