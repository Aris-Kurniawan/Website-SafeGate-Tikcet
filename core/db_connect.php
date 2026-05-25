<?php
/**
 * PUSAT KONEKSI DATABASE SAFEGATE
 * File ini ibarat "Kabel Colokan" antara Website PHP dan Mesin MySQL.
 * Jangan pernah meletakkan file ini sembarangan, harus di dalam folder /core/.
 */

// 1. Variabel Konfigurasi (Sesuaikan dengan XAMPP/MAMP milikmu)
$host = '127.0.0.1';        // Alamat server database (localhost)
$dbname = 'safegate_db';      // NAMA DATABASE-MU (Pastikan kamu sudah buat ini di phpMyAdmin)
$username = 'root';             // Username default XAMPP biasanya 'root'
$password = '';                 // Password default XAMPP biasanya kosong (biarkan kosong)

// 2. Data Source Name (DSN) - Menentukan jenis mesin yang dipakai (MySQL)
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

// 3. Konfigurasi Keamanan & Error Handling (Wajib untuk aplikasi finansial)
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Matikan web kalau error, jangan bocorkan data
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Ambil data dalam bentuk Array Murni
    PDO::ATTR_EMULATE_PREPARES => false,                  // Mencegah SQL Injection (Keamanan Tingkat Tinggi)
];

// 4. Mencoba Menyambungkan Kabel (Try-Catch Block)
try {
    // Membuat instance/objek PDO baru (Mencolokkan kabel)
    $pdo = new PDO($dsn, $username, $password, $options);

    // HAPUS ATAU COMMENT BARIS DI BAWAH INI NANTI JIKA SUDAH BERHASIL (Hanya untuk testing)
    // echo "Koneksi ke database '$dbname' BERHASIL!"; 

} catch (PDOException $e) {
    // Jika kabel putus / database tidak ditemukan, tampilkan pesan error yang aman
    die("Sistem Darurat: Gagal terhubung ke Database. Periksa konfigurasi di db_connect.php. Error: " . $e->getMessage());
}
?>