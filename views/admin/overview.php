<?php
// views/admin/overview.php - Pusat Komando Admin SafeGate
$page_title = 'System Command Center - SafeGate';

// Pastikan hanya admin level 4 yang masuk
require_once __DIR__ . '/../../core/admin_middleware.php';
require_once __DIR__ . '/../../core/safegate_repository.php';

$admin_overview = sg_get_admin_overview();
$admin_id = sg_current_user_id('admin');
$notifications = sg_get_notifications($admin_id, 5);
$unread_notifications = sg_unread_notification_count($admin_id);
$action_queue = sg_get_admin_action_queue(8);

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
            <div class="sg-admin-kpi-value" data-rupiah="<?= (int) $admin_overview['escrow_locked'] ?>"><?= sg_rupiah($admin_overview['escrow_locked']) ?></div>
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
            <div class="sg-admin-kpi-value sg-admin-glow-text" data-rupiah="<?= (int) $admin_overview['revenue'] ?>"><?= sg_rupiah($admin_overview['revenue']) ?></div>
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
                <div class="sg-admin-kpi-value"><?= (int) $admin_overview['pending_kyc'] ?> Users</div>
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
            <?php if ($unread_notifications > 0): ?>
                <a href="index.php?sg_action=mark_notifications_read" style="color:#d9ff00; font-size:11px; font-weight:800; text-decoration:none; text-transform:uppercase;">Mark read</a>
            <?php endif; ?>
        </div>
        <div class="sg-admin-alerts-list">
            <?php if ($notifications): ?>
                <?php foreach ($notifications as $notification): ?>
                    <?php
                    $icon_class = $notification['type'] === 'dispute_opened' ? 'is-warning' : (($notification['type'] === 'payment_success' || $notification['type'] === 'kyc_approved') ? 'is-success' : 'is-info');
                    $icon = $notification['type'] === 'dispute_opened' ? 'ph:warning-bold' : (($notification['type'] === 'payment_success' || $notification['type'] === 'kyc_approved') ? 'ph:check-bold' : 'ph:info-bold');
                    ?>
                    <div class="sg-admin-alert-item" style="<?= (int) $notification['is_read'] ? 'opacity:.55;' : '' ?>">
                        <div class="sg-admin-alert-icon <?= $icon_class ?>">
                            <iconify-icon icon="<?= $icon ?>"></iconify-icon>
                        </div>
                        <div class="sg-admin-alert-content">
                            <p class="sg-admin-alert-text"><?= sg_h($notification['title']) ?></p>
                            <span class="sg-admin-alert-time"><?= sg_h($notification['body']) ?> · <?= sg_h(sg_time_ago($notification['created_at'])) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="sg-admin-alert-item">
                    <div class="sg-admin-alert-icon is-info">
                        <iconify-icon icon="ph:info-bold"></iconify-icon>
                    </div>
                    <div class="sg-admin-alert-content">
                        <p class="sg-admin-alert-text">Belum ada alert database.</p>
                        <span class="sg-admin-alert-time">Semua sistem nominal</span>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Row 3: Priority Action Queue -->
<div class="sg-admin-table-panel">
    <div class="sg-admin-table-header-row">
        <h3 class="sg-admin-table-title">Priority Action Queue</h3>
        <a class="sg-admin-btn-export" href="index.php?sg_action=export_transactions" style="text-decoration:none;">Export Ledger</a>
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
                <?php if (!$action_queue): ?>
                    <tr>
                        <td colspan="4" style="padding: 28px; color: var(--admin-text-muted);">Belum ada KYC, dispute, atau audit log aktif dari database.</td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($action_queue as $queue_item): ?>
                    <tr>
                        <td class="sg-admin-timestamp"><?= sg_h(date('M d, H:i:s', strtotime($queue_item['timestamp']))) ?></td>
                        <td>
                            <span class="sg-admin-badge-case <?= sg_h($queue_item['class']) ?>"><?= sg_h($queue_item['type']) ?></span>
                        </td>
                        <td>
                            <span class="sg-admin-case-desc"><?= sg_h($queue_item['description']) ?></span>
                        </td>
                        <td>
                            <?php if ($queue_item['type'] === 'AUDIT LOG'): ?>
                                <a class="sg-admin-btn-more" aria-label="View audit target" href="<?= sg_h($queue_item['action_link']) ?>" style="display:inline-flex; align-items:center; justify-content:center; text-decoration:none;">
                                    <iconify-icon icon="<?= sg_h($queue_item['icon']) ?>"></iconify-icon>
                                </a>
                            <?php else: ?>
                                <button class="sg-admin-btn-action <?= sg_h($queue_item['action_class']) ?>" onclick="location.href='<?= sg_h($queue_item['action_link']) ?>'"><?= sg_h($queue_item['action_label']) ?></button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/admin_layout.php';
?>
