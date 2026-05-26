<?php
// Default values if variables are not passed
$image = isset($image) ? $image : 'https://images.unsplash.com/photo-1540039155733-d7696f4ad9b2?auto=format&fit=crop&q=80&w=800';
$title = isset($title) ? $title : 'Event Title';
$date = isset($date) ? $date : 'Date • Location';
$price = isset($price) ? $price : '100.000';
$originalPrice = isset($originalPrice) ? $originalPrice : '150.000';
$listingId = isset($listingId) ? $listingId : '';
$detailHref = $listingId
    ? 'index.php?page=detail_tiket&listing_id=' . urlencode($listingId)
    : 'index.php?page=detail_tiket&title=' . urlencode($title) . '&price=' . urlencode($price) . '&originalPrice=' . urlencode($originalPrice) . '&image=' . urlencode($image) . '&date=' . urlencode($date);
?>
<a href="<?= $detailHref ?>" class="text-decoration-none h-100 d-block">
    <div class="card bg-safegate-surface rounded-4 overflow-hidden h-100 card-hover text-white border-0"
        style="box-shadow: inset 0 0 0 1px rgba(255,255,255,0.05);">
        <!-- Image Wrapper -->
        <div class="position-relative" style="height: 12rem; overflow: hidden;">
            <img src="<?= $image ?>" alt="<?= $title ?>" class="w-100 h-100 object-fit-cover card-img-zoom">
            <!-- Badge Layer -->
            <div class="position-absolute bg-safegate-neon text-black fw-bold rounded shadow-sm d-flex align-items-center gap-1"
                style="top: 1rem; left: 1rem; padding: 0.2rem 0.5rem; font-size: 0.6rem; letter-spacing: 0.05em;">
                <iconify-icon icon="ph:check-circle-fill" style="font-size: 14px;"></iconify-icon> VERIFIED
            </div>
        </div>

        <!-- Content Body -->
        <div class="card-body d-flex flex-column p-4">
            <h5 class="card-title fs-4 fw-medium mb-2 letter-spacing-tight"><?= $title ?></h5>
            <p class="card-text text-safegate-text-sec mb-4 d-flex align-items-center gap-2" style="font-size: 0.75rem;">
                <iconify-icon icon="ph:calendar-blank" style="font-size: 14px;"></iconify-icon> <?= $date ?>
            </p>

            <div class="d-flex gap-2 mb-4">
                <span class="badge text-light text-uppercase rounded-pill fw-bold"
                    style="background: rgba(255, 255, 255, 0.05); font-size: 0.55rem; letter-spacing: 0.05em; padding: 0.5em 0.8em; box-shadow: inset 0 0 0 1px rgba(255,255,255,0.1);">KYC
                    SECURED</span>
                <span class="badge text-light text-uppercase rounded-pill fw-bold"
                    style="background: rgba(255, 255, 255, 0.05); font-size: 0.55rem; letter-spacing: 0.05em; padding: 0.5em 0.8em; box-shadow: inset 0 0 0 1px rgba(255,255,255,0.1);">ESCROW
                    ACTIVE</span>
            </div>

            <!-- Footer / Price -->
            <div class="mt-auto d-flex align-items-end justify-content-between pt-4"
                style="border-top: 1px solid rgba(255,255,255,0.05);">
                <div>
                    <p class="text-safegate-neon fw-bold text-uppercase mb-1"
                        style="font-size: 0.55rem; letter-spacing: 0.05em;">Face Value Cap</p>
                    <div class="d-flex align-items-baseline gap-2">
                        <span class="fs-4 fw-medium text-white">Rp.<?= $price ?></span>
                        <span class="text-decoration-line-through text-safegate-text-sec"
                            style="font-size: 0.75rem;">Rp.<?= $originalPrice ?></span>
                    </div>
                </div>
                <button class="btn btn-outline-safegate-neon rounded-pill fw-bold"
                    style="font-size: 0.75rem; padding: 0.4rem 1.25rem;">
                    BUY
                </button>
            </div>
        </div>
    </div>
</a>
