<?php
$page_title = "Marketplace - SafeGate";
ob_start();
?>

<!-- Marketplace Section (Dedicated Page) -->
<section class="max-w-7xl mx-auto px-8 py-20">
    <div class="mb-12 text-center md:text-left">
        <p class="text-safegate-neon text-[10px] font-bold uppercase tracking-widest mb-3">Marketplace</p>
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">Semua Tiket Tersedia</h1>
        <p class="text-safegate-text-sec text-sm max-w-2xl mx-auto md:mx-0 leading-relaxed">
            Cari tiket untuk event favoritmu. Semua transaksi dilindungi oleh sistem Escrow SafeGate yang menjamin tiket asli dengan harga wajar.
        </p>
    </div>
    
    <!-- Filters -->
    <div class="flex gap-4 mb-12 overflow-x-auto pb-4 no-scrollbar">
        <button class="bg-safegate-neon text-black font-bold text-xs px-6 py-2.5 rounded-full whitespace-nowrap">Semua Event</button>
        <button class="border border-gray-700 hover:border-safegate-neon text-white hover:text-safegate-neon font-semibold text-xs px-6 py-2.5 rounded-full whitespace-nowrap transition-colors">Konser Musik</button>
        <button class="border border-gray-700 hover:border-safegate-neon text-white hover:text-safegate-neon font-semibold text-xs px-6 py-2.5 rounded-full whitespace-nowrap transition-colors">Pertandingan Olahraga</button>
        <button class="border border-gray-700 hover:border-safegate-neon text-white hover:text-safegate-neon font-semibold text-xs px-6 py-2.5 rounded-full whitespace-nowrap transition-colors">Teater & Seni</button>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php
        // Sample Data Array (expanded for the marketplace view)
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
            ],
            [
                "image" => "https://images.unsplash.com/photo-1470229722913-7c092db62220?auto=format&fit=crop&q=80&w=800",
                "title" => "Festival Rock",
                "date" => "Okt 10, 2024 • Jakarta",
                "price" => "250.000",
                "originalPrice" => "300.000"
            ],
            [
                "image" => "https://images.unsplash.com/photo-1514525253161-7a46d19cd819?auto=format&fit=crop&q=80&w=800",
                "title" => "Jazz Malam",
                "date" => "Nov 15, 2024 • Bandung",
                "price" => "400.000",
                "originalPrice" => "500.000"
            ],
            [
                "image" => "https://images.unsplash.com/photo-1429962714451-bb934ecdc4ec?auto=format&fit=crop&q=80&w=800",
                "title" => "Pentas Teater Baru",
                "date" => "Des 02, 2024 • TIM",
                "price" => "80.000",
                "originalPrice" => "120.000"
            ]
        ];
        
        foreach($tickets as $ticket) {
            $image = $ticket['image'];
            $title = $ticket['title'];
            $date = $ticket['date'];
            $price = $ticket['price'];
            $originalPrice = $ticket['originalPrice'];
            include '../../components/ticket_card.php';
        }
        ?>
    </div>
</section>

<?php
$content = ob_get_clean();
require_once '../../layouts/public_layout.php';
?>
