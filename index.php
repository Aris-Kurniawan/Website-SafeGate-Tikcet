<?php
// index.php - Router / Entry Point Utama

// Ambil parameter 'page' dari URL, default ke 'home' jika tidak ada
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Daftar halaman publik yang diizinkan
$public_pages = ['home', 'penjualan', 'cara_kerja', 'detail_tiket', 'login', 'register'];

if (in_array($page, $public_pages)) {
    $view_file = __DIR__ . '/views/public/' . $page . '.php';
    if (file_exists($view_file)) {
        require_once $view_file;
    } else {
        echo "<h1 style='color:white;text-align:center;margin-top:50px;'>404 - Halaman Belum Dibuat</h1>";
    }
} else {
    echo "<h1 style='color:white;text-align:center;margin-top:50px;'>404 - Halaman Tidak Ditemukan</h1>";
}
