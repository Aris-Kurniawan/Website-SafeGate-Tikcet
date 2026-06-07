<?php
// import_dummy.php
// Script otomatis untuk memasukkan data dummy ke database safegate_db.

require_once __DIR__ . '/core/db_connect.php';

echo "========================================================\n";
echo "SafeGate Ticket Platform - Automated Dummy Data Importer\n";
echo "========================================================\n";

$db = sg_db();
if (!$db) {
    echo "Error: Koneksi database gagal! Harap pastikan MySQL/MariaDB XAMPP Anda aktif.\n";
    echo "Pesan error: " . sg_db_error() . "\n";
    exit(1);
}

$sqlFile = __DIR__ . '/database/dummy_data_safegate.sql';
if (!file_exists($sqlFile)) {
    echo "Error: File SQL data dummy tidak ditemukan di: $sqlFile\n";
    exit(1);
}

echo "Membaca file data dummy SQL...\n";
$sql = file_get_contents($sqlFile);

echo "Mengimpor data ke database 'safegate_db'...\n";
try {
    // Menonaktifkan penyiapan query emulasi untuk mengizinkan multi-query jika perlu
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);

    // Eksekusi seluruh isi file SQL
    $db->exec($sql);

    echo "--------------------------------------------------------\n";
    echo "SUKSES! Semua data dummy berhasil diimpor ke database.\n";
    echo "Akun percobaan yang siap digunakan:\n";
    echo "  1. Admin  : admin@safegate.local (password: password123)\n";
    echo "  2. Seller : budi.seller@safegate.local (password: password123)\n";
    echo "  3. Seller : siti.vendor@safegate.local (password: password123)\n";
    echo "  4. Seller : seller@safegate.local (password: password123)\n";
    echo "  5. Buyer  : buyer@safegate.local (password: password123)\n";
    echo "  6. Buyer  : andi.buyer@safegate.local (password: password123)\n";
    echo "  7. Buyer  : dewi.fans@safegate.local (password: password123)\n";
    echo "========================================================\n";
} catch (Throwable $e) {
    echo "Error saat mengimpor SQL: " . $e->getMessage() . "\n";
}
