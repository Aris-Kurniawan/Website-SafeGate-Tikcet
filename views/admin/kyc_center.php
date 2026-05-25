<?php
// views/admin/kyc_center.php - Ruang Interogasi Identitas (KYC Center)
$page_title = 'KYC Verification - SafeGate Admin';

require_once __DIR__ . '/../../core/admin_middleware.php';

// Calculate relative path to assets dynamically based on current script location
$assets_path = (strpos($_SERVER['SCRIPT_NAME'], 'views/') !== false) ? '../../assets' : 'assets';

// Mock Data untuk KYC Submissions
$submissions = [
    [
        'id' => 'K-101',
        'name' => 'Alexander Sterling',
        'nik' => '3512345678901234',
        'email' => 'a.sterling@global-assets.co',
        'path' => 'Institutional Portal v4.2',
        'time' => '10 mins ago',
        'status' => 'PENDING',
        'ktp_img' => $assets_path . '/images/national_id.png',
        'selfie_img' => $assets_path . '/images/selfie_id.png'
    ],
    [
        'id' => 'K-102',
        'name' => 'Sarah Valerius',
        'nik' => '3201456789123456',
        'email' => 's.valerius@valerius-corp.id',
        'path' => 'API Gateway Integration',
        'time' => '42 mins ago',
        'status' => 'PENDING',
        'ktp_img' => $assets_path . '/images/national_id.png',
        'selfie_img' => $assets_path . '/images/selfie_id.png'
    ],
    [
        'id' => 'K-103',
        'name' => 'Dominic Thorne',
        'nik' => '1209384756102938',
        'email' => 'd.thorne@thornemedia.net',
        'path' => 'Mobile Merchant App v1.8',
        'time' => '1 hour ago',
        'status' => 'PENDING',
        'ktp_img' => $assets_path . '/images/national_id.png',
        'selfie_img' => $assets_path . '/images/selfie_id.png'
    ],
    [
        'id' => 'K-104',
        'name' => 'Elena Rodriguez',
        'nik' => '5102938475610293',
        'email' => 'e.rodriguez@nexustravels.com',
        'path' => 'Partner Web Portal v2.0',
        'time' => '3 hours ago',
        'status' => 'PENDING',
        'ktp_img' => $assets_path . '/images/national_id.png',
        'selfie_img' => $assets_path . '/images/selfie_id.png'
    ]
];

ob_start();
?>

<!-- Header Section -->
<div style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 8px;">
    <div>
        <h1 style="margin: 0; font-size: 32px; font-weight: 800; letter-spacing: -0.04em;">KYC Verification Queue</h1>
        <p style="margin: 6px 0 0 0; color: var(--admin-text-muted); font-size: 14px; font-weight: 500;">Reviewing institutional and high-net-worth individual applications.</p>
    </div>
    <div style="background-color: rgba(255, 76, 76, 0.05); border: 1px solid rgba(255, 76, 76, 0.3); color: var(--admin-peach); font-size: 12px; font-weight: 700; display: flex; align-items: center; gap: 8px; padding: 8px 16px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.02em;">
        <span style="display: inline-block; width: 6px; height: 6px; border-radius: 50%; background-color: var(--admin-danger); box-shadow: 0 0 6px var(--admin-danger); animation: sg-pulse-live 2s infinite;"></span>
        <span>12 Pending Requests</span>
    </div>
</div>

