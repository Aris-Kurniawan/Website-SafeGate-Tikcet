<?php
require_once __DIR__ . '/../../core/safegate_repository.php';

$page_title = 'Active Listings - SafeGate';
$dashboard_page = 'active_listings';
$seller_id = sg_current_user_id('seller');
$listings = sg_get_seller_listings($seller_id);
$flash = sg_flash();

ob_start();
?>

<section class="sg-vendor-page sg-active-page">
    <header class="sg-vendor-heading">
        <h1>Active Listings</h1>
        <p>Pantau tiket aktif, status lelang, dan performa listing Anda.</p>
    </header>

    <?php if ($flash): ?>
        <p class="sg-list-status <?= $flash['type'] === 'error' ? 'is-error' : '' ?>"><?= sg_h($flash['message']) ?></p>
    <?php endif; ?>

    <div class="sg-active-grid">
        <?php if (!$listings): ?>
            <article class="sg-panel sg-listing-card" style="grid-column: 1 / -1;">
                <div class="sg-listing-thumb"><iconify-icon icon="ph:ticket"></iconify-icon></div>
                <div>
                    <h2>Belum ada listing dari database</h2>
                    <p>Listing yang kamu buat akan muncul di sini setelah tersimpan.</p>
                </div>
                <strong><?= sg_rupiah(0) ?></strong>
                <span>Empty</span>
                <a href="index.php?page=sell_ticket" style="text-decoration:none;">Create Listing</a>
            </article>
        <?php endif; ?>
        <?php foreach ($listings as $listing): ?>
            <article class="sg-panel sg-listing-card">
                <div class="sg-listing-thumb"><iconify-icon icon="ph:ticket"></iconify-icon></div>
                <div>
                    <h2><?= sg_h($listing['title']) ?></h2>
                    <p><?= sg_h($listing['meta']) ?></p>
                </div>
                <strong><?= sg_h($listing['price']) ?></strong>
                <span><?= sg_h($listing['status']) ?></span>
                <?php if (!empty($listing['id']) && ($listing['status_raw'] ?? '') !== 'sold'): ?>
                    <form action="index.php?page=active_listings" method="post" style="display:flex; gap:8px; flex-wrap:wrap; justify-content:flex-end;">
                        <input type="hidden" name="sg_action" value="listing_status">
                        <input type="hidden" name="listing_id" value="<?= (int) $listing['id'] ?>">
                        <?php if (in_array(($listing['status_raw'] ?? ''), ['paused', 'pending_review'], true)): ?>
                            <button type="submit" name="listing_status" value="active">Activate</button>
                        <?php else: ?>
                            <button type="submit" name="listing_status" value="paused">Pause</button>
                        <?php endif; ?>
                        <button type="submit" name="listing_status" value="promoted">Promote</button>
                        <button type="submit" name="listing_status" value="cancelled">Cancel</button>
                    </form>
                <?php else: ?>
                    <a href="<?= !empty($listing['id']) ? 'index.php?page=detail_tiket&listing_id=' . urlencode($listing['id']) : 'index.php?page=sell_ticket' ?>" style="text-decoration:none;">Manage</a>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/dashboard_layout.php';
?>
