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
