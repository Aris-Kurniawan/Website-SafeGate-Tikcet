<?php
$buyer_page = $buyer_page ?? '';
sg_ensure_user_profile_schema();
$user = !empty($_SESSION['user_id'])
    ? sg_fetch_one('SELECT full_name, email, profile_photo_path FROM users WHERE id = :id LIMIT 1', ['id' => (int) $_SESSION['user_id']])
    : null;
$buyer_name = $user['full_name'] ?? 'SafeGate User';
$initials = sg_user_initials($buyer_name, 'U');
$buyerPhoto = trim((string) ($user['profile_photo_path'] ?? ''));
$nav = [
    ['buyer_dashboard', 'Overview', 'ph:squares-four'],
    ['my_tickets', 'My Tickets', 'ph:ticket'],
    ['buyer_wallet', 'Wallet & Escrow', 'ph:wallet'],
    ['buyer_transactions', 'Transaction History', 'ph:clock-counter-clockwise'],
];
?>
<aside class="sg-sidebar sg-buyer-sidebar" aria-label="Buyer dashboard sidebar">
    <div class="sg-sidebar-header">
        <a class="sg-side-brand" href="index.php?page=home" aria-label="SafeGate home">
            <div class="sg-logo-icon">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18" stroke="#090B10" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"></polyline>
                    <polyline points="17 6 23 6 23 12" stroke="#090B10" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"></polyline>
                </svg>
            </div>
            <span>SafeGate</span>
        </a>
        <button class="sg-sidebar-toggle" aria-label="Toggle sidebar" onclick="toggleBuyerSidebar()">
            <iconify-icon icon="ph:list" id="buyer-sidebar-toggle-icon"></iconify-icon>
        </button>
    </div>

    <div class="sg-seller-card">
        <div class="sg-seller-avatar sg-buyer-avatar <?= $buyerPhoto !== '' ? 'has-photo' : '' ?>">
            <?php if ($buyerPhoto !== ''): ?>
                <img src="<?= sg_h($buyerPhoto) ?>" alt="">
            <?php else: ?>
                <?= sg_h($initials) ?>
            <?php endif; ?>
        </div>
        <div>
            <strong><?= sg_h($buyer_name) ?></strong>
            <span>KYC Active</span>
        </div>
    </div>

    <nav class="sg-sidebar-nav sg-buyer-nav">
        <?php foreach ($nav as [$page, $label, $icon]): ?>
            <a href="index.php?page=<?= sg_h($page) ?>" class="<?= $buyer_page === $page ? 'is-active' : '' ?>">
                <iconify-icon icon="<?= sg_h($icon) ?>"></iconify-icon>
                <span><?= sg_h($label) ?></span>
            </a>
        <?php endforeach; ?>
        <hr>
        <a href="#">
            <iconify-icon icon="ph:question"></iconify-icon>
            <span>Help Center</span>
        </a>
    </nav>

    <div class="sg-sidebar-footer">
        <a href="index.php?sg_action=logout">
            <iconify-icon icon="ph:sign-out"></iconify-icon>
            <span>Log Out</span>
        </a>
    </div>
</aside>

<script>
function toggleBuyerSidebar() {
    const frame = document.querySelector('.sg-buyer-frame');
    const icon = document.getElementById('buyer-sidebar-toggle-icon');
    if (!frame) return;

    frame.classList.toggle('sg-sidebar-collapsed');
    const isCollapsed = frame.classList.contains('sg-sidebar-collapsed');
    localStorage.setItem('sg-buyer-sidebar-collapsed', isCollapsed ? 'true' : 'false');

    if (icon) {
        icon.setAttribute('icon', isCollapsed ? 'ph:list-bold' : 'ph:list');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const isCollapsed = localStorage.getItem('sg-buyer-sidebar-collapsed') === 'true';
    const frame = document.querySelector('.sg-buyer-frame');
    const icon = document.getElementById('buyer-sidebar-toggle-icon');
    if (isCollapsed && frame) {
        frame.classList.add('sg-sidebar-collapsed');
    }
    if (isCollapsed && icon) {
        icon.setAttribute('icon', 'ph:list-bold');
    }
});
</script>
