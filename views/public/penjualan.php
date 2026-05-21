<?php
// 1. Definisikan judul halaman
$page_title = "Marketplace Tiket - SafeGate";

// 2. Mulai menangkap output konten (Output Buffering)
ob_start();

// List of available tickets
$tickets = [
    [
        "title" => "Midnight Symphony Tour",
        "image" => "https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?auto=format&fit=crop&q=80&w=800",
        "date" => "October 24, 2024 • The Neon Citadel, LA",
        "price" => "180.000",
        "originalPrice" => "250.000"
    ],
    [
        "title" => "Tour Konser Senior",
        "image" => "https://images.unsplash.com/photo-1459749411175-04bf5292ceea?auto=format&fit=crop&q=80&w=800",
        "date" => "July 24, 2024 • Madison Square Garden",
        "price" => "150.000",
        "originalPrice" => "200.000"
    ],
    [
        "title" => "Finals NBA 2024",
        "image" => "https://images.unsplash.com/photo-1504450758481-7338eba7524a?auto=format&fit=crop&q=80&w=800",
        "date" => "August 12, 2024 • Crypto.com Arena",
        "price" => "100.000",
        "originalPrice" => "150.000"
    ],
    [
        "title" => "Konser Coldplay",
        "image" => "https://images.unsplash.com/photo-1533174000222-1d11bb74ca34?auto=format&fit=crop&q=80&w=800",
        "date" => "Sept 05, 2024 • Hyde Park",
        "price" => "300.000",
        "originalPrice" => "350.000"
    ],
    [
        "title" => "Tomorrowland Special",
        "image" => "https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&q=80&w=800",
        "date" => "July 18, 2024 • Boom, Belgium",
        "price" => "420.000",
        "originalPrice" => "500.000"
    ],
    [
        "title" => "Jazz Festival Jakarta",
        "image" => "https://images.unsplash.com/photo-1511192336575-5a79af67a629?auto=format&fit=crop&q=80&w=800",
        "date" => "June 29, 2024 • JIExpo, Jakarta",
        "price" => "120.000",
        "originalPrice" => "180.000"
    ]
];
?>

<!-- Marketplace Section -->
<section class="container mx-auto py-5" style="max-width: 1200px; padding-left: 1.5rem; padding-right: 1.5rem; margin-top: 4rem; margin-bottom: 5rem;">
    
    <!-- Title Area -->
    <div class="mb-5 text-center text-md-start">
        <p class="text-safegate-neon fw-bold text-uppercase mb-2 letter-spacing-wide" style="font-size: 0.75rem;">Secondary Market</p>
        <h1 class="display-4 fw-bold text-white mb-3 letter-spacing-tight">Penjualan Tiket Terverifikasi</h1>
        <p class="text-safegate-text-sec fs-6 mx-auto mx-md-0" style="max-width: 36rem; line-height: 1.6;">
            Temukan tiket konser, pertandingan olahraga, dan festival yang dijual secara aman oleh sesama penggemar. Harga dijamin adil di bawah batas atas (Face Value Cap).
        </p>
    </div>

    <!-- Interactive Filters & Search bar (High Fidelity Mockup) -->
    <div class="p-3 rounded-4 mb-5 sg-glass">
        <div class="row g-3 align-items-center">
            <!-- Search field -->
            <div class="col-12 col-md-5">
                <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-pill bg-dark bg-opacity-50 border border-secondary border-opacity-25">
                    <iconify-icon icon="ph:magnifying-glass" class="text-safegate-neon fs-5"></iconify-icon>
                    <input type="text" id="search-input" onkeyup="filterCards()" placeholder="Cari nama event..." class="bg-transparent border-0 text-white w-100 shadow-none p-0" style="font-size: 0.85rem;">
                </div>
            </div>
            
            <!-- Category dropdown -->
            <div class="col-6 col-md-3">
                <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-pill bg-dark bg-opacity-50 border border-secondary border-opacity-25">
                    <iconify-icon icon="ph:tag-bold" class="text-safegate-text-sec fs-5"></iconify-icon>
                    <select class="bg-transparent border-0 text-white w-100 shadow-none p-0" style="font-size: 0.85rem; cursor: pointer; outline: none;">
                        <option class="bg-safegate-surface" value="all">Semua Kategori</option>
                        <option class="bg-safegate-surface" value="concert">Konser Musik</option>
                        <option class="bg-safegate-surface" value="sports">Olahraga</option>
                        <option class="bg-safegate-surface" value="festival">Festival</option>
                    </select>
                </div>
            </div>

            <!-- Sort dropdown -->
            <div class="col-6 col-md-4">
                <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-pill bg-dark bg-opacity-50 border border-secondary border-opacity-25">
                    <iconify-icon icon="ph:sort-ascending-bold" class="text-safegate-text-sec fs-5"></iconify-icon>
                    <select class="bg-transparent border-0 text-white w-100 shadow-none p-0" style="font-size: 0.85rem; cursor: pointer; outline: none;">
                        <option class="bg-safegate-surface" value="featured">Rekomendasi Utama</option>
                        <option class="bg-safegate-surface" value="price-asc">Harga Terendah</option>
                        <option class="bg-safegate-surface" value="price-desc">Harga Tertinggi</option>
                        <option class="bg-safegate-surface" value="date">Tanggal Terdekat</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Ticket Cards Grid -->
    <div class="row g-4" id="ticket-grid">
        <?php foreach ($tickets as $index => $ticket): ?>
            <div class="col-12 col-md-6 col-lg-4 ticket-card-item" data-title="<?= strtolower($ticket['title']) ?>">
                <?php
                $image = $ticket['image'];
                $title = $ticket['title'];
                $date = $ticket['date'];
                $price = $ticket['price'];
                $originalPrice = $ticket['originalPrice'];
                include __DIR__ . '/../../components/ticket_card.php';
                ?>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Empty State -->
    <div id="empty-state" class="text-center py-5 d-none">
        <iconify-icon icon="ph:ticket-slash-bold" class="text-safegate-text-sec display-1 mb-4"></iconify-icon>
        <h3 class="h4 fw-bold text-white mb-2">Tiket Tidak Ditemukan</h3>
        <p class="text-safegate-text-sec">Coba cari dengan kata kunci lain.</p>
    </div>

</section>
<script>
    // Real-time client-side search filtering
    function filterCards() {
        const query = document.getElementById("search-input").value.toLowerCase();
        const cards = document.querySelectorAll(".ticket-card-item");
        let found = 0;
        
        cards.forEach(card => {
            const title = card.getAttribute("data-title");
            if (title.includes(query)) {
                card.style.display = "";
                found++;
            } else {
                card.style.display = "none";
            }
        });
        
        const emptyState = document.getElementById("empty-state");
        if (found === 0) {
            emptyState.classList.remove("d-none");
        } else {
            emptyState.classList.add("d-none");
        }
    }
</script>

<?php
// 3. Simpan konten ke dalam variabel
$content = ob_get_clean();

// 4. Panggil Layout Publik yang sudah berisi Header dan Footer
require_once __DIR__ . '/../../layouts/public_layout.php';
?>
