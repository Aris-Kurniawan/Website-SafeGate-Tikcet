<?php
require_once __DIR__ . '/../../core/safegate_repository.php';

$page_title = "SafeGate - Home";
$tickets = array_slice(sg_get_marketplace_listings(), 0, 3);
ob_start();
?>

<style>
/* Date Picker Reset */
.sg-home-search input[type="date"]::-webkit-calendar-picker-indicator {
    opacity: 0;
    display: none;
}

.sg-home-search input[type="date"] {
    appearance: none;
    -webkit-appearance: none;
}

/* Responsive Hero Section */
.sg-hero-section {
    max-width: 1200px; 
    padding-left: 1.5rem; 
    padding-right: 1.5rem; 
    margin-top: 3rem; 
    margin-bottom: 3rem;
}

/* Responsive Title */
.sg-hero-title {
    font-size: clamp(2rem, 5vw, 4.2rem) !important;
    line-height: 1.15 !important;
}

/* Responsive Search Form styling */
.sg-home-search {
    background: rgba(18, 22, 31, 0.72);
    border: 1px solid rgba(255, 255, 255, 0.08);
    max-width: 560px;
    min-height: 56px;
    padding: 0.35rem;
    display: flex;
    align-items: center;
    border-radius: 50rem; /* pill shape on desktop and mobile */
    gap: 0;
}

.sg-search-col-q {
    flex: 1 1 30%;
    min-width: 0;
}

.sg-search-col-date {
    flex: 1 1 32%;
    min-width: 0;
    border-left: 1px solid rgba(255,255,255,0.08);
}

.sg-search-col-loc {
    flex: 1 1 24%;
    min-width: 0;
    border-left: 1px solid rgba(255,255,255,0.08);
}

.sg-search-col-btn {
    flex: 0 0 auto;
    min-width: 130px;
}

/* Floating badge responsive positions */
.sg-hero-badge {
    bottom: 3rem; 
    left: 0; 
    margin-left: -2rem;
}

/* Responsive adjustments */
@media (max-width: 991.98px) {
    .sg-hero-badge {
        left: 50%;
        transform: translateX(-50%);
        margin-left: 0;
        bottom: -0.75rem;
        width: 90%;
        text-align: center;
        padding: 0.4rem 0.6rem !important;
    }

    .sg-hero-badge .fs-1 {
        font-size: 1.2rem !important;
    }
    
    .sg-hero-badge div {
        font-size: 0.45rem !important;
    }
}

@media (max-width: 767.98px) {
    .sg-hero-badge-pill {
        font-size: 0.5rem !important;
        padding: 0.25rem 0.5rem !important;
        margin-bottom: 0.75rem !important;
    }

    .sg-hero-section {
        margin-top: 1.5rem;
        margin-bottom: 2rem;
        padding-top: 1rem !important;
        padding-bottom: 2rem !important; /* normal padding since search bar is in normal flow */
    }

    .sg-hero-title {
        font-size: clamp(1.45rem, 6.5vw, 2.2rem) !important; /* Slightly enlarged and fully responsive */
        line-height: 1.2 !important;
    }

    .safegate-logo-box {
        width: 24px !important;
        height: 24px !important;
        border-radius: 6px !important;
        box-shadow: 0 0 10px rgba(217, 255, 0, 0.4) !important;
    }

    .safegate-logo-box svg {
        width: 14px !important;
        height: 14px !important;
        stroke-width: 3.5 !important;
    }

    .sg-hero-title .d-flex {
        gap: 0.5rem !important;
    }

    .sg-home-search-wrapper {
        margin-top: 2.5rem !important; /* pushes it below the columns in normal flow */
        width: 100%;
        display: block;
    }

    .sg-home-search {
        min-height: 44px;
        padding: 0.2rem;
        width: 100%;
        max-width: 100%;
    }

    .sg-home-search input {
        font-size: 0.6rem !important;
    }

    .sg-home-search iconify-icon {
        font-size: 0.8rem !important;
    }

    .sg-search-col-q,
    .sg-search-col-date,
    .sg-search-col-loc {
        flex: 1 1 30%;
        padding-left: 0.4rem !important;
        padding-right: 0.2rem !important;
        gap: 0.2rem !important;
    }

    .sg-search-col-btn {
        flex: 0 0 auto;
        min-width: 70px;
    }

    .sg-search-col-btn button {
        font-size: 0.6rem !important;
        padding: 0.4rem 0.8rem !important;
    }
}
</style>

