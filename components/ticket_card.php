<?php
// Default values if variables are not passed
$image = isset($image) ? $image : 'https://images.unsplash.com/photo-1540039155733-d7696f4ad9b2?auto=format&fit=crop&q=80&w=800';
$title = isset($title) ? $title : 'Event Title';
$date = isset($date) ? $date : 'Date • Location';
$price = isset($price) ? $price : '100.000';
$originalPrice = isset($originalPrice) ? $originalPrice : '150.000';
?>
<div class="card bg-safegate-surface border-secondary rounded-4 overflow-hidden h-100 card-hover text-white">
    <!-- Image Wrapper -->
    <div class="position-relative" style="height: 12rem; overflow: hidden;">
        <img src="<?= $image ?>" alt="<?= $title ?>" class="w-100 h-100 object-fit-cover card-img-zoom">
        <!-- Badge Layer -->
        <div class="position-absolute bg-safegate-neon text-black fw-bold rounded shadow-sm d-flex align-items-center gap-1" style="top: 1rem; left: 1rem; padding: 0.25rem 0.5rem; font-size: 0.65rem;">
            <i class="ph-fill ph-check-circle"></i> VERIFIED
        </div>
    </div>
    
    <!-- Content Body -->
    <div class="card-body d-flex flex-column p-4">
        <h5 class="card-title fs-5 fw-medium mb-2"><?= $title ?></h5>
        <p class="card-text text-safegate-text-sec mb-4 d-flex align-items-center gap-2" style="font-size: 0.75rem;">
            <i class="ph ph-calendar-blank fs-6"></i> <?= $date ?>
        </p>
        
        <div class="d-flex gap-2 mb-4">
            <span class="badge border border-secondary text-light text-uppercase" style="background: rgba(142, 149, 163, 0.2); font-size: 0.6rem; letter-spacing: 0.05em; padding: 0.4em 0.8em;">KYC Secured</span>
            <span class="badge border border-secondary text-light text-uppercase" style="background: rgba(142, 149, 163, 0.2); font-size: 0.6rem; letter-spacing: 0.05em; padding: 0.4em 0.8em;">Escrow Active</span>
        </div>
        
        <!-- Footer / Price -->
        <div class="mt-auto d-flex align-items-end justify-content-between border-top border-secondary pt-3">
            <div>
                <p class="text-safegate-neon fw-bold text-uppercase mb-1" style="font-size: 0.6rem; letter-spacing: 0.05em;">Face Value Cap</p>
                <div class="d-flex align-items-baseline gap-2">
                    <span class="fs-5 fw-medium text-white">Rp.<?= $price ?></span>
                    <span class="text-decoration-line-through text-secondary" style="font-size: 0.75rem;">Rp.<?= $originalPrice ?></span>
                </div>
            </div>
            <button class="btn btn-outline-safegate-neon rounded-pill fw-semibold" style="font-size: 0.75rem; padding: 0.375rem 1.25rem;">
                BUY
            </button>
        </div>
    </div>
</div>
