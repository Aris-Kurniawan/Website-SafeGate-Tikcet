SafeGate UI/UX Implementation Guide & Design System

Dokumen ini berisi spesifikasi teknis antarmuka (UI) untuk mengubah desain visual Figma SafeGate menjadi kode frontend yang interaktif, scalable, dan aman.

1. Design Tokens (Variabel CSS / Tailwind Configuration)

Jangan pernah melakukan hardcode warna (contoh: color: #d9ff00) langsung di dalam elemen. Gunakan variabel agar konsisten di seluruh halaman.

1.1. Color Palette

Primary Accent (Neon Yellow): #D9FF00 (Digunakan untuk tombol utama, indikator aktif, dan highlight penting). Tailwind: bg-safegate-neon.

Background (Deep Space): #090B10 (Warna latar belakang utama, sangat gelap tapi bukan #000000). Tailwind: bg-safegate-bg.

Surface/Card (Dark Slate): #12161F (Warna latar belakang untuk form, kartu, dan sidebar). Tailwind: bg-safegate-surface.

Text Primary: #FFFFFF (Untuk heading dan nilai utama).

Text Secondary: #8E95A3 (Untuk label, deskripsi, dan placeholder).

Success Indicator: #00FFA3 (Untuk status 'Verified' atau 'Completed').

Danger/Error: #FF4C4C (Untuk notifikasi harga melebihi batas atau gagal KYC).

1.2. Typography

Font Family: Inter atau Space Grotesk (Kesan modern dan institusional).

Heading 1: text-4xl font-bold tracking-tight text-white (Contoh: "Protocol SafeGate").

Heading 2: text-2xl font-semibold text-white (Contoh: "Sell Your Ticket").

Body Text: text-sm text-gray-400 leading-relaxed.

Number/Monospace: Gunakan font tabular untuk angka harga dan waktu agar sejajar (Contoh: Timer Lelang "02:44:12").

1.3. Borders & Shadows (Glassmorphism ringan)

Card Border: border border-gray-800/50.

Card Radius: rounded-2xl (Sudut membulat yang konsisten pada semua kartu).

Glow Effect: shadow-[0_0_15px_rgba(217,255,0,0.15)] (Digunakan tipis-tipis di belakang tombol CTA utama).

2. Component Library (Reusable Components)

Bangun komponen ini sekali, lalu gunakan berkali-kali. Jangan mengetik ulang HTML yang sama.

2.1. Tombol Utama (Primary CTA)

Visual: Tombol kuning neon, teks hitam bold, radius membulat.
Implementasi Kelas: w-full bg-[#D9FF00] hover:bg-[#c2e600] text-black font-bold py-3 px-6 rounded-full transition-all duration-300 transform hover:scale-[1.02]
Logika: Harus memiliki state :disabled dengan warna redup jika algoritma validasi (seperti Price Ceiling) gagal.

2.2. Label Keamanan (Security Badges)

Visual: Pil kecil di atas kartu, outline kuning/hijau.
Implementasi: <span class="flex items-center gap-2 border border-[#D9FF00] text-[#D9FF00] text-xs font-semibold px-3 py-1 rounded-full"><IconShield/> SECURED</span>

2.3. Kartu Listing Tiket (Marketplace Card)

Struktur UI:

Image Wrapper: h-48 w-full overflow-hidden rounded-t-2xl dengan gambar konser.

Badge Layer: Posisi absolut di pojok kiri atas gambar (KYC Secured).

Content Body: p-5 bg-safegate-surface. Berisi judul, tempat, dan label "Escrow Active".

Footer/Price: Tampilan "Face Value Cap" (Harga Coret) dan tombol "BUY" kecil di kanan.

3. Page Structure & Logic Mapping

Bagian ini memetakan visual halaman dengan Business Logic di belakangnya.

3.1. Halaman: Cara Kerja (Protocol SafeGate)

Fokus Visual: Menampilkan kredibilitas.

Elemen: Teks besar di kiri, Kartu "Security Analytics" di kanan.

Koneksi Backend: Angka "99.8% Success" atau indikator "Escrow Liquidity: Stable" sebaiknya diambil dari API analytics backend agar terlihat hidup, bukan data statis (hardcode).

3.2. Halaman: Detail Tiket (Checkout View)

Fokus Visual: Transparansi transaksi sebelum user menekan "Beli".

Komponen Krusial: Ringkasan Pembayaran (Payment Summary)

Tampilkan breakdown biaya: Tiket Dasar, Biaya Layanan, Asuransi Escrow.

Frontend Logic: Total harga harus dihitung secara reaktif.

Komponen Krusial: Perlindungan Pembeli (Escrow Badge)

Harus selalu terlihat dekat tombol "Beli" untuk psikologi trust.

3.3. Halaman: Jual Tiket (Sell Ticket Dashboard)

Fokus Visual: Input data kurasi tiket.

Form Pricing (Listed Price):

WAJIB ADA LOGIKA FRONTEND: Integrasikan progress bar "Fairness".

Event Listener: Saat user mengetik harga di input box, jalankan script javascript untuk mengecek: if (inputPrice > faceValue * 1.10) { triggerErrorState() }.

Warna progress bar harus berubah menjadi merah jika melebihi batas, dan tombol "LIST MY TICKET" harus otomatis terkunci (disabled).

Ticket Proof (Upload):

Area drag-and-drop dengan batas maksimal ukuran file (Max 10MB).

Tampilkan notifikasi "Encrypting..." saat file diunggah untuk simulasi visual proses OCR dan validasi hash di server.

3.4. Halaman: Marketplace (List Rekomendasi)

Fokus Visual: Etalase produk dengan rasa aman.

Grid System: Gunakan CSS Grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 agar responsif di HP maupun layar besar.

Price Presentation: Harga asli ($P_{asli}$) ditampilkan dicoret (strikethrough) dengan warna abu-abu di sebelah harga jual aktual. Ini adalah bukti visual algoritma batas harga.

4. Rekomendasi Arsitektur Frontend

Untuk mendapatkan antarmuka semulus desain ini, sangat disarankan menggunakan:

Framework: React.js atau Next.js (agar transisi Routing cepat dan state seperti kalkulasi harga bisa real-time).

Styling: Tailwind CSS (untuk mereplikasi utility-first design dari Figma dengan sangat cepat).

Icons: Lucide-React atau Phosphor Icons (Sangat cocok dengan gaya neon-tech SafeGate).