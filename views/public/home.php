<?php
$page_title = "SafeGate - Home";
ob_start();
?>

<!-- Hero Section -->
<section class="container-fluid mx-auto py-5 position-relative overflow-hidden"
    style="max-width: 1280px; padding-left: 2rem; padding-right: 2rem;">
    <div class="row align-items-center position-relative z-1" style="row-gap: 3rem;">
        <!-- Left Content -->
        <div class="flex flex-col items-start pt-10">
            <div
                class="flex items-center gap-2 border border-gray-800/80 bg-gray-900/40 text-[9px] text-safegate-neon font-bold uppercase tracking-widest px-3 py-1.5 rounded-full mb-8">
                <span class="w-1.5 h-1.5 bg-safegate-neon rounded-full"></span>
                Institutional Security Layer
            </div>

            <h1 class="text-5xl md:text-6xl lg:text-[4rem] font-bold text-white leading-[1.1] mb-6 tracking-tight">
                <span class="flex items-center gap-3 mb-2">
                    <span class="w-4 h-4 bg-safegate-neon rounded-full mt-1"></span> SafeGate
                </span>
                <span class="text-safegate-neon fst-italic pe-2">Harga Terjamin.</span><br>
                Tanpa Penipuan.
            </h1>

            <p class="text-safegate-text-sec text-sm md:text-[15px] leading-relaxed max-w-md mb-12">
                Dana terjamin di sistem Escrow hingga proses transaksi selesai. Hanya untuk penjual terverifikasi.
                Nikmati pasar sekunder dengan keamanan standar institusi.
            </p>

            <!-- Search Bar -->
            <div
                class="w-full bg-safegate-surface border border-gray-800/50 rounded-full p-2 flex flex-col md:flex-row items-center gap-2 shadow-lg">
                <div class="flex-1 flex items-center gap-3 px-4 w-full md:border-r border-gray-800/50 py-2 md:py-1">
                    <i class="ph ph-magnifying-glass text-safegate-neon text-lg"></i>
                    <input type="text" placeholder="Event or Artist"
                        class="bg-transparent border-none text-sm text-white focus:outline-none w-full placeholder-safegate-text-sec">
                </div>
                <div class="flex-1 flex items-center gap-3 px-4 w-full md:border-r border-gray-800/50 py-2 md:py-1">
                    <i class="ph ph-calendar-blank text-safegate-text-sec text-lg"></i>
                    <input type="text" placeholder="Tanggal"
                        class="bg-transparent border-none text-sm text-white focus:outline-none w-full placeholder-safegate-text-sec">
                </div>
                <div class="flex-1 flex items-center gap-3 px-4 w-full py-2 md:py-1">
                    <i class="ph ph-map-pin text-safegate-text-sec text-lg"></i>
                    <input type="text" placeholder="Tempat"
                        class="bg-transparent border-none text-sm text-white focus:outline-none w-full placeholder-safegate-text-sec">
                </div>
                <button
                    class="bg-safegate-neon hover:bg-[#c2e600] text-black font-bold text-sm px-8 py-3.5 rounded-full w-full md:w-auto transition-all duration-300">
                    SEARCH
                </button>
            </div>
        </div>

        <!-- Right Content / Image -->
        <div
            class="col-12 col-lg-6 position-relative d-flex justify-content-lg-end justify-content-center mt-5 mt-lg-0">
            <!-- Glow effect behind image -->
            <div
                class="absolute inset-0 bg-safegate-neon/10 blur-[100px] rounded-full scale-75 translate-x-10 translate-y-10">
            </div>

            <div class="relative w-[480px] h-[480px] rounded-[32px] overflow-hidden border border-gray-800/30">
                <img src="https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&q=80&w=800"
                    alt="Dashboard interaction" class="w-full h-full object-cover">
                <!-- Overlay Gradient -->
                <div class="position-absolute top-0 start-0 w-100 h-100"
                    style="background: linear-gradient(to top, rgba(9, 11, 16, 0.8), transparent, transparent);"></div>
            </div>

            <!-- Floating Badge -->
            <div
                class="absolute bottom-16 -left-8 bg-safegate-neon text-black p-5 rounded-2xl shadow-[0_10px_40px_rgba(217,255,0,0.2)]">
                <div class="text-3xl font-black mb-1">99.8%</div>
                <div class="text-[9px] font-bold uppercase tracking-widest">Verifikasi Sukses</div>
            </div>
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
                include __DIR__ . '/../../components/ticket_card.php';
            }
            ?>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/public_layout.php';
?>