<?php
/**
 * PUSAT KONEKSI DATABASE SAFEGATE
 * File ini ibarat "Kabel Colokan" antara Website PHP dan Mesin MySQL.
 * Jangan pernah meletakkan file ini sembarangan, harus di dalam folder /core/.
 */

$host     = getenv('MYSQLHOST') ?: 'localhost'; 
$user     = getenv('MYSQLUSER') ?: 'root';
$password = getenv('MYSQLPASSWORD') ?: '';
$dbname   = getenv('MYSQLDATABASE') ?: 'safegate_db'; // Pastikan di server bernilai 'safegate_db'
$port     = getenv('MYSQLPORT') ?: '3306'; 

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $koneksi = new PDO($dsn, $user, $password);
    $koneksi->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
// 2. Data Source Name (DSN) - Menentukan jenis mesin yang dipakai (MySQL)
$dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";

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