<?php
$current_page = isset($_GET['page']) ? $_GET['page'] : 'home';
// Highlight Events ('home') when viewing ticket details
if ($current_page === 'detail_tiket') {
    $current_page = 'home';
}

$userId = sg_current_user_id();
$unread_count = 0;
$dbUser = null;
$initials = '';
$dashboardLink = 'index.php?page=buyer_dashboard';
$dashboardLabel = 'Dashboard';
$userRole = 'buyer';

if ($userId) {
    $userRole = $_SESSION['role'] ?? 'buyer';
    $dbUser = sg_fetch_one('SELECT full_name, profile_photo_path FROM users WHERE id = :id', ['id' => $userId]);
    $initials = sg_user_initials($dbUser['full_name'] ?? 'User');

    // Tentukan link dashboard berdasarkan role
    if ($userRole === 'admin') {
        $dashboardLink = 'index.php?page=admin_overview';
        $dashboardLabel = 'Admin Panel';
    } elseif ($userRole === 'seller') {
        $dashboardLink = 'index.php?page=seller_overview';
        $dashboardLabel = 'Seller Panel';
    }

    if ($userRole === 'buyer') {
        $unread_count = sg_unread_notification_count($userId);
    }
}
?>
<header class="w-100 py-3 px-4 border-bottom bg-safegate-bg sticky-top"
    style="border-color: rgba(255,255,255,0.05) !important; z-index: 1030; opacity: 0.95;">
    <div class="container-fluid mx-auto d-flex flex-wrap align-items-center justify-content-between"
        style="max-width: 1200px;">
        <!-- Logo -->
        <div class="d-flex align-items-center gap-3">
            <a href="index.php?page=home" class="d-flex align-items-center gap-2 text-decoration-none">
                <div class="safegate-logo-box"
                    style="width: 28px; height: 28px; border-radius: 7px; box-shadow: 0 0 12px rgba(217, 255, 0, 0.35);">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#090B10"
                        stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"
                        style="width: 15px; height: 15px;">
                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                        <polyline points="17 6 23 6 23 12"></polyline>
                    </svg>
                </div>
                <span class="fs-5 fw-bold text-white letter-spacing-tight">SafeGate</span>
            </a>
        </div>

        <!-- Mobile Actions & Hamburger Toggle (Mobile Only) -->
        <div class="d-flex d-md-none align-items-center gap-3">
            <?php if ($userId && $userRole === 'buyer'): ?>
                <a href="index.php?page=buyer_dashboard"
                    class="text-white text-decoration-none hover-neon position-relative"
                    style="font-size: 1.2rem; display: inline-flex; align-items: center;" title="Notifications">
                    <iconify-icon icon="ph:bell"></iconify-icon>
                    <?php if ($unread_count > 0): ?>
                        <span class="position-absolute translate-middle badge rounded-pill bg-danger"
                            style="font-size: 8px; padding: 2px 4px; top: 0px; left: 16px; border: 1.5px solid var(--safegate-bg);">
                            <?= $unread_count ?>
                        </span>
                    <?php endif; ?>
                </a>
            <?php endif; ?>
            <button class="btn border-0 p-1 text-white" type="button" data-bs-toggle="collapse"
                data-bs-target="#mobileNavbar" aria-controls="mobileNavbar" aria-expanded="false"
                aria-label="Toggle navigation">
                <iconify-icon icon="ph:list-bold" style="font-size: 1.5rem; display: block;"></iconify-icon>
            </button>
        </div>

        <!-- Navigation (Desktop Only) -->
        <nav class="d-none d-md-flex align-items-center gap-5 fw-medium">
            <a href="index.php?page=home"
                class="<?= ($current_page === 'home') ? 'text-white' : 'text-safegate-text-sec' ?> text-decoration-none position-relative hover-white"
                style="font-size: 0.85rem; <?= ($current_page === 'home') ? 'color: var(--safegate-neon) !important;' : '' ?>">
                Events
                <?php if ($current_page === 'home'): ?>
                    <span class="position-absolute start-0 w-100 bg-safegate-neon nav-active-bar"
                        style="bottom: -10px; height: 2px; box-shadow: 0 0 10px var(--safegate-neon);"></span>
                <?php endif; ?>
            </a>
            <a href="index.php?page=penjualan"
                class="<?= ($current_page === 'penjualan') ? 'text-white' : 'text-safegate-text-sec' ?> text-decoration-none position-relative hover-white"
                style="font-size: 0.85rem; <?= ($current_page === 'penjualan') ? 'color: var(--safegate-neon) !important;' : '' ?>">
                Penjualan
                <?php if ($current_page === 'penjualan'): ?>
                    <span class="position-absolute start-0 w-100 bg-safegate-neon nav-active-bar"
                        style="bottom: -10px; height: 2px; box-shadow: 0 0 10px var(--safegate-neon);"></span>
                <?php endif; ?>
            </a>
            <a href="index.php?page=cara_kerja"
                class="<?= ($current_page === 'cara_kerja') ? 'text-white' : 'text-safegate-text-sec' ?> text-decoration-none position-relative hover-white"
                style="font-size: 0.85rem; <?= ($current_page === 'cara_kerja') ? 'color: var(--safegate-neon) !important;' : '' ?>">
                Cara Kerja
                <?php if ($current_page === 'cara_kerja'): ?>
                    <span class="position-absolute start-0 w-100 bg-safegate-neon nav-active-bar"
                        style="bottom: -10px; height: 2px; box-shadow: 0 0 10px var(--safegate-neon);"></span>
                <?php endif; ?>
            </a>
        </nav>

        <!-- Actions (Desktop Only) -->
        <div class="d-none d-md-flex align-items-center gap-4">
            <button class="btn btn-outline-safegate-neon rounded-pill d-flex align-items-center gap-2 fw-bold"
                style="font-size: 0.7rem; padding: 0.35rem 1.25rem; letter-spacing: 0.05em; border-color: rgba(217, 255, 0, 0.3);">
                <iconify-icon icon="ph:shield-check-fill" style="font-size: 14px;"></iconify-icon> SECURED
            </button>
            <?php if ($userId): ?>
                <!-- Notifications Bell -->
                <?php if ($userRole === 'buyer'): ?>
                    <a href="index.php?page=buyer_dashboard"
                        class="text-white text-decoration-none hover-neon position-relative me-2"
                        style="font-size: 1.2rem; display: inline-flex; align-items: center;" title="Notifications">
                        <iconify-icon icon="ph:bell"></iconify-icon>
                        <?php if ($unread_count > 0): ?>
                            <span class="position-absolute translate-middle badge rounded-pill bg-danger"
                                style="font-size: 8px; padding: 2px 4px; top: 0px; left: 16px; border: 1.5px solid var(--safegate-bg);">
                                <?= $unread_count ?>
                            </span>
                        <?php endif; ?>
                    </a>
                <?php endif; ?>

                <!-- User Profile Dropdown -->
                <div class="dropdown">
                    <button
                        class="btn btn-link text-white d-flex align-items-center gap-2 text-decoration-none dropdown-toggle p-0"
                        type="button" id="userMenuDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php if (!empty($dbUser['profile_photo_path']) && $dbUser['profile_photo_path'] !== 'pending-upload'): ?>
                            <img src="<?= sg_h($dbUser['profile_photo_path']) ?>" alt="Avatar" class="rounded-circle"
                                style="width: 32px; height: 32px; object-fit: cover; border: 1.5px solid var(--safegate-neon);">
                        <?php else: ?>
                            <div class="rounded-circle bg-safegate-neon text-black d-flex align-items-center justify-content-center fw-bold"
                                style="width: 32px; height: 32px; font-size: 0.85rem; box-shadow: 0 0 10px rgba(217, 255, 0, 0.2);">
                                <?= $initials ?>
                            </div>
                        <?php endif; ?>
                        <span
                            class="fs-7 fw-semibold hover-neon text-white"><?= sg_h($dbUser['full_name'] ?? 'User') ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark border-0 rounded-3 p-2 mt-2 shadow-lg"
                        aria-labelledby="userMenuDropdown"
                        style="background: rgba(18, 22, 31, 0.98); border: 1px solid rgba(255,255,255,0.06) !important; backdrop-filter: blur(8px);">
                        <li>
                            <a class="dropdown-item rounded-2 py-2 fs-7 fw-medium" href="<?= $dashboardLink ?>">
                                <iconify-icon icon="ph:layout-bold" class="align-middle me-2"></iconify-icon>
                                <?= $dashboardLabel ?>
                            </a>
                        </li>
                        <?php if ($userRole === 'buyer'): ?>
                            <li>
                                <a class="dropdown-item rounded-2 py-2 fs-7 fw-medium" href="index.php?page=my_tickets">
                                    <iconify-icon icon="ph:ticket-bold" class="align-middle me-2"></iconify-icon> Tiket Saya
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item rounded-2 py-2 fs-7 fw-medium" href="index.php?page=buyer_wallet">
                                    <iconify-icon icon="ph:wallet-bold" class="align-middle me-2"></iconify-icon> Wallet &
                                    Escrow
                                </a>
                            </li>
                        <?php endif; ?>
                        <li>
                            <hr class="dropdown-divider bg-secondary">
                        </li>
                        <li>
                            <a class="dropdown-item rounded-2 py-2 fs-7 fw-semibold text-danger"
                                href="index.php?sg_action=logout">
                                <iconify-icon icon="ph:sign-out-bold" class="align-middle me-2"></iconify-icon> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            <?php else: ?>
                <a href="index.php?page=login" class="text-white text-decoration-none fw-semibold hover-neon"
                    style="font-size: 0.85rem;">Login</a>
                <a href="index.php?page=signup" class="btn btn-safegate-neon rounded-pill fw-bold"
                    style="padding: 0.5rem 1.5rem; font-size: 0.85rem;">
                    Sign Up
                </a>
            <?php endif; ?>
        </div>

        <!-- Collapsible Mobile Navigation (Mobile Only) -->
        <div class="collapse w-100 d-md-none" id="mobileNavbar">
            <div class="d-flex flex-column gap-3 py-3 border-top mt-3"
                style="border-color: rgba(255,255,255,0.08) !important;">
                <!-- Links -->
                <a href="index.php?page=home"
                    class="py-2 text-decoration-none hover-neon <?= ($current_page === 'home') ? 'text-safegate-neon fw-bold' : 'text-safegate-text-sec' ?>"
                    style="font-size: 0.9rem;">
                    Events
                </a>
                <a href="index.php?page=penjualan"
                    class="py-2 text-decoration-none hover-neon <?= ($current_page === 'penjualan') ? 'text-safegate-neon fw-bold' : 'text-safegate-text-sec' ?>"
                    style="font-size: 0.9rem;">
                    Penjualan
                </a>
                <a href="index.php?page=cara_kerja"
                    class="py-2 text-decoration-none hover-neon <?= ($current_page === 'cara_kerja') ? 'text-safegate-neon fw-bold' : 'text-safegate-text-sec' ?>"
                    style="font-size: 0.9rem;">
                    Cara Kerja
                </a>

                <!-- Mobile Actions -->
                <div class="d-flex flex-column gap-3 pt-3 border-top"
                    style="border-color: rgba(255,255,255,0.05) !important;">
                    <div class="d-flex align-items-center gap-2 text-safegate-text-sec"
                        style="font-size: 0.8rem; font-weight: bold; letter-spacing: 0.05em;">
                        <iconify-icon icon="ph:shield-check-fill" class="text-safegate-neon"
                            style="font-size: 16px;"></iconify-icon> SECURED TRANSACTION
                    </div>
                    <?php if ($userId): ?>
                        <div class="d-flex align-items-center gap-3 py-2 border-bottom border-top py-3"
                            style="border-color: rgba(255,255,255,0.05) !important;">
                            <?php if (!empty($dbUser['profile_photo_path']) && $dbUser['profile_photo_path'] !== 'pending-upload'): ?>
                                <img src="<?= sg_h($dbUser['profile_photo_path']) ?>" alt="Avatar" class="rounded-circle"
                                    style="width: 36px; height: 36px; object-fit: cover; border: 1.5px solid var(--safegate-neon);">
                            <?php else: ?>
                                <div class="rounded-circle bg-safegate-neon text-black d-flex align-items-center justify-content-center fw-bold"
                                    style="width: 36px; height: 36px; font-size: 0.9rem; box-shadow: 0 0 10px rgba(217, 255, 0, 0.2);">
                                    <?= $initials ?>
                                </div>
                            <?php endif; ?>
                            <div>
                                <span
                                    class="d-block fw-semibold text-white fs-6"><?= sg_h($dbUser['full_name'] ?? 'User') ?></span>
                                <span class="d-block text-safegate-text-sec fs-7 text-uppercase"
                                    style="font-size: 0.65rem; letter-spacing: 0.05em;"><?= sg_h($userRole) ?></span>
                            </div>
                        </div>
                        <a href="<?= $dashboardLink ?>"
                            class="py-2 text-decoration-none text-white hover-neon fs-7 fw-medium">
                            <iconify-icon icon="ph:layout-bold" class="align-middle me-2"></iconify-icon>
                            <?= $dashboardLabel ?>
                        </a>
                        <?php if ($userRole === 'buyer'): ?>
                            <a href="index.php?page=my_tickets"
                                class="py-2 text-decoration-none text-white hover-neon fs-7 fw-medium">
                                <iconify-icon icon="ph:ticket-bold" class="align-middle me-2"></iconify-icon> Tiket Saya
                            </a>
                            <a href="index.php?page=buyer_wallet"
                                class="py-2 text-decoration-none text-white hover-neon fs-7 fw-medium">
                                <iconify-icon icon="ph:wallet-bold" class="align-middle me-2"></iconify-icon> Wallet & Escrow
                            </a>
                        <?php endif; ?>
                        <a href="index.php?sg_action=logout"
                            class="py-2 text-decoration-none text-danger hover-neon fs-7 fw-semibold mt-2">
                            <iconify-icon icon="ph:sign-out-bold" class="align-middle me-2"></iconify-icon> Logout
                        </a>
                    <?php else: ?>
                        <a href="index.php?page=login"
                            class="btn btn-outline-safegate-neon rounded-pill fw-bold py-2 w-100 text-center"
                            style="font-size: 0.85rem;">Login</a>
                        <a href="index.php?page=signup"
                            class="btn btn-safegate-neon rounded-pill fw-bold py-2 w-100 text-center"
                            style="font-size: 0.85rem;">Sign Up</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</header>