<?php
// components/admin_header.php - Navigasi Atas (Topbar) khusus Admin SafeGate
?>
<header class="sg-admin-header">
    <div class="sg-admin-header-left">
        <a href="index.php?page=admin_overview" class="sg-admin-header-logo-link">
            <div class="sg-admin-header-logo-box">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#090B10"
                    stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"
                    style="width: 16px; height: 16px;">
                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                    <polyline points="17 6 23 6 23 12"></polyline>
                </svg>
            </div>
            <span class="sg-admin-header-title">SafeGate</span>
        </a>
        <span class="sg-admin-header-separator"></span>
        <span class="sg-admin-header-subtitle">INSTITUTIONAL ADMIN</span>
    </div>

    <div class="sg-admin-header-right">
        <div class="sg-admin-status-pill">
            <iconify-icon icon="ph:shield-check-fill"></iconify-icon>
            <span>System: Encrypted</span>
        </div>

        <button class="sg-admin-icon-btn" aria-label="Notifications">
            <iconify-icon icon="ph:bell"></iconify-icon>
            <span class="sg-admin-badge-dot"></span>
        </button>

        <button class="sg-admin-icon-btn" aria-label="Settings">
            <iconify-icon icon="ph:gear-six"></iconify-icon>
        </button>

        <a href="index.php?sg_action=logout" class="sg-admin-logout-link">Log Out</a>

        <div class="sg-admin-profile">
            <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&q=80&w=120"
                alt="Admin Avatar" class="sg-admin-avatar">
        </div>
    </div>

    <!-- Hamburger menu for mobile responsive navbar -->
    <button class="sg-admin-hamburger" aria-label="Toggle Menu" onclick="toggleAdminMobileMenu()">
        <iconify-icon icon="ph:list" id="sg-admin-hamburger-icon"></iconify-icon>
    </button>

    <script>
    function toggleAdminMobileMenu() {
        const sidebar = document.querySelector('.sg-admin-sidebar');
        const icon = document.getElementById('sg-admin-hamburger-icon');
        if (sidebar) {
            sidebar.classList.toggle('is-open');
            const isOpen = sidebar.classList.contains('is-open');
            if (icon) {
                icon.setAttribute('icon', isOpen ? 'ph:x' : 'ph:list');
            }
        }
    }
    </script>
</header>
