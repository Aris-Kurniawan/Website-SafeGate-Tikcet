<?php
require_once __DIR__ . '/../../core/safegate_repository.php';

// 1. Definisikan judul halaman
$page_title = "Marketplace Tiket - SafeGate";

// 2. Mulai menangkap output konten (Output Buffering)
ob_start();

// Fallback tickets kalau database masih kosong.
$search = trim((string) ($_GET['q'] ?? ''));
$category = $_GET['category'] ?? 'all';
$sort = $_GET['sort'] ?? 'featured';
$dateFilter = trim((string) ($_GET['date'] ?? ''));
$locationFilter = trim((string) ($_GET['location'] ?? ''));
$tickets = sg_get_marketplace_listings([
    'q' => $search,
    'category' => $category,
    'sort' => $sort,
    'date' => $dateFilter,
    'location' => $locationFilter,
]);
?>

<!-- Marketplace Section -->
<section class="container mx-auto py-5"
    style="max-width: 1200px; padding-left: 1.5rem; padding-right: 1.5rem; margin-top: 4rem; margin-bottom: 5rem;">

    <!-- Title Area -->
    <div class="mb-5 text-center text-md-start">
        <p class="text-safegate-neon fw-bold text-uppercase mb-2 letter-spacing-wide" style="font-size: 0.75rem;">
            Secondary Market</p>
        <h1 class="display-4 fw-bold text-white mb-3 letter-spacing-tight">Penjualan Tiket Terverifikasi</h1>
        <p class="text-safegate-text-sec fs-6 mx-auto mx-md-0" style="max-width: 36rem; line-height: 1.6;">
            Temukan tiket konser, pertandingan olahraga, dan festival yang dijual secara aman oleh sesama penggemar.
            Harga dijamin adil di bawah batas atas (Face Value Cap).
        </p>
    </div>

    <!-- Interactive Filters & Search bar (High Fidelity Mockup) -->
    <form class="p-3 rounded-4 mb-5 sg-glass" action="index.php" method="get">
        <input type="hidden" name="page" value="penjualan">
        <div class="row g-3 align-items-center">
            <!-- Search field -->
            <div class="col-12 col-md-5">
                <div
                    class="d-flex align-items-center gap-2 px-3 py-2 rounded-pill bg-dark bg-opacity-50 border border-secondary border-opacity-25">
                    <iconify-icon icon="ph:magnifying-glass" class="text-safegate-neon fs-5"></iconify-icon>
                    <input type="text" id="search-input" name="q" value="<?= sg_h($search) ?>"
                        placeholder="Cari nama event..."
                        class="bg-transparent border-0 text-white w-100 shadow-none p-0" style="font-size: 0.85rem;">
                </div>
            </div>

            <!-- Category dropdown -->
            <div class="col-6 col-md-3">
                <div
                    class="d-flex align-items-center gap-2 px-3 py-2 rounded-pill bg-dark bg-opacity-50 border border-secondary border-opacity-25">
                    <iconify-icon icon="ph:tag-bold" class="text-safegate-text-sec fs-5"></iconify-icon>
                    <select name="category" class="bg-transparent border-0 text-white w-100 shadow-none p-0"
                        style="font-size: 0.85rem; cursor: pointer; outline: none;">
                        <option class="bg-safegate-surface" value="all" <?= $category === 'all' ? 'selected' : '' ?>>Semua
                            Kategori</option>
                        <option class="bg-safegate-surface" value="concert" <?= $category === 'concert' ? 'selected' : '' ?>>Konser Musik</option>
                        <option class="bg-safegate-surface" value="sports" <?= $category === 'sports' ? 'selected' : '' ?>>
                            Olahraga</option>
                        <option class="bg-safegate-surface" value="festival" <?= $category === 'festival' ? 'selected' : '' ?>>Festival</option>
                    </select>
                </div>
            </div>

            <!-- Sort dropdown -->
            <div class="col-6 col-md-4">
                <div
                    class="d-flex align-items-center gap-2 px-3 py-2 rounded-pill bg-dark bg-opacity-50 border border-secondary border-opacity-25">
                    <iconify-icon icon="ph:sort-ascending-bold" class="text-safegate-text-sec fs-5"></iconify-icon>
                    <select name="sort" class="bg-transparent border-0 text-white w-100 shadow-none p-0"
                        style="font-size: 0.85rem; cursor: pointer; outline: none;">
                        <option class="bg-safegate-surface" value="featured" <?= $sort === 'featured' ? 'selected' : '' ?>>
                            Rekomendasi Utama</option>
                        <option class="bg-safegate-surface" value="price-asc" <?= $sort === 'price-asc' ? 'selected' : '' ?>>Harga Terendah</option>
                        <option class="bg-safegate-surface" value="price-desc" <?= $sort === 'price-desc' ? 'selected' : '' ?>>Harga Tertinggi</option>
                        <option class="bg-safegate-surface" value="date" <?= $sort === 'date' ? 'selected' : '' ?>>Tanggal
                            Terdekat</option>
                    </select>
                </div>
            </div>
        </div>
    </form>

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
                $listingId = $ticket['id'] ?? '';
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

    document.addEventListener("DOMContentLoaded", () => {
        const searchInput = document.getElementById("search-input");
        const form = searchInput?.closest("form");
        let submitTimer;

        function scheduleSubmit() {
            window.clearTimeout(submitTimer);
            submitTimer = window.setTimeout(() => {
                form?.requestSubmit();
            }, 450);
        }

        searchInput?.addEventListener("input", filterCards);
        searchInput?.addEventListener("input", scheduleSubmit);
        form?.querySelectorAll("select").forEach((select) => {
            select.addEventListener("change", () => form.requestSubmit());
        });
        filterCards();
    });
</script>

<?php
// 3. Simpan konten ke dalam variabel
$content = ob_get_clean();

// 4. Panggil Layout Publik yang sudah berisi Header dan Footer
require_once __DIR__ . '/../../layouts/public_layout.php';
?>