<!-- Main Split Layout -->
<div class="sg-kyc-layout">
    
    <!-- Left Column: Recent Submissions -->
    <div class="sg-kyc-sidebar">
        <div class="sg-kyc-sidebar-title-row">
            <h3 class="sg-kyc-sidebar-title">Recent Submissions</h3>
            <button class="sg-kyc-sidebar-filter" aria-label="Filter Submissions">
                <iconify-icon icon="ph:sliders-horizontal-bold"></iconify-icon>
            </button>
        </div>
        
        <div class="sg-kyc-list" id="kycList">
            <?php foreach ($submissions as $index => $sub): ?>
                <div class="sg-kyc-card <?= $index === 0 ? 'is-active' : '' ?>" id="card-<?= $sub['id'] ?>" onclick="selectKyc('<?= $sub['id'] ?>')">
                    <div class="sg-kyc-card-avatar">
                        <iconify-icon icon="ph:user-fill"></iconify-icon>
                    </div>
                    <div class="sg-kyc-card-info">
                        <h4 class="sg-kyc-card-name"><?= $sub['name'] ?></h4>
                        <span class="sg-kyc-card-time"><?= $sub['time'] ?></span>
                    </div>
                    <span class="sg-kyc-card-badge is-pending" id="badge-<?= $sub['id'] ?>">
                        <?= $sub['status'] ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
        
        <button class="sg-kyc-archive-btn" onclick="viewArchive()">View Archive</button>
    </div>
    
    <!-- Right Column: Current Investigation -->
    <div class="sg-kyc-investigation-view" id="kycDetailsContainer">
        <!-- Header -->
        <div class="sg-kyc-investigation-header">
            <div class="sg-kyc-investigation-title-box">
                <span class="sg-kyc-investigation-title-label">Current Investigation</span>
                <h2 class="sg-kyc-investigation-title">Reviewing: <span id="det-name">Alexander Sterling</span></h2>
            </div>
            <div class="sg-kyc-header-actions">
                <button class="sg-kyc-header-action-btn" onclick="zoomImages()" title="Zoom Images" aria-label="Zoom Images">
                    <iconify-icon icon="ph:magnifying-glass-plus-bold"></iconify-icon>
                </button>
                <button class="sg-kyc-header-action-btn" onclick="resetView()" title="Reset View" aria-label="Reset View">
                    <iconify-icon icon="ph:arrow-counter-clockwise-bold"></iconify-icon>
                </button>
            </div>
        </div>

        <!-- Images Panels -->
        <div class="sg-kyc-image-panels">
            <div class="sg-kyc-image-card">
                <span class="sg-kyc-image-label">National ID (KTP)</span>
                <div class="sg-kyc-image-container">
                    <img src="<?= $submissions[0]['ktp_img'] ?>" id="det-ktp-img" class="sg-kyc-image" alt="KTP Image">
                </div>
            </div>
            <div class="sg-kyc-image-card">
                <span class="sg-kyc-image-label">Selfie with ID</span>
                <div class="sg-kyc-image-container">
                    <img src="<?= $submissions[0]['selfie_img'] ?>" id="det-selfie-img" class="sg-kyc-image" alt="Selfie Image">
                </div>
            </div>
        </div>

        <!-- Application Data -->
        <div class="sg-kyc-data-section">
            <h3 class="sg-kyc-data-section-title">Application Data</h3>
            <div class="sg-kyc-data-box">
                <div class="sg-kyc-data-row">
                    <span class="sg-kyc-data-label">NIK (Government ID)</span>
                    <div class="sg-kyc-data-value">
                        <span id="det-nik">3512345678901234</span>
                        <span class="sg-kyc-badge-decrypted">Decrypted</span>
                    </div>
                </div>
                <div class="sg-kyc-data-row">
                    <span class="sg-kyc-data-label">Full Name</span>
                    <span class="sg-kyc-data-value" id="det-fullname">Alexander Sterling</span>
                </div>
                <div class="sg-kyc-data-row">
                    <span class="sg-kyc-data-label">Email Address</span>
                    <span class="sg-kyc-data-value" id="det-email">a.sterling@global-assets.co</span>
                </div>
                <div class="sg-kyc-data-row">
                    <span class="sg-kyc-data-label">Submission Path</span>
                    <span class="sg-kyc-data-value" id="det-path">Institutional Portal v4.2</span>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="sg-kyc-actions">
            <button class="sg-kyc-btn-reject" onclick="handleKycDecision('reject')">Reject Application</button>
            <button class="sg-kyc-btn-approve" onclick="handleKycDecision('approve')">Approve & Verify</button>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div class="sg-toast-container" id="toastContainer"></div>

