<?php
// views/admin/kyc_center.php - Ruang Interogasi Identitas (KYC Center)
$page_title = 'KYC Verification - SafeGate Admin';

require_once __DIR__ . '/../../core/admin_middleware.php';
require_once __DIR__ . '/../../core/safegate_repository.php';

// Calculate relative path to assets dynamically based on current script location
$assets_path = (strpos($_SERVER['SCRIPT_NAME'], 'views/') !== false) ? '../../assets' : 'assets';

$kyc_status_filter = $_GET['status'] ?? 'pending';
$submissions = sg_get_admin_kyc_submissions($assets_path, $kyc_status_filter === 'all' ? 'all' : $kyc_status_filter);
$first_submission = $submissions[0] ?? [
    'id' => '',
    'name' => 'No submission selected',
    'nik' => '-',
    'email' => '-',
    'path' => 'Database KYC Queue',
    'ktp_img' => $assets_path . '/images/national_id.png',
    'selfie_img' => '',
    'has_selfie' => false,
];
$flash = sg_flash();

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
        <span><?= count($submissions) ?> <?= $kyc_status_filter === 'all' ? 'KYC Records' : 'Pending Requests' ?></span>
    </div>
</div>

<?php if ($flash): ?>
    <p class="sg-list-status <?= $flash['type'] === 'error' ? 'is-error' : '' ?>"><?= sg_h($flash['message']) ?></p>
<?php endif; ?>

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
            <?php if (!$submissions): ?>
                <div style="padding: 22px; color: var(--admin-text-muted); font-size: 13px; line-height: 1.5;">
                    Belum ada data KYC untuk filter ini.
                </div>
            <?php endif; ?>
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
        
        <a class="sg-kyc-archive-btn" href="index.php?page=admin_kyc&status=<?= $kyc_status_filter === 'all' ? 'pending' : 'all' ?>" style="text-decoration:none; display:block; text-align:center;">
            <?= $kyc_status_filter === 'all' ? 'View Pending Queue' : 'View Archive' ?>
        </a>
    </div>
    
    <!-- Right Column: Current Investigation -->
    <div class="sg-kyc-investigation-view" id="kycDetailsContainer">
        <!-- Header -->
        <div class="sg-kyc-investigation-header">
            <div class="sg-kyc-investigation-title-box">
                <span class="sg-kyc-investigation-title-label">Current Investigation</span>
                <h2 class="sg-kyc-investigation-title">Reviewing: <span id="det-name"><?= sg_h($first_submission['name']) ?></span></h2>
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
                    <img src="<?= sg_h($first_submission['ktp_img']) ?>" id="det-ktp-img" class="sg-kyc-image" alt="KTP Image">
                </div>
            </div>
            <div class="sg-kyc-image-card">
                <span class="sg-kyc-image-label">Selfie with ID</span>
                <div class="sg-kyc-image-container">
                    <img src="<?= sg_h($first_submission['selfie_img']) ?>" id="det-selfie-img" class="sg-kyc-image" alt="Selfie Image" <?= !empty($first_submission['has_selfie']) ? '' : 'hidden' ?>>
                    <div id="det-selfie-empty" class="sg-kyc-image-empty" <?= !empty($first_submission['has_selfie']) ? 'hidden' : '' ?>>
                        <iconify-icon icon="ph:user-focus"></iconify-icon>
                        <span>Selfie belum diupload</span>
                    </div>
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
                        <span id="det-nik"><?= sg_h($first_submission['nik']) ?></span>
                        <span class="sg-kyc-badge-decrypted">Decrypted</span>
                    </div>
                </div>
                <div class="sg-kyc-data-row">
                    <span class="sg-kyc-data-label">Full Name</span>
                    <span class="sg-kyc-data-value" id="det-fullname"><?= sg_h($first_submission['name']) ?></span>
                </div>
                <div class="sg-kyc-data-row">
                    <span class="sg-kyc-data-label">Email Address</span>
                    <span class="sg-kyc-data-value" id="det-email"><?= sg_h($first_submission['email']) ?></span>
                </div>
                <div class="sg-kyc-data-row">
                    <span class="sg-kyc-data-label">Submission Path</span>
                    <span class="sg-kyc-data-value" id="det-path"><?= sg_h($first_submission['path']) ?></span>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="sg-kyc-actions">
            <button class="sg-kyc-btn-reject" onclick="handleKycDecision('reject')" <?= $submissions ? '' : 'disabled' ?>>Reject Application</button>
            <button class="sg-kyc-btn-approve" onclick="handleKycDecision('approve')" <?= $submissions ? '' : 'disabled' ?>>Approve & Verify</button>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div class="sg-toast-container" id="toastContainer"></div>

<!-- JavaScript Logic -->
<script>
// JSON model data dari PHP
const kycData = <?= json_encode($submissions) ?>;
let activeKycId = String(kycData[0]?.id || '');
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
        const selfieImg = document.getElementById('det-selfie-img');
        const selfieEmpty = document.getElementById('det-selfie-empty');
        if (sub.has_selfie && sub.selfie_img) {
            selfieImg.src = sub.selfie_img;
            selfieImg.hidden = false;
            selfieEmpty.hidden = true;
        } else {
            selfieImg.removeAttribute('src');
            selfieImg.hidden = true;
            selfieEmpty.hidden = false;
        }
        
        container.classList.remove('is-loading');
    }, 200);
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
        if (/^\d+$/.test(String(activeKycId))) {
            const form = document.createElement('form');
            form.method = 'post';
            form.action = 'index.php?page=admin_kyc';
            form.innerHTML = `
                <input type="hidden" name="sg_action" value="admin_kyc_decision">
                <input type="hidden" name="kyc_id" value="${activeKycId}">
                <input type="hidden" name="decision" value="${type}">
            `;
            document.body.appendChild(form);
            form.submit();
            return;
        }

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
