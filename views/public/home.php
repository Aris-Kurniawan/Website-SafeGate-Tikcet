<?php
$page_title = "SafeGate - Home";
ob_start();
?>

<!-- Hero Section -->
<section class="hero-section">

    <div class="hero-left">

        <span class="institutional-badge">
            ● INSTITUTIONAL SECURITY LAYER
        </span>

        <h1 class="hero-title">
            <span class="white">SafeGate</span><br>

            <span class="neon">
                Harga Terjamin.
            </span><br>

            <span class="white">
                Tanpa Penipuan.
            </span>
        </h1>

        <p class="hero-description">
            Dana terjamin di sistem Escrow hingga proses transaksi selesai.
            Hanya untuk penjual terverifikasi.
            Nikmati pasar sekunder dengan keamanan standar institusi.
        </p>

        <!-- SEARCH -->

        <div class="search-container">

            <div class="search-item">
                <i class="ph ph-magnifying-glass"></i>
                <input type="text" placeholder="Event or Artist">
            </div>

            <div class="search-item">
                <i class="ph ph-calendar"></i>
                <input type="text" placeholder="Tanggal">
            </div>

            <div class="search-item">
                <i class="ph ph-map-pin"></i>
                <input type="text" placeholder="Tempat">
            </div>

            <button class="search-btn">
                SEARCH
            </button>

        </div>

    </div>

    <!-- RIGHT -->

    <div class="hero-right">

        <img src="assets/images/hero.jpg" alt="Hero">

        <div class="verification-box">

            <h2>99.8%</h2>
            <span>VERIFIKASI SUKSES</span>

        </div>

    </div>

</section>

<!-- Marketplace Section -->
<section class="border-top border-secondary mt-5 py-5" style="background: rgba(18, 22, 31, 0.3);">
    <div class="container-fluid mx-auto" style="max-width: 1280px; padding-left: 2rem; padding-right: 2rem;">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-5 gap-4">
            <div>
                <p class="text-safegate-neon fw-bold text-uppercase mb-2"
                    style="font-size: 0.65rem; letter-spacing: 0.1em;">Marketplace</p>
                <h2 class="fs-2 fw-medium text-white mb-0">List Rekomendasi</h2>
            </div>
            <a href="index.php?page=penjualan"
                class="flex items-center gap-2 border border-gray-700 hover:border-safegate-neon text-xs font-bold text-white hover:text-safegate-neon px-6 py-2.5 rounded-full transition-colors group mt-4 md:mt-0">
                VIEW ALL EVENTS <i
                    class="ph-bold ph-arrow-right text-safegate-neon group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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
                $image = $ticket['image'];
                $title = $ticket['title'];
                $date = $ticket['date'];
                $price = $ticket['price'];
                $originalPrice = $ticket['originalPrice'];
                include '../../components/ticket_card.php';
            }
            ?>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/public_layout.php';
?>