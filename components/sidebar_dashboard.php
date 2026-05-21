<?php
$asset_prefix = isset($asset_prefix) ? $asset_prefix : ((strpos($_SERVER['SCRIPT_NAME'], 'views/') !== false) ? '../../' : '');
$dashboard_page = isset($dashboard_page) ? $dashboard_page : '';
?>
<style>
.sg-side-brand {
    gap: 12px !important;
}
.sg-logo-icon {
    position: relative;
    width: 36px;
    height: 36px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    filter: drop-shadow(0 0 8px rgba(190, 240, 0, 0.35));
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.sg-side-brand:hover .sg-logo-icon {
    transform: scale(1.06);
    filter: drop-shadow(0 0 14px rgba(190, 240, 0, 0.55));
}
.sg-logo-icon svg {
    width: 100%;
    height: 100%;
    display: block;
}
.sg-side-brand span {
    color: #e2e6ef !important;
    transition: color 0.3s ease !important;
}
.sg-side-brand:hover span {
    color: #ffffff !important;
}
</style>
<aside class="sg-sidebar" aria-label="Seller dashboard sidebar">
    <a class="sg-side-brand" href="<?= $asset_prefix ?>index.php?page=seller_overview" aria-label="SafeGate dashboard">
        <div class="sg-logo-icon">
            <svg viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="44" height="44" rx="12" fill="#bef000"/>
                <path d="M13 31L21 23L27 28L35 17" stroke="#121A02" stroke-width="4.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M27 17H35V25" stroke="#121A02" stroke-width="4.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <span>SafeGate</span>
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
