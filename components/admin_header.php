<?php
// components/admin_header.php - Navigasi Atas (Topbar) khusus Admin SafeGate
$admin_page = isset($admin_page) ? $admin_page : '';
?>
<header class="sg-admin-topbar">
    <div class="sg-admin-topbar-left">
        <a href="index.php?page=admin_overview" class="sg-admin-topbar-logo">
            <span class="logo-box">
                <iconify-icon icon="ph:shield-chevron-fill"></iconify-icon>
            </span>
            <span class="logo-text">SafeGate</span>
        </a>
    </div>
    
    <nav class="sg-admin-topbar-nav">
        <a href="index.php?page=admin_disputes" class="sg-topbar-nav-link <?= ($admin_page === 'disputes') ? 'is-active' : '' ?>">DISPUTES</a>
        <a href="index.php?page=admin_transactions" class="sg-topbar-nav-link <?= ($admin_page === 'transactions') ? 'is-active' : '' ?>">TRANSACTIONS</a>
        <a href="index.php?page=admin_overview" class="sg-topbar-nav-link <?= ($admin_page === 'overview' || $admin_page === 'kyc_center') ? 'is-active' : '' ?>">SYSTEM</a>
    </nav>
    
    <div class="sg-admin-topbar-right">
        <div class="sg-admin-encrypt-badge">
            <iconify-icon icon="ph:shield-check-fill"></iconify-icon>
            <span>System: Encrypted</span>
        </div>
        <button class="sg-admin-icon-btn notification-btn">
            <iconify-icon icon="ph:bell-bold"></iconify-icon>
            <span class="notification-dot"></span>
        </button>
        <button class="sg-admin-icon-btn">
            <iconify-icon icon="ph:gear-bold"></iconify-icon>
        </button>
        <div class="sg-admin-avatar">
            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&h=100&q=80" alt="Admin Avatar">
        </div>
    </div>
</header>
