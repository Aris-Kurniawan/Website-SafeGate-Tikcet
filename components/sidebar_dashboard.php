<?php
$asset_prefix = isset($asset_prefix) ? $asset_prefix : ((strpos($_SERVER['SCRIPT_NAME'], 'views/') !== false) ? '../../' : '');
$dashboard_page = isset($dashboard_page) ? $dashboard_page : '';
?>
<aside class="sg-sidebar" aria-label="Seller dashboard sidebar">
    <a class="sg-side-brand" href="<?= $asset_prefix ?>index.php?page=seller_overview" aria-label="SafeGate dashboard">
        <span>SafeGate</span><i></i>
    </a>

    <div class="sg-seller-card">
        <div class="sg-seller-avatar" aria-hidden="true"></div>
        <div>
            <strong>Verified Vendor</strong>
            <span>KYC Active</span>
        </div>
    </div>

    <nav class="sg-sidebar-nav">
        <a class="<?= $dashboard_page === 'overview' ? 'is-active' : '' ?>" href="<?= $asset_prefix ?>index.php?page=seller_overview">
            <iconify-icon icon="ph:squares-four"></iconify-icon>
            <span>Overview</span>
        </a>
        <a class="<?= $dashboard_page === 'sell_ticket' ? 'is-active' : '' ?>" href="<?= $asset_prefix ?>index.php?page=sell_ticket">
            <iconify-icon icon="ph:ticket"></iconify-icon>
            <span>Sell Ticket</span>
        </a>
        <a class="<?= $dashboard_page === 'active_listings' ? 'is-active' : '' ?>" href="<?= $asset_prefix ?>index.php?page=active_listings">
            <iconify-icon icon="ph:list-bullets"></iconify-icon>
            <span>Active Listings</span>
        </a>
        <a class="<?= $dashboard_page === 'wallet' ? 'is-active' : '' ?>" href="<?= $asset_prefix ?>index.php?page=wallet">
            <iconify-icon icon="ph:wallet"></iconify-icon>
            <span>Wallet &amp; Escrow</span>
        </a>
        <a class="<?= $dashboard_page === 'transaction' ? 'is-active' : '' ?>" href="<?= $asset_prefix ?>index.php?page=transaction">
            <iconify-icon icon="ph:clock-counter-clockwise"></iconify-icon>
            <span>Transaction History</span>
        </a>
        <hr>
        <a class="<?= $dashboard_page === 'settings' ? 'is-active' : '' ?>" href="<?= $asset_prefix ?>index.php?page=settings">
            <iconify-icon icon="ph:shield-check"></iconify-icon>
            <span>Settings &amp; Verification</span>
        </a>
        <a href="#">
            <iconify-icon icon="ph:question"></iconify-icon>
            <span>Help Center</span>
        </a>
    </nav>

    <div class="sg-sidebar-footer">
        <a href="<?= $asset_prefix ?>index.php?page=home">
            <iconify-icon icon="ph:sign-out"></iconify-icon>
            <span>Log Out</span>
        </a>
    </div>
</aside>