<!-- Hero Section -->
<section class="container-fluid mx-auto py-5 position-relative overflow-hidden sg-hero-section">
    <div class="row align-items-center position-relative z-1" style="row-gap: 2rem;">
        <!-- Left Content -->
        <div class="col-7 col-lg-6 d-flex flex-column align-items-start">
            <div class="d-flex align-items-center gap-2 rounded-pill mb-4 px-3 py-2 sg-hero-badge-pill"
                style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08); font-size: 0.6rem; font-weight: bold; text-transform: uppercase; letter-spacing: 0.1em;">
                <span class="bg-safegate-neon rounded-circle" style="width: 6px; height: 6px;"></span>
                <span class="text-safegate-neon">Institutional Security Layer</span>
            </div>

            <h1 class="display-3 fw-bold text-white mb-4 sg-hero-title" style="line-height: 1.1; letter-spacing: -0.02em;">
                <span class="d-flex align-items-center gap-3 mb-2">
                    <span class="safegate-logo-box mt-2" style="width: 44px; height: 44px; border-radius: 12px; box-shadow: 0 0 24px rgba(217, 255, 0, 0.4);">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#090B10" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round" style="width: 24px; height: 24px;">
                            <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                            <polyline points="17 6 23 6 23 12"></polyline>
                        </svg>
                    </span>
                    SafeGate
                </span>
                <span class="text-safegate-neon fst-italic pe-2">Harga Terjamin.</span><br>
                Tanpa Penipuan.
            </h1>

            <p class="text-safegate-text-sec mb-5 d-none d-md-block" style="font-size: 0.95rem; line-height: 1.6; max-width: 28rem;">
                Dana terjamin di sistem Escrow hingga proses transaksi selesai. Hanya untuk penjual terverifikasi.
                Nikmati pasar sekunder dengan keamanan standar institusi.
            </p>

            <!-- Search Bar Wrapper -->
            <div class="sg-home-search-wrapper w-100 d-none d-md-block">
                <form action="index.php" method="get" class="w-100 shadow-lg sg-home-search">
                    <input type="hidden" name="page" value="penjualan">
                    <div class="d-flex align-items-center gap-2 px-3 py-1 sg-search-col-q">
                        <iconify-icon icon="ph:magnifying-glass" class="text-safegate-neon" style="font-size: 1rem;"></iconify-icon>
                        <input type="text" name="q" placeholder="Event or Artist"
                            class="bg-transparent border-0 text-white w-100 form-control shadow-none p-0"
                            style="font-size: 0.74rem; min-width: 0;">
                    </div>
                    <div class="d-flex align-items-center gap-2 px-3 py-1 sg-search-col-date">
                        <iconify-icon icon="ph:calendar-blank" class="text-safegate-text-sec" style="font-size: 1rem;"></iconify-icon>
                        <input type="text" name="date" placeholder="Tanggal"
                            class="bg-transparent border-0 text-white w-100 form-control shadow-none p-0"
                            style="font-size: 0.74rem; min-width: 0;">
                    </div>
                    <div class="d-flex align-items-center gap-2 px-3 py-1 sg-search-col-loc">
                        <iconify-icon icon="ph:map-pin" class="text-safegate-text-sec" style="font-size: 1rem;"></iconify-icon>
                        <input type="text" name="location" placeholder="Tempat"
                            class="bg-transparent border-0 text-white w-100 form-control shadow-none p-0"
                            style="font-size: 0.74rem; min-width: 0;">
                    </div>
                    <div class="sg-search-col-btn">
                        <button type="submit" class="btn btn-safegate-neon rounded-pill fw-bold w-100"
                            style="font-size: 0.74rem; padding: 0.75rem 1.8rem; min-height: 44px; display: flex; align-items: center; justify-content: center;">
                            SEARCH
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Content / Image -->
        <div class="col-5 col-lg-6 position-relative d-flex justify-content-lg-end justify-content-center mt-0">
            <!-- Glow effect behind image -->
            <div class="position-absolute bg-safegate-neon rounded-circle"
                style="opacity: 0.15; filter: blur(120px); top: 10%; left: 10%; right: 10%; bottom: 10%; z-index: -1;">
            </div>

            <div class="position-relative"
                style="width: 100%; max-width: 500px; aspect-ratio: 1; border-radius: 2rem; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.4); border: 1px solid rgba(255, 255, 255, 0.05);">
                <img src="https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&q=80&w=800"
                    alt="Dashboard interaction" class="w-100 h-100 object-fit-cover">
                <!-- Overlay Gradient -->
                <div class="position-absolute top-0 start-0 w-100 h-100"
                    style="background: linear-gradient(to top, rgba(9, 11, 16, 0.9) 0%, rgba(9, 11, 16, 0.2) 50%, transparent 100%);">
                </div>
            </div>

            <!-- Floating Badge -->
            <div class="position-absolute bg-safegate-neon text-black rounded-4 shadow sg-hero-badge"
                style="padding: 1.25rem 1.5rem; z-index: 2; box-shadow: 0 10px 40px rgba(217, 255, 0, 0.25);">
                <div class="fs-1 fw-bold mb-0" style="font-weight: 900; letter-spacing: -0.05em;">99.8%</div>
                <div class="fw-bold text-uppercase mt-1" style="font-size: 0.55rem; letter-spacing: 0.15em;">Verifikasi Sukses</div>
            </div>
        </div>

        <!-- Search Bar Mobile Row -->
        <div class="col-12 sg-home-search-wrapper d-block d-md-none">
            <form action="index.php" method="get" class="w-100 shadow-lg sg-home-search">
                <input type="hidden" name="page" value="penjualan">
                <div class="d-flex align-items-center gap-2 px-3 py-1 sg-search-col-q">
                    <iconify-icon icon="ph:magnifying-glass" class="text-safegate-neon" style="font-size: 1rem;"></iconify-icon>
                    <input type="text" name="q" placeholder="Event or Artist"
                        class="bg-transparent border-0 text-white w-100 form-control shadow-none p-0"
                        style="font-size: 0.74rem; min-width: 0;">
                </div>
                <div class="d-flex align-items-center gap-2 px-3 py-1 sg-search-col-date">
                    <iconify-icon icon="ph:calendar-blank" class="text-safegate-text-sec" style="font-size: 1rem;"></iconify-icon>
                    <input type="text" name="date" placeholder="Tanggal"
                        class="bg-transparent border-0 text-white w-100 form-control shadow-none p-0"
                        style="font-size: 0.74rem; min-width: 0;">
                </div>
                <div class="d-flex align-items-center gap-2 px-3 py-1 sg-search-col-loc">
                    <iconify-icon icon="ph:map-pin" class="text-safegate-text-sec" style="font-size: 1rem;"></iconify-icon>
                    <input type="text" name="location" placeholder="Tempat"
                        class="bg-transparent border-0 text-white w-100 form-control shadow-none p-0"
                        style="font-size: 0.74rem; min-width: 0;">
                </div>
                <div class="sg-search-col-btn">
                    <button type="submit" class="btn btn-safegate-neon rounded-pill fw-bold w-100"
                        style="font-size: 0.74rem; padding: 0.75rem 1.8rem; min-height: 44px; display: flex; align-items: center; justify-content: center;">
                        SEARCH
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Marketplace Section -->
<section class="mt-5 py-5" style="background: rgba(18, 22, 31, 0.3); border-top: 1px solid rgba(255,255,255,0.03);">
    <div class="container-fluid mx-auto" style="max-width: 1200px; padding-left: 1.5rem; padding-right: 1.5rem;">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-5 gap-4">
            <div>
                <p class="text-safegate-neon fw-bold text-uppercase mb-2"
                    style="font-size: 0.65rem; letter-spacing: 0.15em;">Marketplace</p>
                <h2 class="display-6 fw-normal text-white mb-0" style="letter-spacing: -0.02em;">List Rekomendasi</h2>
            </div>
            <a href="index.php?page=penjualan" class="btn rounded-pill fw-bold d-flex align-items-center gap-2"
                style="background: transparent; border: 1px solid rgba(255,255,255,0.1); color: var(--safegate-neon); font-size: 0.75rem; padding: 0.6rem 1.5rem;">
                VIEW ALL EVENTS <iconify-icon icon="ph:arrow-right-bold"></iconify-icon>
            </a>
        </div>

        <div class="row g-4">
            <?php
            if (empty($tickets)) {
                echo '<div class="col-12 text-center py-5"><p class="text-safegate-text-sec">Belum ada listing tiket yang tersedia di database saat ini.</p></div>';
            }

            foreach ($tickets as $ticket) {
                echo '<div class="col-12 col-md-6 col-lg-4">';
                $image = $ticket['image'];
                $title = $ticket['title'];
                $date = $ticket['date'];
                $price = $ticket['price'];
                $originalPrice = $ticket['originalPrice'];
                $listingId = $ticket['id'] ?? '';
                $auctionEndAt = $ticket['auctionEndAt'] ?? null;
                include __DIR__ . '/../../components/ticket_card.php';
                echo '</div>';
            }
            ?>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/public_layout.php';
?>
