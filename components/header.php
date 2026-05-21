<?php
$current_page = isset($_GET['page']) ? $_GET['page'] : 'home';
// Highlight Events ('home') when viewing ticket details
if ($current_page === 'detail_tiket') {
    $current_page = 'home';
}
?>
<header class="w-100 py-3 px-4 border-bottom bg-safegate-bg sticky-top"
    style="border-color: rgba(255,255,255,0.05) !important; z-index: 1030; opacity: 0.95;">
    <div class="container-fluid mx-auto d-flex align-items-center justify-content-between" style="max-width: 1200px;">
        <!-- Logo -->
        <div class="d-flex align-items-center gap-3">
            <a href="index.php?page=home" class="d-flex align-items-center gap-2 text-decoration-none">
                <div class="safegate-logo-box" style="width: 28px; height: 28px; border-radius: 7px; box-shadow: 0 0 12px rgba(217, 255, 0, 0.35);">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#090B10" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round" style="width: 15px; height: 15px;">
                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                        <polyline points="17 6 23 6 23 12"></polyline>
                    </svg>
                </div>
                <span class="fs-5 fw-bold text-white letter-spacing-tight">SafeGate</span>
            </a>
        </div>

        <!-- Navigation -->
        <nav class="d-none d-md-flex align-items-center gap-5 fw-medium">
            <a href="index.php?page=home" class="<?= ($current_page === 'home') ? 'text-white' : 'text-safegate-text-sec' ?> text-decoration-none position-relative hover-white"
                style="font-size: 0.85rem; <?= ($current_page === 'home') ? 'color: var(--safegate-neon) !important;' : '' ?>">
                Events
                <?php if ($current_page === 'home'): ?>
                    <span class="position-absolute start-0 w-100 bg-safegate-neon nav-active-bar"
                        style="bottom: -10px; height: 2px; box-shadow: 0 0 10px var(--safegate-neon);"></span>
                <?php endif; ?>
            </a>
            <a href="index.php?page=penjualan" class="<?= ($current_page === 'penjualan') ? 'text-white' : 'text-safegate-text-sec' ?> text-decoration-none position-relative hover-white"
                style="font-size: 0.85rem; <?= ($current_page === 'penjualan') ? 'color: var(--safegate-neon) !important;' : '' ?>">
                Penjualan
                <?php if ($current_page === 'penjualan'): ?>
                    <span class="position-absolute start-0 w-100 bg-safegate-neon nav-active-bar"
                        style="bottom: -10px; height: 2px; box-shadow: 0 0 10px var(--safegate-neon);"></span>
                <?php endif; ?>
            </a>
            <a href="index.php?page=cara_kerja" class="<?= ($current_page === 'cara_kerja') ? 'text-white' : 'text-safegate-text-sec' ?> text-decoration-none position-relative hover-white"
                style="font-size: 0.85rem; <?= ($current_page === 'cara_kerja') ? 'color: var(--safegate-neon) !important;' : '' ?>">
                Cara Kerja
                <?php if ($current_page === 'cara_kerja'): ?>
                    <span class="position-absolute start-0 w-100 bg-safegate-neon nav-active-bar"
                        style="bottom: -10px; height: 2px; box-shadow: 0 0 10px var(--safegate-neon);"></span>
                <?php endif; ?>
            </a>
        </nav>

        <!-- Actions -->
        <div class="d-none d-md-flex align-items-center gap-4">
            <button class="btn btn-outline-safegate-neon rounded-pill d-flex align-items-center gap-2 fw-bold"
                style="font-size: 0.7rem; padding: 0.35rem 1.25rem; letter-spacing: 0.05em; border-color: rgba(217, 255, 0, 0.3);">
                <iconify-icon icon="ph:shield-check-fill" style="font-size: 14px;"></iconify-icon> SECURED
            </button>
            <a href="index.php?page=login" class="text-white text-decoration-none fw-semibold hover-neon"
                style="font-size: 0.85rem;">Login</a>
            <a href="index.php?page=signup" class="btn btn-safegate-neon rounded-pill fw-bold"
                style="padding: 0.5rem 1.5rem; font-size: 0.85rem;">
                Sign Up
            </a>
        </div>
    </div>
</header>