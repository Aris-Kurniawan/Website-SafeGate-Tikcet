<?php
$page_title = 'Seller Overview - SafeGate';
$dashboard_page = 'overview';

ob_start();
?>

<section class="sg-vendor-page sg-overview-page">
    <header class="sg-vendor-topline">
        <p><iconify-icon icon="ph:map-pin"></iconify-icon> Main Net Node #0412</p>
        <div>
            <button type="button" aria-label="Search"><iconify-icon icon="ph:magnifying-glass"></iconify-icon></button>
            <button type="button" aria-label="Notifications"><iconify-icon icon="ph:bell"></iconify-icon></button>
            <button type="button" aria-label="Settings"><iconify-icon icon="ph:gear-six"></iconify-icon></button>
        </div>
    </header>

    <div class="sg-vendor-heading sg-overview-heading">
        <div>
            <h1>Welcome back, Vendor</h1>
            <p>System status: Operational. Your encryption keys are active.</p>
        </div>
        <span class="sg-vendor-badge"><iconify-icon icon="ph:seal-check-fill"></iconify-icon> Institutional Vendor</span>
    </div>

    <div class="sg-metric-grid">
        <article class="sg-metric-card">
            <span>Escrow Balance</span>
            <strong>Rp 8.290.000</strong>
            <small><iconify-icon icon="ph:hourglass"></iconify-icon> Ditahan sistem</small>
            <iconify-icon class="sg-card-watermark" icon="ph:lock"></iconify-icon>
        </article>
        <article class="sg-metric-card is-highlight">
            <span>Available to Withdraw</span>
            <strong>Rp 4.150.000</strong>
            <a href="index.php?page=wallet"><iconify-icon icon="ph:money"></iconify-icon> Withdraw Funds</a>
        </article>
        <article class="sg-metric-card">
            <span>Total Sales Volume</span>
            <strong>Rp 12.440.000</strong>
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
                <div class="sg-progress-item"><span>Active Listings</span><strong>4 Tiket</strong><i style="--value: 34%"></i></div>
                <div class="sg-progress-item"><span>Sold This Month</span><strong>12 Tiket</strong><i style="--value: 76%"></i></div>
            </section>
            <section class="sg-panel sg-alert-panel">
                <h2>! System Alerts</h2>
                <p><b></b>Lelang tiket VIP Coldplay berakhir dalam 2 jam.<span>High Priority</span></p>
                <p class="is-muted"><b></b>Verifikasi KYC tahunan telah disetujui.<span>Yesterday</span></p>
            </section>
        </aside>
    </div>

    <footer class="sg-vendor-footer">
        <span>© 2024 Safegate Protocol</span>
        <a href="#">API Docs</a>
        <a href="#">Security White Paper</a>
        <strong><i></i> All Systems Nominal</strong>
    </footer>
</section>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/dashboard_layout.php';
?>
