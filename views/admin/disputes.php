<?php
// views/admin/disputes.php - Meja Hijau Sengketa Tiket
$page_title = 'Disputes Center - SafeGate Admin';

require_once __DIR__ . '/../../core/admin_middleware.php';

// Mock Data untuk Sengketa
$cases = [
    [
        'id' => 'SG-882',
        'item' => 'Ticket #SG-882 (Coldplay)',
        'amount' => 'Rp 3.000.000',
        'pool' => '3,000,000 IDR',
        'status' => 'FROZEN',
        'reported_by' => 'Reported by Buyer',
        'updated_time' => 'Updated 2m ago',
        'buyer_claim' => 'Tiket tidak bisa di-scan di Gate 3. Petugas bilang barcode sudah digunakan orang lain pada jam 18:00. Saya datang jam 18:30 dan ditolak masuk.',
        'seller_defense' => 'Saya tidak pernah membagikan PDF ke siapa pun selain SafeGate. Tiket valid saat saya beli dari vendor resmi. Buyer mungkin memalsukan bukti scan atau membagikannya ke teman.',
        'ip_origin' => '192.168.1.45',
        'wallet_age' => '2.4 Years',
        'trust_score' => '98%',
        'auth_level' => 'Biometric-Verified',
        'admin_id' => '#99291'
    ],
    [
        'id' => 'SG-901',
        'item' => 'Asset #SG-901 (MacBook Pro)',
        'amount' => 'Rp 1.250.000',
        'pool' => '1,250,000 IDR',
        'status' => 'PENDING',
        'reported_by' => 'Merchant Defense Filed',
        'updated_time' => 'Updated 4h ago',
        'buyer_claim' => 'MacBook Pro yang dikirimkan memiliki retakan di layar bagian bawah dan baterai drop dalam 15 menit. Penjual menolak retur mandiri.',
        'seller_defense' => 'Saya mengirim barang dalam kondisi sempurna dan dibungkus bubble wrap tebal. Ada video packing lengkap. Kerusakan pasti terjadi saat pengiriman atau dilakukan sendiri oleh pembeli.',
        'ip_origin' => '180.244.12.89',
        'wallet_age' => '6 Months',
        'trust_score' => '82%',
        'auth_level' => 'Sms-Verified',
        'admin_id' => '#99292'
    ],
    [
        'id' => 'SG-774',
        'item' => 'Voucher #SG-774 (Luxury Spa)',
        'amount' => 'Rp 500.000',
        'pool' => '500,000 IDR',
        'status' => 'PENDING',
        'reported_by' => 'Automated Lock Triggered',
        'updated_time' => 'Updated 1d ago',
        'buyer_claim' => 'Voucher Spa ditolak di resepsionis karena tertulis sudah kadaluarsa (expired). Padahal di SafeGate tertulis valid sampai akhir bulan depan.',
        'seller_defense' => 'Ada kesalahan sistem pada merchant spa. Saya sudah menghubungi mereka dan bersedia memberikan kode voucher baru jika pembeli mau menunggu.',
        'ip_origin' => '110.138.45.22',
        'wallet_age' => '1.2 Years',
        'trust_score' => '95%',
        'auth_level' => 'Email-Verified',
        'admin_id' => '#99293'
    ]
];

ob_start();
?>

<!-- Header Section -->
<div style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 8px;">
    <div>
        <h1 style="margin: 0; font-size: 32px; font-weight: 800; letter-spacing: -0.04em;">Active Disputes (Escrow Frozen)</h1>
        <p style="margin: 6px 0 0 0; color: var(--admin-text-muted); font-size: 14px; font-weight: 500;">Institutional Oversight & Asset Resolution</p>
    </div>
    <div style="background-color: rgba(255, 184, 0, 0.05); border: 1px solid rgba(255, 184, 0, 0.3); color: var(--admin-warning); font-size: 12px; font-weight: 700; display: flex; align-items: center; gap: 8px; padding: 8px 16px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.02em;">
        <iconify-icon icon="ph:warning-circle-fill" style="font-size: 16px;"></iconify-icon>
        <span>Action Required</span>
    </div>
</div>

