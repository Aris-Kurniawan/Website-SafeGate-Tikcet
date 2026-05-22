<?php
$asset_prefix = isset($asset_prefix) ? $asset_prefix : ((strpos($_SERVER['SCRIPT_NAME'], 'views/') !== false) ? '../../' : '');
$dashboard_page = isset($dashboard_page) ? $dashboard_page : '';
?>
<style>
/* Logo Brand Styling */
.sg-side-brand {
    gap: 12px !important;
}
.sg-logo-icon {
    position: relative;
    width: 35px;
    height: 35px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    filter: drop-shadow(0 0 10px rgba(190, 240, 0, 0.4));
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.sg-side-brand:hover .sg-logo-icon {
    transform: scale(1.06);
    filter: drop-shadow(0 0 16px rgba(190, 240, 0, 0.6));
}
.sg-logo-icon svg {
    width: 100%;
    height: 100%;
    display: block;
}
.sg-side-brand span {
    color: #e2e6ef !important;
    font-size: 28px !important;
    font-weight: 800 !important;
    letter-spacing: -0.04em !important;
    line-height: 1 !important;
    transition: color 0.3s ease !important;
}
.sg-side-brand:hover span {
    color: #ffffff !important;
}

/* Sidebar Nav Item Styling Overrides (Normal Mode) */
.sg-sidebar-nav a {
    font-size: 18px !important;
    min-height: 52px !important;
    gap: 14px !important;
    padding: 0 18px !important;
}
.sg-sidebar-nav a iconify-icon {
    font-size: 24px !important;
    flex-basis: 24px !important;
}

/* Sidebar Footer Styling Overrides (Normal Mode) */
.sg-sidebar-footer a {
    font-size: 18px !important;
    gap: 14px !important;
}
.sg-sidebar-footer a iconify-icon {
    font-size: 24px !important;
}

/* Compact layout adjustments */
.sg-dashboard-frame .sg-side-brand {
    gap: 10px !important;
}
.sg-dashboard-frame .sg-logo-icon {
    width: 30px;
    height: 30px;
}
.sg-dashboard-frame .sg-side-brand span {
    font-size: 24px !important;
}

/* Compact Sidebar Nav Item Styling Overrides */
.sg-dashboard-frame .sg-sidebar-nav a {
    font-size: 15px !important;
    min-height: 44px !important;
    gap: 12px !important;
    padding: 0 14px !important;
}
.sg-dashboard-frame .sg-sidebar-nav a iconify-icon {
    font-size: 21px !important;
    flex-basis: 21px !important;
}
.sg-dashboard-frame .sg-sidebar-nav a.is-active::after {
    top: 8px !important;
    height: 28px !important;
    width: 4px !important;
}

/* Compact Sidebar Seller Card Overrides */
.sg-dashboard-frame .sg-seller-card strong {
    font-size: 15px !important;
}
.sg-dashboard-frame .sg-seller-card span {
    width: 96px !important;
    height: 20px !important;
    font-size: 9px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
}

/* Compact Sidebar Footer Overrides */
.sg-dashboard-frame .sg-sidebar-footer {
    padding: 24px 20px !important;
}
.sg-dashboard-frame .sg-sidebar-footer a {
    font-size: 15px !important;
    gap: 12px !important;
}
.sg-dashboard-frame .sg-sidebar-footer a iconify-icon {
    font-size: 21px !important;
}

/* Sidebar Header & Toggle */
.sg-sidebar-header {
    display: flex;
    align-items: center;
    justify-content: flex-start !important;
    gap: 16px !important;
    min-height: 90px;
    padding: 0 20px 0 28px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Remove default sidebar brand border & padding */
.sg-sidebar .sg-side-brand {
    border-bottom: none !important;
    padding: 0 !important;
    min-height: 0 !important;
    gap: 12px !important;
}

.sg-sidebar-toggle {
    background: transparent;
    border: none;
    color: #9ea5af;
    font-size: 24px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 8px;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.sg-sidebar-toggle:hover {
    color: #fff;
    background: rgba(255, 255, 255, 0.05);
}

/* Compact layout adjustments */
.sg-dashboard-frame .sg-sidebar-header {
    min-height: 64px !important;
    padding: 0 12px 0 18px !important;
    justify-content: flex-start !important;
    gap: 12px !important;
}

/* Collapsed Sidebar overrides */
.sg-dashboard-frame.sg-sidebar-collapsed {
    grid-template-columns: 80px 1fr !important;
}

.sg-sidebar {
    transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
}

.sg-sidebar-collapsed .sg-sidebar {
    width: 80px !important;
}

.sg-sidebar-collapsed .sg-sidebar-header {
    padding: 24px 0 16px 0 !important; /* Top padding to prevent crowding the top page edge */
    justify-content: center !important;
    flex-direction: column-reverse !important; /* Place hamburger on top of the logo */
    min-height: 110px !important;
    gap: 12px !important;
}

.sg-sidebar-collapsed .sg-side-brand span {
    display: none !important;
}

.sg-sidebar-collapsed .sg-sidebar-toggle {
    margin-top: 0 !important;
}

/* Collapsed Seller Card overrides */
.sg-sidebar-collapsed .sg-seller-card {
    padding: 20px 0 !important;
    justify-content: center !important;
    min-height: 0 !important;
    margin: 0 !important;
}
.sg-sidebar-collapsed .sg-seller-card div:not(.sg-seller-avatar) {
    display: none !important;
}
.sg-sidebar-collapsed .sg-seller-avatar {
    margin: 0 auto !important;
}

/* Collapsed Sidebar Navigation overrides */
.sg-sidebar-collapsed .sg-sidebar-nav {
    padding: 16px 8px !important;
    gap: 8px !important;
}
.sg-sidebar-collapsed .sg-sidebar-nav a {
    padding: 0 !important;
    justify-content: center !important;
    min-height: 44px !important;
    border-radius: 12px !important;
}
.sg-sidebar-collapsed .sg-sidebar-nav a span {
    display: none !important;
}
.sg-sidebar-collapsed .sg-sidebar-nav a::after {
    left: 2px !important;
    right: auto !important;
}

/* Collapsed Nav Divider overrides */
.sg-sidebar-collapsed .sg-sidebar-nav hr {
    margin: 6px 4px !important;
}

/* Collapsed Sidebar Footer overrides */
.sg-sidebar-collapsed .sg-sidebar-footer {
    padding: 20px 0 !important;
    justify-content: center !important;
}
.sg-sidebar-collapsed .sg-sidebar-footer a {
    padding: 0 !important;
    justify-content: center !important;
}
.sg-sidebar-collapsed .sg-sidebar-footer a span {
    display: none !important;
}
</style>
<aside class="sg-sidebar" aria-label="Seller dashboard sidebar">
    <div class="sg-sidebar-header">
        <a class="sg-side-brand" href="<?= $asset_prefix ?>index.php?page=seller_overview" aria-label="SafeGate dashboard">
            <div class="sg-logo-icon">
                <svg viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="44" height="44" rx="12" fill="#bef000"/>
                    <path d="M11 29L19 21L25 26L33 15" stroke="#121A02" stroke-width="4.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M25 15H33V23" stroke="#121A02" stroke-width="4.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <span>SafeGate</span>
        </a>
        <button class="sg-sidebar-toggle" aria-label="Toggle sidebar" onclick="toggleSidebar()">
            <iconify-icon icon="ph:list" id="sidebar-toggle-icon"></iconify-icon>
        </button>
    </div>

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

<script>
function toggleSidebar() {
    const frame = document.querySelector('.sg-dashboard-frame');
    const icon = document.getElementById('sidebar-toggle-icon');
    if (frame) {
        frame.classList.toggle('sg-sidebar-collapsed');
        const isCollapsed = frame.classList.contains('sg-sidebar-collapsed');
        
        // Persist user preference in localStorage
        localStorage.setItem('sg-sidebar-collapsed', isCollapsed ? 'true' : 'false');
        
        // Dynamic icon swap
        if (icon) {
            icon.setAttribute('icon', isCollapsed ? 'ph:list-bold' : 'ph:list');
        }
    }
}

// Restore state on load
document.addEventListener('DOMContentLoaded', () => {
    const isCollapsed = localStorage.getItem('sg-sidebar-collapsed') === 'true';
    if (isCollapsed) {
        const frame = document.querySelector('.sg-dashboard-frame');
        const icon = document.getElementById('sidebar-toggle-icon');
        if (frame) {
            frame.classList.add('sg-sidebar-collapsed');
        }
        if (icon) {
            icon.setAttribute('icon', 'ph:list-bold');
        }
    }
});
</script>
