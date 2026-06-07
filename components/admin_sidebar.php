<?php
// components/admin_sidebar.php - Navigasi Kiri khusus Admin SafeGate
$page = isset($_GET['page']) ? $_GET['page'] : 'admin_overview';
$asset_prefix = isset($asset_prefix) ? $asset_prefix : ((strpos($_SERVER['SCRIPT_NAME'], 'views/') !== false) ? '../../' : '');
?>
<aside class="sg-admin-sidebar">
    <div class="sg-admin-sidebar-header">
        <h2>Admin Control</h2>
        <p>Level 4 Authorization</p>
    </div>

    <nav class="sg-admin-sidebar-menu">
        <a href="<?= $asset_prefix ?>index.php?page=admin_overview"
            class="sg-admin-menu-item <?= ($page === 'admin_overview') ? 'is-active' : '' ?>">
            <iconify-icon icon="ph:squares-four-fill"></iconify-icon>
            <span>Dashboard</span>
        </a>

        <a href="<?= $asset_prefix ?>index.php?page=admin_disputes"
            class="sg-admin-menu-item <?= ($page === 'admin_disputes') ? 'is-active' : '' ?>">
            <iconify-icon icon="ph:gavel-fill"></iconify-icon>
            <span>Disputes</span>
        </a>

        <a href="<?= $asset_prefix ?>index.php?page=admin_transactions"
            class="sg-admin-menu-item <?= ($page === 'admin_transactions') ? 'is-active' : '' ?>">
            <iconify-icon icon="ph:bank-fill"></iconify-icon>
            <span>Transactions</span>
        </a>

        <a href="<?= $asset_prefix ?>index.php?page=admin_kyc"
            class="sg-admin-menu-item <?= ($page === 'admin_kyc') ? 'is-active' : '' ?>">
            <iconify-icon icon="ph:user-focus-fill"></iconify-icon>
            <span>Identity Verification</span>
        </a>

        <!-- Mobile only logout item -->
        <a href="<?= $asset_prefix ?>index.php?sg_action=logout" class="sg-admin-menu-item sg-admin-mobile-logout" style="display: none;">
            <iconify-icon icon="ph:sign-out-fill"></iconify-icon>
            <span>Log Out</span>
        </a>
    </nav>

    <div class="sg-admin-sidebar-footer">
        <form action="<?= $asset_prefix ?>index.php?page=admin_overview" method="post" onsubmit="return confirm('WARNING: catat Emergency Lock ke audit log database?')">
            <input type="hidden" name="sg_action" value="admin_emergency_lock">
            <button class="sg-admin-emergency-btn" type="submit">
                <iconify-icon icon="ph:lock-keyhole-fill"></iconify-icon>
                <span>Emergency Lock</span>
            </button>
        </form>
    </div>
</aside>
