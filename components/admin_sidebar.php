<?php
// components/admin_sidebar.php - Navigasi Kiri khusus Admin SafeGate
$page = isset($_GET['page']) ? $_GET['page'] : 'admin_overview';
?>
<aside class="sg-admin-sidebar">
    <div class="sg-admin-sidebar-header">
        <a href="index.php?page=admin_overview" class="sg-admin-brand">
            <iconify-icon icon="ph:shield-chevron-fill" style="color: var(--admin-accent); font-size: 28px;"></iconify-icon>
            <span>SafeGate</span>
        </a>
        <span class="sg-admin-badge">Admin</span>
    </div>
    
    <nav class="sg-admin-sidebar-menu">
        <a href="index.php?page=admin_overview" class="sg-admin-menu-item <?= ($page === 'admin_overview') ? 'is-active' : '' ?>">
            <iconify-icon icon="ph:squares-four-fill"></iconify-icon>
            <span>Overview</span>
        </a>
        
        <a href="index.php?page=admin_transactions" class="sg-admin-menu-item <?= ($page === 'admin_transactions') ? 'is-active' : '' ?>">
            <iconify-icon icon="ph:bank-fill"></iconify-icon>
            <span>Transactions</span>
        </a>
        
        <a href="index.php?page=admin_disputes" class="sg-admin-menu-item <?= ($page === 'admin_disputes') ? 'is-active' : '' ?>">
            <iconify-icon icon="ph:gavel-fill"></iconify-icon>
            <span>Disputes</span>
        </a>
        
        <a href="index.php?page=admin_kyc" class="sg-admin-menu-item <?= ($page === 'admin_kyc') ? 'is-active' : '' ?>">
            <iconify-icon icon="ph:user-focus-fill"></iconify-icon>
            <span>KYC Center</span>
        </a>
    </nav>
    
    <div class="sg-admin-sidebar-footer">
        <a href="index.php?page=home" class="sg-admin-logout-btn">
            <iconify-icon icon="ph:sign-out-bold"></iconify-icon>
            <span>Keluar Portal</span>
        </a>
    </div>
</aside>
