<?php
// views/admin/disputes.php - Resolusi Sengketa (Meja Hijau)
require_once __DIR__ . '/../../core/admin_middleware.php';

$page_title = 'Dispute Resolution Center - SafeGate';
$admin_page = 'disputes';

ob_start();
?>

<div class="sg-admin-header">
    <div class="sg-admin-title-area">
        <h1>Dispute Resolution Center</h1>
        <p>Meja Hijau SafeGate | Penengah Sengketa Pencairan Escrow Tiket Palsu & Duplikat</p>
    </div>
    <div class="sg-admin-status-badge danger">
        <iconify-icon icon="ph:gavel-fill"></iconify-icon> Court Mode Active
    </div>
</div>

<div class="sg-admin-panel">
    <h2 class="sg-admin-panel-title">
        <iconify-icon icon="ph:scales-fill"></iconify-icon> Sengketa Aktif Butuh Keputusan Admin
    </h2>
    
    <!-- Dispute Card 1 -->
    <div class="sg-dispute-card" style="border-left: 4px solid var(--admin-danger); margin-bottom: 24px;">
        <div class="sg-dispute-header">
            <div>
                <span class="sg-dispute-user" style="font-size: 16px; font-weight: 800; color: #fff;">Sengketa #DISP-0842</span>
                <span style="color: var(--admin-text-secondary); margin-left: 10px; font-size: 12px;">TXID: <code>TX-08942-SG</code></span>
            </div>
            <div>
                <span id="dispute-status-0842" class="sg-admin-status-badge warning">
                    <iconify-icon icon="ph:hourglass-bold"></iconify-icon> Under Review
                </span>
            </div>
        </div>
        
        <div class="sg-dispute-message" style="margin: 15px 0;">
            <p><strong>Pembeli (Aditya Pratama):</strong> "Saya ditolak masuk di gerbang konser Coldplay karena tiket ini terdeteksi sudah pernah discan sebelumnya oleh orang lain. Mohon refund dana escrow saya secepatnya!"</p>
            <p style="color: var(--admin-text-secondary); padding-left: 15px; border-left: 2px solid rgba(255,255,255,0.1); margin-top: 10px;">
                <strong>Penjual (Budi Santoso):</strong> "Loh, tiket itu asli 100% dari pembelian presale saya sendiri. Saya tidak pernah menyebarkan barcode tersebut ke siapapun sebelum terjual di SafeGate."
            </p>
        </div>
        
        <div style="display: flex; gap: 10px; margin-top: 20px;">
            <button class="sg-admin-btn sg-admin-btn-danger js-dispute-refund" data-id="0842">
                <iconify-icon icon="ph:arrow-counter-clockwise-bold"></iconify-icon> Refund to Buyer
            </button>
            <button class="sg-admin-btn sg-admin-btn-accent js-dispute-resolve" data-id="0842">
                <iconify-icon icon="ph:check-circle-bold"></iconify-icon> Release to Seller
            </button>
        </div>
    </div>
    
    <!-- Dispute Card 2 -->
    <div class="sg-dispute-card" style="border-left: 4px solid var(--admin-danger); margin-bottom: 24px;">
        <div class="sg-dispute-header">
            <div>
                <span class="sg-dispute-user" style="font-size: 16px; font-weight: 800; color: #fff;">Sengketa #DISP-0811</span>
                <span style="color: var(--admin-text-secondary); margin-left: 10px; font-size: 12px;">TXID: <code>TX-08691-SG</code></span>
            </div>
            <div>
                <span id="dispute-status-0811" class="sg-admin-status-badge warning">
                    <iconify-icon icon="ph:hourglass-bold"></iconify-icon> Under Review
                </span>
            </div>
        </div>
        
        <div class="sg-dispute-message" style="margin: 15px 0;">
            <p><strong>Pembeli (Siti Rahma):</strong> "Tiket konser Bruno Mars yang dikirim penjual berkategori Festival B, sedangkan di deksripsi lapak tertulis Festival A yang harganya jauh lebih mahal."</p>
            <p style="color: var(--admin-text-secondary); padding-left: 15px; border-left: 2px solid rgba(255,255,255,0.1); margin-top: 10px;">
                <strong>Penjual (Dewi Lestari):</strong> "Maaf, saya salah mengunggah file tiket karena punya 2 tiket yang berbeda kategori. Saya bersedia dikurangi pembayarannya atau direfund saja."
            </p>
        </div>
        
        <div style="display: flex; gap: 10px; margin-top: 20px;">
            <button class="sg-admin-btn sg-admin-btn-danger js-dispute-refund" data-id="0811">
                <iconify-icon icon="ph:arrow-counter-clockwise-bold"></iconify-icon> Refund to Buyer
            </button>
            <button class="sg-admin-btn sg-admin-btn-accent js-dispute-resolve" data-id="0811">
                <iconify-icon icon="ph:check-circle-bold"></iconify-icon> Release to Seller
            </button>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/admin_layout.php';
?>
