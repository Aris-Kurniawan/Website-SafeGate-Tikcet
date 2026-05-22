<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// core/admin_middleware.php - Satpam Bintang 5 SafeGate Level 4
if (isset($_SESSION['user']) && $_SESSION['user']['role'] !== 'admin') {
    http_response_code(403);
    echo "<div style='background:#090B10; color:#FF4C4C; font-family:\"Inter\", sans-serif; text-align:center; padding:40px; height:100vh; display:flex; flex-direction:column; justify-content:center; align-items:center;'>";
    echo "<div style='background:rgba(255, 76, 76, 0.1); border: 1px solid #FF4C4C; padding: 40px; border-radius: 16px; box-shadow: 0 0 40px rgba(255, 76, 76, 0.15); max-width: 600px;'>";
    echo "<h1 style='font-size:5rem; margin:0; font-weight:900;'>403</h1>";
    echo "<h2 style='font-size:1.8rem; color:#8E95A3; margin:15px 0 20px; font-weight:800;'>Akses Ditolak - Satpam Level 4</h2>";
    echo "<p style='line-height:1.6; color:#a6a2a7; font-size: 1.05rem;'>Loh, kamu kan cuma penjual/pembeli biasa, bukan admin! Sistem Satpam Bintang 5 SafeGate langsung mendeteksi pelanggaran keamanan dan melempar kamu keluar.</p>";
    echo "<div style='margin-top: 30px;'>";
    echo "<a href='index.php?page=home' style='background:#D9FF00; color:#090B10; text-decoration:none; padding:14px 28px; border-radius:8px; font-weight:900; box-shadow: 0 0 20px rgba(217, 255, 0, 0.45); transition: 0.3s;'>Kembali ke Home</a>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    exit();
}

// Jika belum login sama sekali, bantu set mock admin session agar halaman Admin bisa dites dengan mudah
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = [
        'username' => 'Admin SafeGate',
        'role' => 'admin',
        'email' => 'admin@safegate.com'
    ];
}
?>
