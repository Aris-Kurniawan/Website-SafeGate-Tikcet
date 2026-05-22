<?php
// views/admin/kyc_center.php - Pusat Verifikasi Identitas Vendor (KYC Center)
require_once __DIR__ . '/../../core/admin_middleware.php';

$page_title = 'KYC Verification Center - SafeGate';
$admin_page = 'kyc_center';

ob_start();
?>

<div class="sg-admin-header">
    <div class="sg-admin-title-area">
        <h1>Identity Verification Center (KYC)</h1>
        <p>Ruang Interogasi SafeGate | Peninjauan Identitas Vendor & Keaslian Dokumen Penjual</p>
    </div>
    <div class="sg-admin-status-badge warning">
        <iconify-icon icon="ph:fingerprint-fill"></iconify-icon> Identity Audit Active
    </div>
</div>

<div class="sg-admin-panel">
    <h2 class="sg-admin-panel-title">
        <iconify-icon icon="ph:users-fill"></iconify-icon> Antrean Persetujuan Akun Institusi Vendor
    </h2>
    
    <div class="sg-admin-table-responsive">
        <table class="sg-admin-table">
            <thead>
                <tr>
                    <th>Vendor Name</th>
                    <th>Business Email</th>
                    <th>Identity Card (KTP)</th>
                    <th>Selfie Verification</th>
                    <th>Status</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- KYC Item 1 -->
                <tr>
                    <td>
                        <strong>Aris Kurniawan</strong><br>
                        <small style="color: var(--admin-text-secondary);">Registered: 1 Hour ago</small>
                    </td>
                    <td><code>aris.kurniawan@safegate.com</code></td>
                    <td>
                        <div style="background: var(--admin-surface-soft); border: 1px solid var(--admin-border); border-radius: 6px; padding: 8px 12px; display: inline-flex; align-items: center; gap: 8px; font-size: 12px; color: var(--admin-text-primary);">
                            <iconify-icon icon="ph:cardholder-fill" style="color: var(--admin-accent); font-size: 16px;"></iconify-icon>
                            <span>ktp_aris_kurniawan.jpg</span>
                        </div>
                    </td>
                    <td>
                        <div style="background: var(--admin-surface-soft); border: 1px solid var(--admin-border); border-radius: 6px; padding: 8px 12px; display: inline-flex; align-items: center; gap: 8px; font-size: 12px; color: var(--admin-text-primary);">
                            <iconify-icon icon="ph:camera-fill" style="color: var(--admin-info); font-size: 16px;"></iconify-icon>
                            <span>selfie_aris_kurniawan.jpg</span>
                        </div>
                    </td>
                    <td>
                        <span id="kyc-status-9081" class="sg-admin-status-badge warning">
                            <iconify-icon icon="ph:clock-fill"></iconify-icon> Pending Review
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <div style="display: inline-flex; gap: 8px;">
                            <button class="sg-admin-btn sg-admin-btn-danger js-kyc-reject" data-id="9081" style="padding: 6px 12px; font-size: 11px;">
                                <iconify-icon icon="ph:x-circle-bold"></iconify-icon> Reject
                            </button>
                            <button class="sg-admin-btn sg-admin-btn-accent js-kyc-approve" data-id="9081" style="padding: 6px 12px; font-size: 11px;">
                                <iconify-icon icon="ph:seal-check-bold"></iconify-icon> Approve
                            </button>
                        </div>
                    </td>
                </tr>
                
                <!-- KYC Item 2 -->
                <tr>
                    <td>
                        <strong>Rizky Ramadhan</strong><br>
                        <small style="color: var(--admin-text-secondary);">Registered: 3 Hours ago</small>
                    </td>
                    <td><code>rizky.ramadhan@gmail.com</code></td>
                    <td>
                        <div style="background: var(--admin-surface-soft); border: 1px solid var(--admin-border); border-radius: 6px; padding: 8px 12px; display: inline-flex; align-items: center; gap: 8px; font-size: 12px; color: var(--admin-text-primary);">
                            <iconify-icon icon="ph:cardholder-fill" style="color: var(--admin-accent); font-size: 16px;"></iconify-icon>
                            <span>ktp_rizky_r.jpg</span>
                        </div>
                    </td>
                    <td>
                        <div style="background: var(--admin-surface-soft); border: 1px solid var(--admin-border); border-radius: 6px; padding: 8px 12px; display: inline-flex; align-items: center; gap: 8px; font-size: 12px; color: var(--admin-text-primary);">
                            <iconify-icon icon="ph:camera-fill" style="color: var(--admin-info); font-size: 16px;"></iconify-icon>
                            <span>selfie_rizky_r.jpg</span>
                        </div>
                    </td>
                    <td>
                        <span id="kyc-status-9082" class="sg-admin-status-badge warning">
                            <iconify-icon icon="ph:clock-fill"></iconify-icon> Pending Review
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <div style="display: inline-flex; gap: 8px;">
                            <button class="sg-admin-btn sg-admin-btn-danger js-kyc-reject" data-id="9082" style="padding: 6px 12px; font-size: 11px;">
                                <iconify-icon icon="ph:x-circle-bold"></iconify-icon> Reject
                            </button>
                            <button class="sg-admin-btn sg-admin-btn-accent js-kyc-approve" data-id="9082" style="padding: 6px 12px; font-size: 11px;">
                                <iconify-icon icon="ph:seal-check-bold"></iconify-icon> Approve
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/admin_layout.php';
?>
