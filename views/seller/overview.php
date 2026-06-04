<?php
require_once __DIR__ . '/../../core/safegate_repository.php';

$page_title = 'Seller Overview - SafeGate';
$dashboard_page = 'overview';
$seller_id = sg_current_user_id();
$metrics = sg_get_seller_overview($seller_id);
$notifications = sg_get_notifications($seller_id, 5);
$unread_notifications = sg_unread_notification_count($seller_id);
$seller_profile = $seller_id ? sg_fetch_one('SELECT full_name FROM users WHERE id = :id LIMIT 1', ['id' => $seller_id]) : null;
$seller_name = trim((string) ($seller_profile['full_name'] ?? 'Vendor'));

ob_start();
?>

<section class="sg-vendor-page sg-overview-page">
    <header class="sg-vendor-topline">
        <p><iconify-icon icon="ph:map-pin"></iconify-icon> Main Net Node #0412</p>
        <div>
            <button type="button" aria-label="Search"><iconify-icon icon="ph:magnifying-glass"></iconify-icon></button>
            <button type="button" aria-label="Notifications" title="<?= (int) $unread_notifications ?> unread notifications"><iconify-icon icon="ph:bell"></iconify-icon></button>
            <button type="button" aria-label="Settings"><iconify-icon icon="ph:gear-six"></iconify-icon></button>
        </div>
    </header>

    <div class="sg-vendor-heading sg-overview-heading">
        <div>
            <h1>Welcome back, <?= sg_h($seller_name) ?></h1>
            <p>System status: Operational. Your encryption keys are active.</p>
        </div>
        <span class="sg-vendor-badge"><iconify-icon icon="ph:seal-check-fill"></iconify-icon> Institutional Vendor</span>
    </div>

    <div class="sg-metric-grid">
        <article class="sg-metric-card">
            <span>Escrow Balance</span>
            <strong><?= sg_rupiah($metrics['escrow_balance']) ?></strong>
            <small><iconify-icon icon="ph:hourglass"></iconify-icon> Ditahan sistem</small>
            <iconify-icon class="sg-card-watermark" icon="ph:lock"></iconify-icon>
        </article>
        <article class="sg-metric-card is-highlight">
            <span>Available to Withdraw</span>
            <strong><?= sg_rupiah($metrics['available_balance']) ?></strong>
            <a href="index.php?page=wallet"><iconify-icon icon="ph:money"></iconify-icon> Withdraw Funds</a>
        </article>
        <article class="sg-metric-card">
            <span>Total Sales Volume</span>
            <strong><?= sg_rupiah($metrics['sales_volume']) ?></strong>
            <p>Setelah dipotong fee 5%</p>
            <small class="text-safegate-success"><iconify-icon icon="ph:trend-up"></iconify-icon> +12.4% vs last month</small>
        </article>
    </div>

    <div class="sg-overview-grid">
        <section class="sg-panel sg-chart-panel">
            <div class="sg-panel-title-row">
                <h2>Grafik Penjualan (30 Hari Terakhir)</h2>
                <div class="sg-chart-tabs"><button class="is-active" type="button">30D</button><button type="button">90D</button></div>
            </div>
            <div class="sg-sales-chart" aria-label="Grafik penjualan">
                <svg viewBox="0 0 720 310" role="img">
                    <defs>
                        <linearGradient id="salesFill" x1="0" x2="0" y1="0" y2="1">
                            <stop offset="0" stop-color="#d9ff00" stop-opacity=".34"/>
                            <stop offset="1" stop-color="#d9ff00" stop-opacity="0"/>
                        </linearGradient>
                    </defs>
                    <path class="grid" d="M0 60H720M0 120H720M0 180H720M0 240H720"/>
                    <path class="area" d="M0 255 C70 235 78 150 135 132 S235 48 300 72 S390 205 455 170 S560 82 620 112 S685 212 720 220 L720 300 L0 300Z"/>
                    <path class="line" d="M0 255 C70 235 78 150 135 132 S235 48 300 72 S390 205 455 170 S560 82 620 112 S685 212 720 220"/>
                    <g class="points">
                        <circle cx="0" cy="255" r="4"/><circle cx="135" cy="132" r="4"/><circle cx="300" cy="72" r="4"/><circle cx="455" cy="170" r="4"/><circle cx="560" cy="112" r="4"/><circle cx="620" cy="112" r="4"/><circle cx="685" cy="212" r="4"/>
                    </g>
                </svg>
                <div class="sg-chart-labels"><span>W1</span><span>W2</span><span>W3</span><span>W4</span></div>
            </div>
        </section>

        <aside class="sg-side-stack">
            <section class="sg-panel sg-ops-panel">
                <h2><iconify-icon icon="ph:chart-bar"></iconify-icon> Operational Metrics</h2>
                <div class="sg-progress-item"><span>Active Listings</span><strong><?= (int) $metrics['active_listings'] ?> Tiket</strong><i style="--value: 34%"></i></div>
                <div class="sg-progress-item"><span>Sold This Month</span><strong><?= (int) $metrics['sold_month'] ?> Tiket</strong><i style="--value: 76%"></i></div>
            </section>
            <section class="sg-panel sg-alert-panel">
                <div style="display:flex; justify-content:space-between; align-items:center; gap:12px;">
                    <h2>! System Alerts</h2>
                    <?php if ($unread_notifications > 0): ?>
                        <a href="index.php?sg_action=mark_notifications_read" style="color:#d9ff00; font-size:11px; font-weight:800; text-decoration:none; text-transform:uppercase;">Mark read</a>
                    <?php endif; ?>
                </div>
                <?php if ($notifications): ?>
                    <?php foreach (array_slice($notifications, 0, 3) as $notification): ?>
                        <p class="<?= (int) $notification['is_read'] ? 'is-muted' : '' ?>">
                            <b></b>
                            <span class="sg-alert-content">
                                <strong><?= sg_h($notification['title']) ?></strong>
                                <em><?= sg_h($notification['body']) ?></em>
                                <small><?= sg_h(sg_time_ago($notification['created_at'])) ?></small>
                            </span>
                        </p>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p><b></b><span class="sg-alert-content"><strong>Belum ada notifikasi baru.</strong><em>Semua sistem seller normal.</em><small>Just now</small></span></p>
                <?php endif; ?>
            </section>
        </aside>
    </div>

    <footer class="sg-vendor-footer">
        <span>Ã‚Â© 2024 Safegate Protocol</span>
        <a href="#">API Docs</a>
        <a href="#">Security White Paper</a>
        <strong><i></i> All Systems Nominal</strong>
    </footer>
</section>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/dashboard_layout.php';
?>
