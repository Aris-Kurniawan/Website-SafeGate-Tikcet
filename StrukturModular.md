Arsitektur Direktori Modular SafeGate

Struktur ini memisahkan antara Komponen Visual, Layout (Pembungkus), dan Halaman (Views). Ini adalah standar industri (mirip konsep MVC atau Component-Based Framework) agar kode mudah di-maintenance dan aman.

Struktur Folder (Directory Tree)

/safegate-web
│
├── /assets/                    # File Statis (Tidak berisi logika PHP/Backend)
│   ├── /css/                   # Tailwind config atau custom CSS
│   ├── /js/                    # Script validasi frontend (Price Ceiling logic)
│   └── /images/                # Logo, ilustrasi, dummy tiket
│
├── /components/                # Potongan UI modular (Dipanggil berkali-kali)
│   ├── header.php              # Navigasi utama (Public)
│   ├── footer.php              # Footer global
│   ├── ticket_card.php         # Desain kartu tiket untuk halaman marketplace
│   ├── escrow_badge.php        # Label "Secured by Escrow"
│   └── sidebar_dashboard.php   # Navigasi samping khusus penjual
│
├── /layouts/                   # Pembungkus (Wrapper) Halaman Utama
│   ├── public_layout.php       # Menggabungkan Header + Konten + Footer
│   └── dashboard_layout.php    # Menggabungkan Sidebar + Konten Dashboard
│
├── /views/                     # File Halaman Utama (Berisi Konten Spesifik)
│   │
│   ├── /public/                # Akses Bebas (Tidak perlu otorisasi)
│   │   ├── home.php            # Landing Page
│   │   ├── cara_kerja.php      # Protocol & Penjelasan SafeGate
│   │   ├── penjualan.php       # Marketplace / List Tiket
│   │   └── detail_tiket.php    # Halaman checkout & rincian kursi
│   │
│   └── /seller/                # Akses Terbatas (WAJIB melewati verifikasi sesi login)
│       ├── transaction.php     # History transaksi & status Escrow
│       └── sell_ticket.php     # Form upload tiket & set harga (Ticket Vault)
│
├── /core/                      # Logika Backend & Keamanan (Wajib dilindungi)
│   ├── db_connect.php          # Koneksi ke PostgreSQL/MySQL
│   ├── auth_middleware.php     # Pengecekan sesi: Tendang user jika belum login
│   └── price_validator.php     # Fungsi pengecekan P_jual <= P_asli + 10%
│
└── index.php                   # Router / Entry Point Utama


Penjelasan Logika Modular

1. Kenapa components dan layouts dipisah?

Components: Ibarat batu bata. header.php dan footer.php ada di sini.

Layouts: Ibarat kerangka rumah. public_layout.php akan melakukan include '../components/header.php', lalu menyisipkan konten utama, dan ditutup dengan include '../components/footer.php'.

Keuntungan: Jika halaman penjualan.php dan cara_kerja.php butuh kerangka yang sama, mereka cukup memanggil public_layout.php. Kamu tidak perlu lagi mengetik include header dan footer di setiap file halaman.

2. Pemisahan /views/public/ dan /views/seller/

Ini adalah Batas Keamanan (Security Boundary).
Semua file yang ada di dalam folder /seller/ wajib diletakkan di bawah perlindungan session login. Jika ada user anonim mencoba mengakses /views/seller/sell_ticket.php, sistem harus langsung memblokirnya dan melemparnya ke halaman Login.

3. Folder /core/ (Logika Bisnis)

Jangan pernah menulis logika koneksi database atau rumus Price Ceiling langsung di dalam file HTML/UI. Simpan rumusnya di /core/, lalu panggil (require) fungsinya di file yang membutuhkan. Ini mencegah kebocoran logika ke sisi klien (browser).

Contoh Cara Kerja Modularitas di File (Pseudo-code PHP)

Ini adalah contoh isi file /views/public/cara_kerja.php. Sangat bersih dan tidak ada kode HTML Header/Footer yang berulang:

<?php
// 1. Definisikan judul halaman
$page_title = "Cara Kerja - Protocol SafeGate";

// 2. Mulai menangkap output konten (Output Buffering)
ob_start();
?>

<!-- KONTEN UTAMA HALAMAN CARA KERJA (HANYA BAGIAN TENGAH) -->
<main class="container mx-auto mt-10">
    <h1 class="text-4xl font-bold text-white">Protocol SafeGate</h1>
    <p class="text-gray-400">We've built a proprietary verification layer...</p>
    <!-- ... sisa desain halaman cara kerja ... -->
</main>

<?php
// 3. Simpan konten ke dalam variabel
$content = ob_get_clean();

// 4. Panggil Layout Publik yang sudah berisi Header dan Footer
require_once '../../layouts/public_layout.php';
?>
