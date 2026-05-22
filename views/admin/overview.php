<?php
// views/admin/overview.php - Command Center Utama Admin
require_once __DIR__ . '/../../core/admin_middleware.php';

$page_title = 'Admin Command Center - SafeGate';
$admin_page = 'overview';

ob_start();
?>

<div class="sg-admin-header">
    <div class="sg-admin-title-area">
        <h1>Command Center Overview</h1>
        <p>Protokol Keamanan Tingkat Tinggi SafeGate | Status Layanan Nominal</p>
    </div>
    <div class="sg-admin-status-badge success">
        <iconify-icon icon="ph:shield-check-fill"></iconify-icon> Secure Node Online
    </div>
</div>

<!-- Stats Grid -->
<div class="sg-admin-stats-grid">
    <div class="sg-admin-stat-card">
        <span>Total Escrow Protection</span>
        <strong>Rp 148.290.000</strong>
        <iconify-icon icon="ph:lock-keyhole-fill" class="watermark"></iconify-icon>
    </div>
    
    <div class="sg-admin-stat-card">
        <span>Active Disputes</span>
        <strong style="color: var(--admin-danger);">3 Kasus</strong>
        <iconify-icon icon="ph:gavel-fill" class="watermark"></iconify-icon>
    </div>
    
    <div class="sg-admin-stat-card">
        <span>KYC Queue</span>
        <strong style="color: var(--admin-warning);">5 Vendor</strong>
        <iconify-icon icon="ph:user-focus-fill" class="watermark"></iconify-icon>
    </div>
    
    <div class="sg-admin-stat-card">
        <span>Global Sales Volume</span>
        <strong style="color: var(--admin-success);">Rp 842.150.000</strong>
        <iconify-icon icon="ph:chart-line-up-fill" class="watermark"></iconify-icon>
    </div>
</div>

<!-- Charts and Details Panel -->
<div class="sg-admin-panel">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 class="sg-admin-panel-title" style="margin-bottom: 0;">
            <iconify-icon icon="ph:chart-bar-fill"></iconify-icon> Escrow Protection & Volume Tracker
        </h2>
        <div style="display: flex; gap: 8px;">
            <button class="sg-admin-chart-tab is-active" type="button" style="background: rgba(255,255,255,0.05); border: 1px solid var(--admin-border); color: #fff; padding: 6px 14px; border-radius: 6px; cursor: pointer; font-weight: 800; font-size: 12px; transition: all 0.2s;">30D</button>
            <button class="sg-admin-chart-tab" type="button" style="background: rgba(255,255,255,0.05); border: 1px solid var(--admin-border); color: #fff; padding: 6px 14px; border-radius: 6px; cursor: pointer; font-weight: 800; font-size: 12px; transition: all 0.2s;">90D</button>
        </div>
    </div>
    
    <div class="sg-admin-sales-chart" style="margin-top: 20px; position: relative;">
        <svg viewBox="0 0 720 300" style="width: 100%; height: auto; display: block; overflow: visible;">
            <defs>
                <linearGradient id="adminChartFill" x1="0" x2="0" y1="0" y2="1">
                    <stop offset="0" stop-color="var(--admin-accent)" stop-opacity="0.25"/>
                    <stop offset="1" stop-color="var(--admin-accent)" stop-opacity="0"/>
                </linearGradient>
            </defs>
            
            <!-- Grid Lines -->
            <line x1="0" y1="50" x2="720" y2="50" stroke="rgba(255,255,255,0.03)" stroke-width="1" />
            <line x1="0" y1="120" x2="720" y2="120" stroke="rgba(255,255,255,0.03)" stroke-width="1" />
            <line x1="0" y1="190" x2="720" y2="190" stroke="rgba(255,255,255,0.03)" stroke-width="1" />
            <line x1="0" y1="260" x2="720" y2="260" stroke="rgba(255,255,255,0.03)" stroke-width="1" />
            
            <!-- Area under line -->
            <path class="area" d="M0 240 C70 210 120 140 220 110 S320 230 420 170 S520 80 620 90 S690 190 720 160 L720 300 L0 300Z" fill="url(#adminChartFill)"></path>
            
            <!-- Chart line path -->
            <path class="line" d="M0 240 C70 210 120 140 220 110 S320 230 420 170 S520 80 620 90 S690 190 720 160" fill="none" stroke="var(--admin-accent)" stroke-width="3" stroke-linecap="round"></path>
            
            <!-- Circle Points -->
            <g class="points">
                <circle cx="0" cy="240" r="5" fill="var(--admin-accent)" stroke="var(--admin-surface)" stroke-width="2"></circle>
                <circle cx="220" cy="110" r="5" fill="var(--admin-accent)" stroke="var(--admin-surface)" stroke-width="2"></circle>
                <circle cx="420" cy="170" r="5" fill="var(--admin-accent)" stroke="var(--admin-surface)" stroke-width="2"></circle>
                <circle cx="620" cy="90" r="5" fill="var(--admin-accent)" stroke="var(--admin-surface)" stroke-width="2"></circle>
                <circle cx="720" cy="160" r="5" fill="var(--admin-accent)" stroke="var(--admin-surface)" stroke-width="2"></circle>
            </g>
        </svg>
    </div>
</div>

<!-- Alert Logs -->
<div class="sg-admin-panel">
    <h2 class="sg-admin-panel-title">
        <iconify-icon icon="ph:warning-circle-fill"></iconify-icon> Antrean Darurat & Alert Protokol
    </h2>
    <div class="sg-admin-table-responsive">
        <table class="sg-admin-table">
            <thead>
                <tr>
                    <th>Alert Type</th>
                    <th>Message</th>
                    <th>Priority</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong style="color: var(--admin-danger);">DISPUTE</strong></td>
                    <td>Sengketa Baru #DISP-0842 (Coldplay VIP Ticket - Pembeli melapor tiket duplikat)</td>
                    <td><span class="sg-admin-status-badge danger">CRITICAL</span></td>
                    <td>2 Menit Lalu</td>
                </tr>
                <tr>
                    <td><strong style="color: var(--admin-warning);">KYC PENDING</strong></td>
                    <td>Pengajuan Vendor ID #V-9081 (Aris Kurniawan - Menunggu Review Swafoto)</td>
                    <td><span class="sg-admin-status-badge warning">MEDIUM</span></td>
                    <td>14 Menit Lalu</td>
                </tr>
                <tr>
                    <td><strong style="color: var(--admin-info);">SYSTEM</strong></td>
                    <td>Audit Node SafeGate selesai, total Rp 148M aman terenkripsi di Escrow Ledger</td>
                    <td><span class="sg-admin-status-badge success">LOW</span></td>
                    <td>1 Jam Lalu</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Custom styles inline helper for chart tab styling -->
<style>
.sg-admin-chart-tab.is-active {
    background: var(--admin-accent) !important;
    color: #000 !important;
    border-color: var(--admin-accent) !important;
    box-shadow: 0 0 10px rgba(217, 255, 0, 0.35);
}
</style>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/admin_layout.php';
?>
