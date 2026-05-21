<?php
$page_title = "SafeGate - Home";
ob_start();
?>

<!-- Hero Section -->
<section class="container-fluid mx-auto py-5 position-relative overflow-hidden"
    style="max-width: 1200px; padding-left: 1.5rem; padding-right: 1.5rem; margin-top: 3rem; margin-bottom: 3rem;">
    <div class="row align-items-center position-relative z-1" style="row-gap: 4rem;">
        <!-- Left Content -->
        <div class="col-12 col-lg-6 d-flex flex-column align-items-start">
            <div class="d-flex align-items-center gap-2 rounded-pill mb-4 px-3 py-2"
                style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08); font-size: 0.6rem; font-weight: bold; text-transform: uppercase; letter-spacing: 0.1em;">
                <span class="bg-safegate-neon rounded-circle" style="width: 6px; height: 6px;"></span>
                <span class="text-safegate-neon">Institutional Security Layer</span>
            </div>

            <h1 class="display-3 fw-bold text-white mb-4" style="line-height: 1.1; letter-spacing: -0.02em;">
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

            <p class="text-safegate-text-sec mb-5" style="font-size: 0.95rem; line-height: 1.6; max-width: 28rem;">
                Dana terjamin di sistem Escrow hingga proses transaksi selesai. Hanya untuk penjual terverifikasi.
                Nikmati pasar sekunder dengan keamanan standar institusi.
            </p>

            <!-- Search Bar -->
            <div class="w-100 rounded-pill p-2 d-flex flex-column flex-md-row align-items-center gap-2 shadow-lg"
                style="background: rgba(18, 22, 31, 0.6); border: 1px solid rgba(255, 255, 255, 0.05);">
                <div class="flex-grow-1 d-flex align-items-center gap-3 px-3 w-100 py-2 py-md-1">
                    <iconify-icon icon="ph:magnifying-glass" class="text-safegate-neon fs-5"></iconify-icon>
                    <input type="text" placeholder="Event or Artist"
                        class="bg-transparent border-0 text-white w-100 form-control shadow-none p-0"
                        style="font-size: 0.85rem;">
                </div>
                <div class="flex-grow-1 d-flex align-items-center gap-3 px-3 w-100 search-divider py-2 py-md-1"
                    style="border-left: 1px solid rgba(255,255,255,0.05);">
                    <iconify-icon icon="ph:calendar-blank" class="text-safegate-text-sec fs-5"></iconify-icon>
                    <input type="text" placeholder="Tanggal"
                        class="bg-transparent border-0 text-white w-100 form-control shadow-none p-0"
                        style="font-size: 0.85rem;">
                </div>
                <div class="flex-grow-1 d-flex align-items-center gap-3 px-3 w-100 py-2 py-md-1">
                    <iconify-icon icon="ph:map-pin" class="text-safegate-text-sec fs-5"></iconify-icon>
                    <input type="text" placeholder="Tempat"
                        class="bg-transparent border-0 text-white w-100 form-control shadow-none p-0"
                        style="font-size: 0.85rem;">
                </div>
                <button class="btn btn-safegate-neon rounded-pill fw-bold w-100 w-md-auto mt-2 mt-md-0"
                    style="font-size: 0.85rem; padding: 0.8rem 2rem;">
                    SEARCH
                </button>
            </div>
        </div>

        <!-- Right Content / Image -->
        <div
            class="col-12 col-lg-6 position-relative d-flex justify-content-lg-end justify-content-center mt-5 mt-lg-0">
            <!-- Glow effect behind image -->
            <div class="position-absolute bg-safegate-neon rounded-circle"
                style="opacity: 0.15; filter: blur(120px); top: 10%; left: 10%; right: 10%; bottom: 10%; z-index: -1;">
            </div>

            <div class="position-relative"
                style="width: 100%; max-width: 500px; aspect-ratio: 1; border-radius: 2rem; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.4); border: 1px solid rgba(255,255,255,0.05);">
                <img src="https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&q=80&w=800"
                    alt="Dashboard interaction" class="w-100 h-100 object-fit-cover">
                <!-- Overlay Gradient -->
                <div class="position-absolute top-0 start-0 w-100 h-100"
                    style="background: linear-gradient(to top, rgba(9, 11, 16, 0.9) 0%, rgba(9, 11, 16, 0.2) 50%, transparent 100%);">
                </div>
            </div>

            <!-- Floating Badge -->
            <div class="position-absolute bg-safegate-neon text-black rounded-4 shadow"
                style="bottom: 3rem; left: 0; padding: 1.25rem 1.5rem; z-index: 2; box-shadow: 0 10px 40px rgba(217,255,0,0.25); margin-left: -2rem;">
                <div class="fs-1 fw-bold mb-0" style="font-weight: 900; letter-spacing: -0.05em;">99.8%</div>
                <div class="fw-bold text-uppercase mt-1" style="font-size: 0.55rem; letter-spacing: 0.15em;">Verifikasi
                    Sukses</div>
            </div>
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
            // Sample Data Array
            $tickets = [
                [
                    "image" => "https://images.unsplash.com/photo-1459749411175-04bf5292ceea?auto=format&fit=crop&q=80&w=800",
                    "title" => "Tour Konser Senior",
                    "date" => "July 24, 2024 • Madison Square Garden",
                    "price" => "150.000",
                    "originalPrice" => "200.000"
                ],
                [
                    "image" => "https://images.unsplash.com/photo-1504450758481-7338eba7524a?auto=format&fit=crop&q=80&w=800",
                    "title" => "Finals NBA",
                    "date" => "August 12, 2024 • Crypto.com Arena",
                    "price" => "100.000",
                    "originalPrice" => "150.000"
                ],
                [
                    "image" => "https://images.unsplash.com/photo-1533174000222-1d11bb74ca34?auto=format&fit=crop&q=80&w=800",
                    "title" => "Konser Coldplay",
                    "date" => "Sept 05, 2024 • Hyde Park",
                    "price" => "300.000",
                    "originalPrice" => "350.000"
                ]
            ];

            foreach ($tickets as $ticket) {
                echo '<div class="col-12 col-md-6 col-lg-4">';
                $image = $ticket['image'];
                $title = $ticket['title'];
                $date = $ticket['date'];
                $price = $ticket['price'];
                $originalPrice = $ticket['originalPrice'];
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