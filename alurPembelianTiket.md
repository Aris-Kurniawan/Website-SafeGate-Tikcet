User Flow: Pembeli (Buyer) - SafeGate (V2 - Strict Auction Rules)

Dokumen ini memetakan perjalanan pembeli dengan mengimplementasikan standar lelang industri (Uang Jaminan, Transparansi, Batas Waktu Tegas, dan Sistem Pemenang Cadangan).

ALUR 1: LELANG TIKET HINGGA VERIFIKASI (The Core Engine)

Fase A: Persiapan & Penawaran (Bidding & Deposit)

[UI] Pembeli membuka halaman /views/public/penjualan.php (Katalog Tiket).

[UI] Pembeli mengklik tiket dan masuk ke /views/public/detail_tiket.php.

[Sistem] Halaman menampilkan Anonymous Bid History (Riwayat Penawaran Tersensor, misal: Bidder_***89 - Rp2.500.000).

[Middleware] Pembeli menekan tombol "Place Bid". Sistem mengecek status login. Jika belum, arahkan ke login.php.

[Sistem/Database - ATURAN 1] Sistem mengecek apakah User sudah menyetor Uang Jaminan Lelang (Bid Bond) di dompetnya.

Jika saldo tidak cukup: Munculkan modal "Harap isi saldo jaminan sebesar Rp 50.000 untuk mengikuti lelang ini."

Jika saldo cukup: Saldo jaminan dikunci (Locked).

[Database] PHP mengeksekusi INSERT INTO bids (Menyimpan bidder_id, listing_id, bid_amount).

Fase B: Menang, Wanprestasi & Pembayaran (Hard Timeout & Cascade)

[Cron Job - ATURAN 3] Batas waktu auction_end_at tercapai mutlak. Form input bid dikunci (Disabled).

[Database] Sistem menetapkan Pemenang 1 (Highest Bidder) dengan is_winning_bid = 1.

[Notifikasi] Email/WA terkirim: "Anda Menang! Bayar dalam 2 Jam. Jika gagal, Uang Jaminan Anda hangus."

[Cabang Keputusan (Waktu 2 Jam)]:

Opsi 1 (Sukses Bayar):

Pembeli melunasi pembayaran (Bank/Crypto/E-Wallet).

payment_status = 'paid', escrow_status = 'holding'.

Uang Jaminan Lelang dikembalikan/dicairkan ke dompet pembeli.

Opsi 2 (Gagal Bayar / Kabur - ATURAN 4):

Waktu 2 jam habis. Transaksi Pemenang 1 otomatis failed.

Uang Jaminan Pemenang 1 hangus (disita platform).

[Sistem Cascade] Sistem mencari Pemenang 2 (Runner-up). is_winning_bid dioper ke Pemenang 2. Notifikasi pembayaran baru dikirim ke Pemenang 2.

Fase C: Dompet Tiket & Hari H (The Escrow Release)

[UI] Pembeli masuk ke halaman /views/buyer/my_tickets.php.

[UI] Di tab "Tiket Aktif", pembeli membuka detail tiket dan melihat QR Code.

[Offline] Pembeli datang ke lokasi konser, petugas melakukan scan QR Code di gerbang.

[UI] Pembeli menekan tombol di bawah QR Code.

[Cabang Keputusan]:

[Tombol Hijau - TIKET VALID]: escrow_status menjadi 'released'. Dana cair ke penjual.

[Tombol Merah - LAPOR MASALAH]: escrow_status menjadi 'disputed'. Dana ditahan total untuk investigasi Admin.

ALUR 2: PENGATURAN AKUN PEMBELI (Profile Settings)

Fase Pengaturan Dasar

[UI] Pembeli masuk ke menu "Pengaturan Akun" via Navbar.

[Sistem] User diarahkan ke halaman /views/buyer/profile_settings.php.

[UI] Halaman menampilkan 3 Tab: Profil, Keamanan (Password), dan Notifikasi.

[UI] Pembeli melakukan edit dan menekan "Simpan Perubahan".

[Database] PHP mengeksekusi UPDATE users SET ... WHERE id = :user_id. Alert hijau muncul.