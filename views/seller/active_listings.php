<?php
$page_title = 'Active Listings - SafeGate';
$dashboard_page = 'active_listings';

ob_start();
?>

<section class="sg-vendor-page sg-active-page">
    <header class="sg-vendor-heading">
        <h1>Active Listings</h1>
        <p>Pantau tiket aktif, status lelang, dan performa listing Anda.</p>
    </header>

    <div class="sg-active-grid">
        <?php
        $listings = [
            ['title' => 'The Eras Tour - London', 'price' => 'Rp 1.500.000', 'status' => 'Auction Live', 'meta' => 'Wembley Stadium · 18 bids'],
            ['title' => 'Coldplay Music of the Spheres', 'price' => 'Rp 950.000', 'status' => 'Under Review', 'meta' => 'GBK Jakarta · Verification'],
            ['title' => 'Premier League: London Derby', 'price' => 'Rp 1.800.000', 'status' => 'Fixed Price', 'meta' => 'North Stand · 4 watchers'],
        ];
        foreach ($listings as $listing):
        ?>
            <article class="sg-panel sg-listing-card">
                <div class="sg-listing-thumb"><iconify-icon icon="ph:ticket"></iconify-icon></div>
                <div>
                    <h2><?= htmlspecialchars($listing['title']) ?></h2>
                    <p><?= htmlspecialchars($listing['meta']) ?></p>
                </div>
                <strong><?= htmlspecialchars($listing['price']) ?></strong>
                <span><?= htmlspecialchars($listing['status']) ?></span>
                <button type="button">Manage</button>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/dashboard_layout.php';
?>
