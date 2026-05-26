<?php
require_once __DIR__ . '/../../core/safegate_repository.php';

$page_title = 'Account Settings - SafeGate';
$dashboard_page = 'settings';
$seller_id = sg_current_user_id('seller');
$profile = sg_get_seller_profile($seller_id);
$flash = sg_flash();

ob_start();
?>

<section class="sg-vendor-page sg-settings-page">
    <header class="sg-vendor-heading">
        <h1>Account Settings</h1>
        <p>Manage your profile, institutional security, and identity verification status.</p>
    </header>

    <?php if ($flash): ?>
        <p class="sg-list-status <?= $flash['type'] === 'error' ? 'is-error' : '' ?>"><?= sg_h($flash['message']) ?></p>
    <?php endif; ?>

    <div class="sg-settings-grid">
        <div class="sg-settings-main">
            <form class="sg-panel sg-kyc-panel" action="index.php?page=settings" method="post" enctype="multipart/form-data">
                <input type="hidden" name="sg_action" value="kyc_submit">
                <h2>Identity Verification (KYC)</h2>
                <p class="sg-danger-label"><iconify-icon icon="ph:warning"></iconify-icon> Status: <?= sg_h(ucwords($profile['kyc_status'] ?? 'unverified')) ?></p>
                <p>You are currently unable to withdraw funds. Please complete your identity verification to unlock full institutional features.</p>
                <label>
                    <span>Nomor Induk Kependudukan (NIK)</span>
                    <input name="nik" type="text" placeholder="16-digit ID number" value="<?= sg_h($profile['nik'] ?? '') ?>" maxlength="16" inputmode="numeric" pattern="\d{16}" required>
                </label>
                <label>
                    <span>Document Upload (KTP)</span>
                    <div class="sg-doc-drop">
                        <input id="kycFile" name="ktp_photo" type="file" accept=".jpg,.jpeg,.png,.pdf" style="display:none">
                        <iconify-icon icon="ph:cloud-arrow-up"></iconify-icon>
                        <img id="kycPreview" class="sg-kyc-proof-preview" src="" alt="KTP preview" hidden>
                        <strong>Drag &amp; drop or click to upload</strong>
                        <small id="kycFileStatus">Upload front side of your National ID (JPG, PNG, PDF - Max 5MB)</small>
                    </div>
                </label>
                <button type="submit">Submit KYC Documents</button>
            </form>

            <section class="sg-panel sg-security-panel">
                <h2>Security</h2>
                <div class="sg-security-grid">
                    <article>
                        <iconify-icon icon="ph:lock-key"></iconify-icon>
                        <h3>Password</h3>
                        <p>Regularly update your password to keep your account secure.</p>
                        <form action="index.php?page=settings" method="post" style="display:grid; gap:10px; margin-top:14px;">
                            <input type="hidden" name="sg_action" value="change_password">
                            <input type="hidden" name="return_page" value="settings">
                            <input type="password" name="current_password" placeholder="Password lama" required autocomplete="current-password" style="width:100%; min-height:42px; border-radius:8px; border:1px solid rgba(255,255,255,.12); background:#0b0f16; color:#fff; padding:0 12px;">
                            <input type="password" name="new_password" placeholder="Password baru" required minlength="8" autocomplete="new-password" style="width:100%; min-height:42px; border-radius:8px; border:1px solid rgba(255,255,255,.12); background:#0b0f16; color:#fff; padding:0 12px;">
                            <input type="password" name="confirm_password" placeholder="Ulangi password baru" required minlength="8" autocomplete="new-password" style="width:100%; min-height:42px; border-radius:8px; border:1px solid rgba(255,255,255,.12); background:#0b0f16; color:#fff; padding:0 12px;">
                            <button type="submit">Update Password</button>
                        </form>
                    </article>
                    <article>
                        <iconify-icon icon="ph:fingerprint"></iconify-icon>
                        <h3>Passkeys</h3>
                        <p>Use biometrics for faster, more secure sign-ins and approvals.</p>
                        <form action="index.php?page=settings" method="post" style="display:grid; gap:10px; margin-top:14px;">
                            <input type="hidden" name="sg_action" value="register_passkey">
                            <input type="text" name="device_name" value="Browser Device" aria-label="Device name" style="width:100%; min-height:42px; border-radius:8px; border:1px solid rgba(255,255,255,.12); background:#0b0f16; color:#fff; padding:0 12px;">
                            <button type="submit">+ Register Passkey</button>
                        </form>
                    </article>
                </div>
            </section>
        </div>

        <form class="sg-panel sg-profile-panel" action="index.php?page=settings" method="post">
            <input type="hidden" name="sg_action" value="seller_profile_update">
            <div class="sg-panel-title-row">
                <h2>Profile Information</h2>
                <button type="submit" aria-label="Save profile"><iconify-icon icon="ph:floppy-disk"></iconify-icon></button>
            </div>
            <div class="sg-profile-avatar">JD<span><iconify-icon icon="ph:camera"></iconify-icon></span></div>
            <strong class="sg-change-photo">Change Photo</strong>
            <label><span>Full Name</span><input type="text" name="full_name" value="<?= sg_h($profile['full_name']) ?>" required></label>
            <label><span>Email Address</span><input type="email" value="<?= sg_h($profile['email']) ?>" readonly></label>
            <label><span>Phone Number</span><input type="tel" name="phone_number" value="<?= sg_h($profile['phone_number']) ?>"></label>
            <div class="sg-account-tier">
                <span>Account Tier</span>
                <strong>Institutional Gold</strong>
                <iconify-icon icon="ph:medal"></iconify-icon>
            </div>
        </form>
    </div>
</section>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/dashboard_layout.php';
?>