<!-- JavaScript Logic -->
<script>
// JSON model data dari PHP
const kycData = <?= json_encode($submissions) ?>;
let activeKycId = 'K-101';
let zoomedState = false;

// Mengubah detail KYC di panel kanan
function selectKyc(subId) {
    activeKycId = subId;
    
    // Ubah status active card di panel kiri
    document.querySelectorAll('.sg-kyc-card').forEach(card => {
        card.classList.remove('is-active');
    });
    const activeCard = document.getElementById('card-' + subId);
    if (activeCard) {
        activeCard.classList.add('is-active');
    }
    
    const sub = kycData.find(k => k.id === subId);
    if (!sub) return;
    
    // Tambah efek loading transisi tipis
    const container = document.getElementById('kycDetailsContainer');
    container.classList.add('is-loading');
    
    setTimeout(() => {
        // Update DOM elements
        document.getElementById('det-name').textContent = sub.name;
        document.getElementById('det-nik').textContent = sub.nik;
        document.getElementById('det-fullname').textContent = sub.name;
        document.getElementById('det-email').textContent = sub.email;
        document.getElementById('det-path').textContent = sub.path;
        document.getElementById('det-ktp-img').src = sub.ktp_img;
        document.getElementById('det-selfie-img').src = sub.selfie_img;
        
        container.classList.remove('is-loading');
    }, 200);
}

// Simulasi view archive
function viewArchive() {
    showKycToast('Archive Loaded', 'Retrieving historical KYC records. Audit logs generated.', 'success');
}

// Zoom & Reset Image View
function zoomImages() {
    const images = document.querySelectorAll('.sg-kyc-image');
    zoomedState = !zoomedState;
    images.forEach(img => {
        if (zoomedState) {
            img.style.height = '350px';
        } else {
            img.style.height = '190px';
        }
    });
    showKycToast('Zoom Modified', zoomedState ? 'Investigation resolution increased.' : 'Default zoom restored.', 'info');
}

function resetView() {
    zoomedState = false;
    document.querySelectorAll('.sg-kyc-image').forEach(img => {
        img.style.height = '190px';
    });
    showKycToast('View Reset', 'UI investigation states restored to default values.', 'info');
}

// Decision Handler
function handleKycDecision(type) {
    const sub = kycData.find(k => k.id === activeKycId);
    if (!sub) return;
    
    let confirmMsg = '';
    let successTitle = '';
    let successMsg = '';
    let toastType = '';
    
    if (type === 'reject') {
        confirmMsg = `WARNING: Are you sure you want to REJECT the application of ${sub.name}?\n\nThis will suspend their verification submission and log a security alert.`;
        successTitle = 'KYC Rejected';
        successMsg = `Identity verification rejected for ${sub.name}.`;
        toastType = 'danger';
    } else {
        confirmMsg = `Are you sure you want to APPROVE and VERIFY the identity of ${sub.name}?\n\nThis will grant them Level 4 Seller status and enable transaction listings.`;
        successTitle = 'KYC Approved';
        successMsg = `Identity verified for ${sub.name}. Access level upgraded.`;
        toastType = 'success';
    }
    
    if (confirm(confirmMsg)) {
        showKycToast(successTitle, successMsg, toastType);
        
        // Tandai status pada card
        const badge = document.getElementById('badge-' + sub.id);
        if (badge) {
            badge.textContent = type === 'reject' ? 'REJECTED' : 'APPROVED';
            badge.className = 'sg-kyc-card-badge ' + (type === 'reject' ? 'is-rejected' : 'is-approved');
        }
        
        // Simpan status baru secara lokal
        sub.status = type === 'reject' ? 'REJECTED' : 'APPROVED';
    }
}

// Toast System
function showKycToast(title, message, type) {
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