<!-- Main Split Layout -->
<div class="sg-disputes-layout">
    
    <!-- Left Column: Search & Case List -->
    <div class="sg-disputes-sidebar">
        <!-- Search bar -->
        <div class="sg-disputes-search-wrapper">
            <iconify-icon icon="ph:magnifying-glass"></iconify-icon>
            <input type="text" id="disputeSearch" class="sg-disputes-search-input" placeholder="Search Case ID or Participant..." onkeyup="filterCases()">
        </div>
        
        <!-- List of cases -->
        <div class="sg-disputes-list" id="disputesList">
            <?php foreach ($cases as $index => $case): ?>
                <div class="sg-dispute-card <?= $index === 0 ? 'is-active' : '' ?>" id="card-<?= $case['id'] ?>" onclick="selectCase('<?= $case['id'] ?>')">
                    <div class="sg-dispute-card-header">
                        <span class="sg-dispute-badge <?= $case['status'] === 'FROZEN' ? 'is-frozen' : 'is-pending' ?>" id="badge-<?= $case['id'] ?>">
                            <?= $case['status'] ?>
                        </span>
                        <span class="sg-dispute-amount"><?= $case['amount'] ?></span>
                    </div>
                    <div class="sg-dispute-card-body">
                        <h4><?= $case['item'] ?></h4>
                        <p><?= $case['reported_by'] ?></p>
                    </div>
                    <div class="sg-dispute-card-footer">
                        <span><?= $case['updated_time'] ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Right Column: Case Dossier Details -->
    <div class="sg-dispute-detail-view" id="disputeDetailsContainer">
        <!-- Header -->
        <div class="sg-dispute-detail-header">
            <div class="sg-dispute-dossier-info">
                <span class="sg-dispute-dossier-label">Case Dossier</span>
                <h2 class="sg-dispute-dossier-title" id="det-title">Dispute Details: SG-882</h2>
            </div>
            <div class="sg-dispute-escrow-pool">
                <span class="sg-dispute-dossier-label">Escrow Pool</span>
                <span class="sg-dispute-escrow-value" id="det-pool">3,000,000 IDR</span>
            </div>
        </div>

        <!-- Buyer Claim -->
        <div class="sg-claim-block">
            <div class="sg-claim-block-title is-buyer">
                <iconify-icon icon="ph:user-fill"></iconify-icon>
                <span>Buyer Claim</span>
            </div>
            <div class="sg-claim-content-box is-buyer">
                <p class="sg-claim-text" id="det-buyer-claim">"Tiket tidak bisa di-scan di Gate 3. Petugas bilang barcode sudah digunakan orang lain pada jam 18:00. Saya datang jam 18:30 dan ditolak masuk."</p>
                <button class="sg-evidence-link-btn" onclick="viewEvidence()">
                    <iconify-icon icon="ph:play-circle-fill"></iconify-icon>
                    <span>View Evidence (Video/Photo)</span>
                </button>
            </div>
        </div>

        <!-- Seller Defense -->
        <div class="sg-claim-block">
            <div class="sg-claim-block-title is-seller">
                <iconify-icon icon="ph:storefront-fill"></iconify-icon>
                <span>Seller Defense</span>
            </div>
            <div class="sg-claim-content-box is-seller">
                <p class="sg-claim-text" id="det-seller-defense">"Saya tidak pernah membagikan PDF ke siapa pun selain SafeGate. Tiket valid saat saya beli dari vendor resmi. Buyer mungkin memalsukan bukti scan atau membagikannya ke teman."</p>
            </div>
        </div>

        <!-- System Metadata -->
        <div class="sg-meta-section">
            <h3 class="sg-meta-section-title">System Metadata</h3>
            <div class="sg-metadata-grid">
                <div class="sg-metadata-card">
                    <span class="sg-metadata-card-label">IP Origin</span>
                    <span class="sg-metadata-card-value" id="det-ip">192.168.1.45</span>
                </div>
                <div class="sg-metadata-card">
                    <span class="sg-metadata-card-label">Wallet Age</span>
                    <span class="sg-metadata-card-value" id="det-wallet">2.4 Years</span>
                </div>
                <div class="sg-metadata-card">
                    <span class="sg-metadata-card-label">Trust Score</span>
                    <span class="sg-metadata-card-value is-high" id="det-trust">98%</span>
                </div>
                <div class="sg-metadata-card">
                    <span class="sg-metadata-card-label">Auth Level</span>
                    <span class="sg-metadata-card-value" id="det-auth">Biometric-Verified</span>
                </div>
            </div>
        </div>

        <!-- Escrow Override Decision -->
        <div class="sg-override-section">
            <div class="sg-override-notice">
                <iconify-icon icon="ph:info-fill"></iconify-icon>
                <span>
                    <strong class="sg-override-notice-strong">Escrow Override (The Judge's Decision)</strong><br>
                    Keputusan ini bersifat final dan memindahkan dana yang ditahan secara permanen. Admin ID: <span id="det-admin-id">#99291</span> trace logged.
                </span>
            </div>
            
            <div class="sg-decision-cards">
                <button class="sg-decision-card-btn is-refund" onclick="handleDecision('refund')">
                    <iconify-icon icon="ph:arrow-counter-clockwise-fill"></iconify-icon>
                    <span class="sg-decision-card-btn-title">Refund Buyer (Ban Seller)</span>
                    <p class="sg-decision-card-btn-desc">Funds returned to buyer wallet. Seller account restricted.</p>
                </button>
                
                <button class="sg-decision-card-btn is-release" onclick="handleDecision('release')">
                    <iconify-icon icon="ph:coins-fill"></iconify-icon>
                    <span class="sg-decision-card-btn-title">Release Fund to Seller (Dismiss Claim)</span>
                    <p class="sg-decision-card-btn-desc">Funds moved to seller payout. Dispute closed.</p>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div class="sg-toast-container" id="toastContainer"></div>

<!-- JavaScript Logic -->
<script>
// JSON model data dari PHP
const casesData = <?= json_encode($cases) ?>;
let activeCaseId = 'SG-882';

// Mengubah detail case di panel kanan
function selectCase(caseId) {
    activeCaseId = caseId;
    
    // Ubah status active card di panel kiri
    document.querySelectorAll('.sg-dispute-card').forEach(card => {
        card.classList.remove('is-active');
    });
    const activeCard = document.getElementById('card-' + caseId);
    if (activeCard) {
        activeCard.classList.add('is-active');
    }
    
    const cData = casesData.find(c => c.id === caseId);
    if (!cData) return;
    
    // Tambah efek loading transisi tipis
    const container = document.getElementById('disputeDetailsContainer');
    container.classList.add('is-loading');
    
    setTimeout(() => {
        // Update DOM elements
        document.getElementById('det-title').textContent = 'Dispute Details: ' + cData.id;
        document.getElementById('det-pool').textContent = cData.pool;
        document.getElementById('det-buyer-claim').textContent = '"' + cData.buyer_claim + '"';
        document.getElementById('det-seller-defense').textContent = '"' + cData.seller_defense + '"';
        document.getElementById('det-ip').textContent = cData.ip_origin;
        document.getElementById('det-wallet').textContent = cData.wallet_age;
        
        const trustEl = document.getElementById('det-trust');
        trustEl.textContent = cData.trust_score;
        // Atur class trust score berdasarkan persentase
        const score = parseInt(cData.trust_score);
        if (score >= 90) {
            trustEl.className = 'sg-metadata-card-value is-high';
            trustEl.style.color = 'var(--admin-success)';
        } else if (score >= 75) {
            trustEl.className = 'sg-metadata-card-value';
            trustEl.style.color = 'var(--admin-warning)';
        } else {
            trustEl.className = 'sg-metadata-card-value';
            trustEl.style.color = 'var(--admin-danger)';
        }
        
        document.getElementById('det-auth').textContent = cData.auth_level;
        document.getElementById('det-admin-id').textContent = cData.admin_id;
        
        container.classList.remove('is-loading');
    }, 200);
}

// Fitur pencarian sengketa
function filterCases() {
    const query = document.getElementById('disputeSearch').value.toLowerCase();
    casesData.forEach(c => {
        const card = document.getElementById('card-' + c.id);
        if (!card) return;
        
        const matchesSearch = c.id.toLowerCase().includes(query) || 
                              c.item.toLowerCase().includes(query) || 
                              c.reported_by.toLowerCase().includes(query);
                              
        if (matchesSearch) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}

// Simulasi view evidence
function viewEvidence() {
    showToast('Success', 'Initiating secure media player... Evidence payload decrypted.', 'success');
}

// Decision Handler
function handleDecision(type) {
    const cData = casesData.find(c => c.id === activeCaseId);
    if (!cData) return;
    
    let confirmMsg = '';
    let successTitle = '';
    let successMsg = '';
    let toastType = '';
    
    if (type === 'refund') {
        confirmMsg = `WARNING: Are you sure you want to REFUND the buyer for case ${cData.id}?\n\nThis will transfer ${cData.amount} back to the Buyer's wallet and immediately restrict the Seller's account. This action cannot be undone.`;
        successTitle = 'Refund Processed';
        successMsg = `Case ${cData.id} Resolved. Funds returned to Buyer, Seller banned.`;
        toastType = 'danger';
    } else {
        confirmMsg = `Are you sure you want to RELEASE the funds to the seller for case ${cData.id}?\n\nThis will transfer ${cData.amount} to the Seller's payout balance and close this dispute. This action cannot be undone.`;
        successTitle = 'Funds Released';
        successMsg = `Case ${cData.id} Resolved. Escrow pool disbursed to Seller.`;
        toastType = 'success';
    }
    
    if (confirm(confirmMsg)) {
        showToast(successTitle, successMsg, toastType);
        
        // Tandai status pada card sebagai RESOLVED
        const badge = document.getElementById('badge-' + cData.id);
        if (badge) {
            badge.textContent = 'RESOLVED';
            badge.className = 'sg-dispute-badge is-resolved';
        }
        
        // Simpan status baru secara lokal
        cData.status = 'RESOLVED';
    }
}

// Toast System
function showToast(title, message, type) {
    const container = document.getElementById('toastContainer');
    
    const toast = document.createElement('div');
    toast.className = `sg-toast is-${type}`;
    
    let iconName = 'ph:info-fill';
    if (type === 'success') iconName = 'ph:check-circle-fill';
    if (type === 'danger') iconName = 'ph:warning-circle-fill';
    
    toast.innerHTML = `
        <iconify-icon icon="${iconName}"></iconify-icon>
        <div>
            <div style="font-weight: 800; font-size: 13px; text-transform: uppercase; letter-spacing: 0.02em; margin-bottom: 2px;">${title}</div>
            <div style="font-weight: 500; font-size: 12px; color: var(--admin-text-muted); line-height: 1.3;">${message}</div>
        </div>
    `;
    
    container.appendChild(toast);
    
    // Auto remove
    setTimeout(() => {
        toast.classList.add('is-fade-out');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 4500);
}
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/admin_layout.php';
?>

