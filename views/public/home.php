<?php
$page_title = "SafeGate - Home";
ob_start();
?>

<!-- Hero Section -->
<section class="container-fluid mx-auto py-5 position-relative overflow-hidden" style="max-width: 1280px; padding-left: 2rem; padding-right: 2rem;">
    <div class="row align-items-center position-relative z-1" style="row-gap: 3rem;">
        <!-- Left Content -->
        <div class="col-12 col-lg-6 d-flex flex-column align-items-start pt-4">
            <div class="d-flex align-items-center gap-2 border border-secondary rounded-pill mb-4 px-3 py-2" style="background: rgba(14, 18, 26, 0.4); font-size: 0.6rem; font-weight: bold; text-transform: uppercase; letter-spacing: 0.1em;">
                <span class="bg-safegate-neon rounded-circle" style="width: 6px; height: 6px;"></span>
                <span class="text-safegate-neon">Institutional Security Layer</span>
            </div>

            <h1 class="display-4 fw-bold text-white mb-4" style="line-height: 1.1; letter-spacing: -0.02em;">
                <span class="d-flex align-items-center gap-3 mb-2">
                    <span class="bg-safegate-neon rounded-circle mt-2" style="width: 16px; height: 16px;"></span> SafeGate
                </span>
                <span class="text-safegate-neon fst-italic pe-2">Harga Terjamin.</span><br>
                Tanpa Penipuan.
            </h1>

            <p class="text-safegate-text-sec mb-5" style="font-size: 0.95rem; line-height: 1.6; max-width: 28rem;">
                Dana terjamin di sistem Escrow hingga proses transaksi selesai. Hanya untuk penjual terverifikasi.
                Nikmati pasar sekunder dengan keamanan standar institusi.
            </p>

            <!-- Search Bar -->
            <div class="w-100 bg-safegate-surface border border-secondary rounded-pill p-2 d-flex flex-column flex-md-row align-items-center gap-2 shadow-lg">
                <div class="flex-grow-1 d-flex align-items-center gap-3 px-3 w-100 py-2 py-md-1">
                    <i class="ph ph-magnifying-glass text-safegate-neon fs-5"></i>
                    <input type="text" placeholder="Event or Artist" class="bg-transparent border-0 text-white w-100 form-control shadow-none p-0" style="font-size: 0.875rem;">
                </div>
                <div class="flex-grow-1 d-flex align-items-center gap-3 px-3 w-100 border-start border-end border-secondary py-2 py-md-1">
                    <i class="ph ph-calendar-blank text-safegate-text-sec fs-5"></i>
                    <input type="text" placeholder="Tanggal" class="bg-transparent border-0 text-white w-100 form-control shadow-none p-0" style="font-size: 0.875rem;">
                </div>
                <div class="flex-grow-1 d-flex align-items-center gap-3 px-3 w-100 py-2 py-md-1">
                    <i class="ph ph-map-pin text-safegate-text-sec fs-5"></i>
                    <input type="text" placeholder="Tempat" class="bg-transparent border-0 text-white w-100 form-control shadow-none p-0" style="font-size: 0.875rem;">
                </div>
                <button class="btn btn-safegate-neon rounded-pill fw-bold w-100 w-md-auto mt-2 mt-md-0" style="font-size: 0.875rem; padding: 0.8rem 2rem;">
                    SEARCH
                </button>
            </div>
        </div>

        <!-- Right Content / Image -->
        <div class="col-12 col-lg-6 position-relative d-flex justify-content-lg-end justify-content-center mt-5 mt-lg-0">
            <!-- Glow effect behind image -->
            <div class="position-absolute bg-safegate-neon rounded-circle" style="opacity: 0.1; filter: blur(100px); top: 10%; left: 10%; right: 10%; bottom: 10%; transform: scale(0.75) translate(40px, 40px); z-index: -1;"></div>

            <div class="position-relative border border-secondary" style="width: 100%; max-width: 480px; aspect-ratio: 1; border-radius: 2rem; overflow: hidden; background: rgba(255,255,255,0.05);">
                <img src="https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&q=80&w=800" alt="Dashboard interaction" class="w-100 h-100 object-fit-cover">
                <!-- Overlay Gradient -->
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(to top, rgba(9, 11, 16, 0.8), transparent, transparent);"></div>
            </div>

            <!-- Floating Badge -->
            <div class="position-absolute bg-safegate-neon text-black rounded-4 shadow" style="bottom: 4rem; left: 0; padding: 1.25rem; z-index: 2; box-shadow: 0 10px 40px rgba(217,255,0,0.2); margin-left: -1rem;">
                <div class="fs-2 fw-bold mb-1" style="font-weight: 900;">99.8%</div>
                <div class="fw-bold text-uppercase" style="font-size: 0.6rem; letter-spacing: 0.1em;">Verifikasi Sukses</div>
            </div>
        </div>
    </div>
</section>

<!-- Marketplace Section -->
<section class="border-top border-secondary mt-5 py-5" style="background: rgba(18, 22, 31, 0.3);">
    <div class="container-fluid mx-auto" style="max-width: 1280px; padding-left: 2rem; padding-right: 2rem;">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-5 gap-4">
            <div>
                <p class="text-safegate-neon fw-bold text-uppercase mb-2" style="font-size: 0.65rem; letter-spacing: 0.1em;">Marketplace</p>
                <h2 class="fs-2 fw-medium text-white mb-0">List Rekomendasi</h2>
            </div>
            <a href="index.php?page=penjualan" class="btn btn-outline-secondary text-white rounded-pill fw-bold d-flex align-items-center gap-2 border-secondary" style="font-size: 0.75rem; padding: 0.6rem 1.5rem;">
                VIEW ALL EVENTS <i class="ph-bold ph-arrow-right text-safegate-neon"></i>
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