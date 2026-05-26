<?php
$asset_prefix = isset($asset_prefix) ? $asset_prefix : ((strpos($_SERVER['SCRIPT_NAME'], 'views/') !== false) ? '../../' : '');
$dashboard_page = isset($dashboard_page) ? $dashboard_page : '';
?>
<style>
/* Logo Brand Styling */
.sg-side-brand {
    gap: 12px !important;
    color: #fff !important;
    text-decoration: none !important;
}
.sg-logo-icon {
    position: relative;
    width: 36px;
    height: 36px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 9px;
    background: var(--safegate-neon, #d9ff00);
    box-shadow: 0 0 16px rgba(217, 255, 0, 0.38);
    filter: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.sg-side-brand:hover .sg-logo-icon {
    transform: scale(1.05) rotate(2deg);
    box-shadow: 0 0 20px rgba(217, 255, 0, 0.55);
}
.sg-logo-icon svg {
    width: 20px;
    height: 20px;
    display: block;
}
.sg-side-brand span {
    color: #ffffff !important;
    font-size: 24px !important;
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
    gap: 12px !important;
}
.sg-dashboard-frame .sg-logo-icon {
    width: 36px;
    height: 36px;
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
    margin-left: auto !important;
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

/* Width and responsive behavior for Desktop screens */
@media (min-width: 861px) {
    /* Widened sidebar layout to prevent text wrapping */
    .sg-dashboard-frame {
        grid-template-columns: 320px 1fr !important;
        transition: grid-template-columns 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }
    
    .sg-sidebar {
        width: 320px !important;
        transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }
    
    /* Collapsed Sidebar overrides */
    .sg-dashboard-frame.sg-sidebar-collapsed {
        grid-template-columns: 80px 1fr !important;
    }

    .sg-sidebar-collapsed .sg-sidebar {
        width: 80px !important;
    }

    .sg-sidebar-collapsed .sg-sidebar-header {
        padding: 16px 0 12px 0 !important; /* Top padding to prevent crowding the top page edge */
        justify-content: center !important;
        flex-direction: column-reverse !important; /* Place hamburger on top of the logo */
        min-height: 126px !important;
        gap: 9px !important;
    }

    .sg-sidebar-collapsed .sg-side-brand span {
        display: none !important;
    }

    .sg-sidebar-collapsed .sg-sidebar-toggle {
        margin-left: 0 !important;
        margin-top: 0 !important;
        width: 34px !important;
        height: 34px !important;
        padding: 0 !important;
        border-radius: 12px !important;
        background: rgba(255, 255, 255, 0.04) !important;
    }

    .sg-sidebar-collapsed .sg-logo-icon {
        width: 38px !important;
        height: 38px !important;
        border-radius: 10px !important;
    }

    .sg-sidebar-collapsed .sg-logo-icon svg {
        width: 17px !important;
        height: 17px !important;
    }

    /* Collapsed Seller Card overrides */
    .sg-sidebar-collapsed .sg-seller-card {
        padding: 18px 0 !important;
        justify-content: center !important;
        align-items: center !important;
        min-height: 74px !important;
        margin: 0 !important;
        box-sizing: border-box !important;
    }
    .sg-sidebar-collapsed .sg-seller-card div:not(.sg-seller-avatar) {
        display: none !important;
    }
    .sg-sidebar-collapsed .sg-seller-avatar {
        width: 34px !important;
        height: 34px !important;
        margin: 0 auto !important;
    }

    /* Collapsed Sidebar Navigation overrides */
    .sg-sidebar-collapsed .sg-sidebar-nav {
        padding: 16px 10px !important;
        gap: 10px !important;
        align-items: center !important;
    }
    .sg-sidebar-collapsed .sg-sidebar-nav a {
        padding: 0 !important;
        justify-content: center !important;
        width: 50px !important;
        min-height: 50px !important;
        border-radius: 12px !important;
    }
    .sg-sidebar-collapsed .sg-sidebar-nav a iconify-icon {
        font-size: 23px !important;
        flex-basis: auto !important;
    }
    .sg-sidebar-collapsed .sg-sidebar-nav a span {
        display: none !important;
    }
    .sg-sidebar-collapsed .sg-sidebar-nav a::after {
        left: -6px !important;
        right: auto !important;
        top: 10px !important;
        height: 30px !important;
        width: 4px !important;
    }

    /* Collapsed Nav Divider overrides */
    .sg-sidebar-collapsed .sg-sidebar-nav hr {
        width: 50px !important;
        margin: 6px 0 !important;
    }

    /* Collapsed Sidebar Footer overrides */
    .sg-sidebar-collapsed .sg-sidebar-footer {
        padding: 18px 10px !important;
        justify-content: center !important;
    }
    .sg-sidebar-collapsed .sg-sidebar-footer a {
        padding: 0 !important;
        justify-content: center !important;
        width: 50px !important;
        min-height: 50px !important;
        border-radius: 12px !important;
    }
    .sg-sidebar-collapsed .sg-sidebar-footer a iconify-icon {
        font-size: 23px !important;
    }
    .sg-sidebar-collapsed .sg-sidebar-footer a span {
        display: none !important;
    }
}

/* Responsive Accordion/Dropdown behavior for Mobile/Tablet screens */
@keyframes fadeInMobile {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 860px) {
    .sg-dashboard-frame {
        display: flex !important;
        flex-direction: column !important;
        grid-template-columns: 1fr !important;
        min-height: 100vh !important;
    }
    
    .sg-sidebar {
        width: 100% !important;
        height: auto !important;
        min-height: auto !important;
        position: relative !important;
        top: 0 !important;
        border-right: none !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
        transition: all 0.3s ease-in-out !important;
        z-index: 1000 !important;
    }
    
    .sg-sidebar-header {
        min-height: 70px !important;
        padding: 0 20px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        flex-direction: row !important;
        border-bottom: none !important;
    }
    
    .sg-sidebar-collapsed .sg-sidebar-header {
        border-bottom: none !important;
    }
    
    /* When collapsed on mobile, hide the menu content */
    .sg-sidebar-collapsed .sg-sidebar-nav,
    .sg-sidebar-collapsed .sg-seller-card,
    .sg-sidebar-collapsed .sg-sidebar-footer {
        display: none !important;
    }
    
    /* When expanded on mobile, display the menu content vertically */
    .sg-dashboard-frame:not(.sg-sidebar-collapsed) .sg-sidebar-nav,
    .sg-dashboard-frame:not(.sg-sidebar-collapsed) .sg-seller-card,
    .sg-dashboard-frame:not(.sg-sidebar-collapsed) .sg-sidebar-footer {
        display: flex !important;
        animation: fadeInMobile 0.25s cubic-bezier(0.4, 0, 0.2, 1) both;
    }
    
    .sg-dashboard-frame:not(.sg-sidebar-collapsed) .sg-seller-card {
        padding: 20px !important;
        min-height: auto !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06) !important;
    }
    
    .sg-dashboard-frame:not(.sg-sidebar-collapsed) .sg-sidebar-nav {
        display: grid !important;
        padding: 16px 20px !important;
        gap: 8px !important;
    }
    
    .sg-dashboard-frame:not(.sg-sidebar-collapsed) .sg-sidebar-footer {
        padding: 20px 20px !important;
        border-top: 1px solid rgba(255, 255, 255, 0.06) !important;
    }
    
    /* Overrides for mobile to keep items looking clean and proportional */
    .sg-sidebar-collapsed .sg-side-brand span {
        display: block !important;
    }
    
    .sg-sidebar-toggle {
        margin-top: 0 !important;
    }
    
    .sg-dashboard-main {
        padding: 16px !important;
    }
    
    .sg-dashboard-main > section {
        zoom: 1 !important; /* Reset zoom on mobile/tablets for pristine readability */
    }
}

/* Force Transaction History to use the exact same sidebar as every seller page. */
.sg-page-transaction .sg-sidebar {
    background: #080a0e !important;
    border-right: 1px solid rgba(255, 255, 255, 0.06) !important;
}

.sg-page-transaction .sg-sidebar-header {
    display: flex !important;
    align-items: center !important;
    justify-content: flex-start !important;
    gap: 12px !important;
    min-height: 64px !important;
    padding: 0 12px 0 18px !important;
    border-bottom: 1px solid rgba(255, 255, 255, 0.06) !important;
}

.sg-page-transaction .sg-side-brand {
    gap: 12px !important;
    min-height: 0 !important;
    padding: 0 !important;
    border-bottom: none !important;
}

.sg-page-transaction .sg-logo-icon {
    width: 36px !important;
    height: 36px !important;
}

.sg-page-transaction .sg-side-brand span {
    color: #ffffff !important;
    font-size: 24px !important;
    font-weight: 800 !important;
    letter-spacing: -0.04em !important;
}

.sg-page-transaction .sg-seller-card {
    min-height: 98px !important;
    gap: 12px !important;
    margin: 0 !important;
    padding: 26px 20px !important;
    align-items: center !important;
    border-bottom: 1px solid rgba(255, 255, 255, 0.06) !important;
}

.sg-page-transaction .sg-seller-avatar {
    width: 40px !important;
    height: 40px !important;
}

.sg-page-transaction .sg-seller-card strong {
    color: #fff !important;
    font-size: 15px !important;
    line-height: 1 !important;
    letter-spacing: -0.02em !important;
}

.sg-page-transaction .sg-seller-card span {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: 96px !important;
    height: 20px !important;
    margin-top: 6px !important;
    padding: 0 8px !important;
    border: 1px solid #00d99b !important;
    border-radius: 3px !important;
    color: #00ffa3 !important;
    font-size: 9px !important;
    font-weight: 900 !important;
    letter-spacing: 0.04em !important;
    text-transform: uppercase !important;
}

.sg-page-transaction .sg-sidebar-nav {
    gap: 6px !important;
    padding: 16px 12px !important;
}

.sg-page-transaction .sg-sidebar-nav a {
    min-height: 44px !important;
    gap: 12px !important;
    padding: 0 14px !important;
    border-radius: 0 !important;
    color: #8e95a3 !important;
    font-size: 15px !important;
    font-weight: 500 !important;
}

.sg-page-transaction .sg-sidebar-nav a iconify-icon {
    flex-basis: 21px !important;
    color: #9ba2af !important;
    font-size: 21px !important;
}

.sg-page-transaction .sg-sidebar-nav a.is-active {
    color: var(--safegate-neon, #d9ff00) !important;
    background: linear-gradient(90deg, rgba(217, 255, 0, 0.05), transparent) !important;
}

.sg-page-transaction .sg-sidebar-nav a.is-active iconify-icon {
    color: var(--safegate-neon, #d9ff00) !important;
}

.sg-page-transaction .sg-sidebar-nav a.is-active::after {
    top: 8px !important;
    right: 0 !important;
    left: auto !important;
    width: 4px !important;
    height: 28px !important;
    border-radius: 4px 0 0 4px !important;
}

.sg-page-transaction .sg-sidebar-footer {
    padding: 24px 20px !important;
    border-top: 1px solid rgba(255, 255, 255, 0.06) !important;
}

.sg-page-transaction .sg-sidebar-footer a {
    gap: 12px !important;
    color: #777d89 !important;
    font-size: 15px !important;
}

.sg-page-transaction .sg-sidebar-footer a iconify-icon {
    font-size: 21px !important;
}

@media (min-width: 861px) {
    .sg-page-transaction .sg-dashboard-frame {
        grid-template-columns: 320px 1fr !important;
    }

    .sg-page-transaction .sg-sidebar {
        width: 320px !important;
        top: 0 !important;
        height: 100vh !important;
    }

    .sg-page-transaction .sg-dashboard-frame.sg-sidebar-collapsed {
        grid-template-columns: 80px 1fr !important;
    }

    .sg-page-transaction .sg-sidebar-collapsed .sg-sidebar {
        width: 80px !important;
    }

    .sg-page-transaction .sg-sidebar-collapsed .sg-sidebar-header {
        padding: 16px 0 12px 0 !important;
        justify-content: center !important;
        flex-direction: column-reverse !important;
        min-height: 126px !important;
        gap: 9px !important;
    }

    .sg-page-transaction .sg-sidebar-collapsed .sg-side-brand span {
        display: none !important;
    }

    .sg-page-transaction .sg-sidebar-collapsed .sg-sidebar-toggle {
        margin-left: 0 !important;
        margin-top: 0 !important;
        width: 34px !important;
        height: 34px !important;
        padding: 0 !important;
        border-radius: 12px !important;
        background: rgba(255, 255, 255, 0.04) !important;
    }

    .sg-page-transaction .sg-sidebar-collapsed .sg-logo-icon {
        width: 38px !important;
        height: 38px !important;
        border-radius: 10px !important;
    }

    .sg-page-transaction .sg-sidebar-collapsed .sg-logo-icon svg {
        width: 17px !important;
        height: 17px !important;
    }

    .sg-page-transaction .sg-sidebar-collapsed .sg-seller-card {
        padding: 18px 0 !important;
        justify-content: center !important;
        align-items: center !important;
        min-height: 74px !important;
        margin: 0 !important;
        box-sizing: border-box !important;
    }

    .sg-page-transaction .sg-sidebar-collapsed .sg-seller-card div:not(.sg-seller-avatar) {
        display: none !important;
    }

    .sg-page-transaction .sg-sidebar-collapsed .sg-seller-avatar {
        width: 34px !important;
        height: 34px !important;
        margin: 0 auto !important;
    }

    .sg-page-transaction .sg-sidebar-collapsed .sg-sidebar-nav {
        padding: 16px 10px !important;
        gap: 10px !important;
        align-items: center !important;
    }

    .sg-page-transaction .sg-sidebar-collapsed .sg-sidebar-nav a {
        padding: 0 !important;
        justify-content: center !important;
        width: 50px !important;
        min-height: 50px !important;
        border-radius: 12px !important;
    }

    .sg-page-transaction .sg-sidebar-collapsed .sg-sidebar-nav a iconify-icon {
        font-size: 23px !important;
        flex-basis: auto !important;
    }

    .sg-page-transaction .sg-sidebar-collapsed .sg-sidebar-nav a span,
    .sg-page-transaction .sg-sidebar-collapsed .sg-sidebar-footer a span {
        display: none !important;
    }

    .sg-page-transaction .sg-sidebar-collapsed .sg-sidebar-nav a::after {
        left: -6px !important;
        right: auto !important;
        top: 10px !important;
        height: 30px !important;
        width: 4px !important;
    }

    .sg-page-transaction .sg-sidebar-collapsed .sg-sidebar-footer {
        padding: 18px 10px !important;
        justify-content: center !important;
    }

    .sg-page-transaction .sg-sidebar-collapsed .sg-sidebar-footer a {
        padding: 0 !important;
        justify-content: center !important;
        width: 50px !important;
        min-height: 50px !important;
        border-radius: 12px !important;
    }

    .sg-page-transaction .sg-sidebar-collapsed .sg-sidebar-footer a iconify-icon {
        font-size: 23px !important;
    }
}
</style>
<aside class="sg-sidebar" aria-label="Seller dashboard sidebar">
    <div class="sg-sidebar-header">
        <a class="sg-side-brand" href="<?= $asset_prefix ?>index.php?page=seller_overview" aria-label="SafeGate dashboard">
            <div class="sg-logo-icon">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18" stroke="#090B10" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"></polyline>
                    <polyline points="17 6 23 6 23 12" stroke="#090B10" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"></polyline>
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
        <a href="<?= $asset_prefix ?>index.php?sg_action=logout">
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
