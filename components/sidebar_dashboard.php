<?php
$asset_prefix = isset($asset_prefix) ? $asset_prefix : ((strpos($_SERVER['SCRIPT_NAME'], 'views/') !== false) ? '../../' : '');
$dashboard_page = isset($dashboard_page) ? $dashboard_page : '';
?>
<aside class="sg-sidebar" aria-label="Seller dashboard sidebar">
    <div class="sg-seller-card">
        <div class="sg-seller-avatar" aria-hidden="true"></div>
        <div>
            <strong>SafeGate<br>Dashboard</strong>
            <span>Verified Vendor</span>
        </div>
    </div>

    <nav class="sg-sidebar-nav">
        <a class="<?= $dashboard_page === 'sell_ticket' ? 'is-active' : '' ?>" href="<?= $asset_prefix ?>index.php?page=sell_ticket">
            <iconify-icon icon="ph:plus-circle"></iconify-icon>
            <span>Sell<br>Tickets</span>
        </a>
        <a class="<?= $dashboard_page === 'transaction' ? 'is-active' : '' ?>" href="<?= $asset_prefix ?>index.php?page=transaction">
            <iconify-icon icon="ph:calendar-dots"></iconify-icon>
            <span>Transaction History</span>
        </a>
    </nav>

    <div class="sg-sidebar-footer">
        <a href="#">
            <iconify-icon icon="ph:question"></iconify-icon>
            <span>Help<br>Center</span>
        </a>
        <a href="<?= $asset_prefix ?>index.php?page=home">
            <iconify-icon icon="ph:sign-out"></iconify-icon>
            <span>Log<br>Out</span>
        </a>
    </div>
</aside>
