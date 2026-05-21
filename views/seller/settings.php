<?php
$page_title = 'Account Settings - SafeGate';
$dashboard_page = 'settings';

ob_start();
?>

<section class="sg-vendor-page sg-settings-page">
    <header class="sg-vendor-heading">
        <h1>Account Settings</h1>
        <p>Manage your profile, institutional security, and identity verification status.</p>
    </header>

    <div class="sg-settings-grid">
        <div class="sg-settings-main">
            <section class="sg-panel sg-kyc-panel">
                <h2>Identity Verification (KYC)</h2>
                <p class="sg-danger-label"><iconify-icon icon="ph:warning"></iconify-icon> Status: Unverified</p>
                <p>You are currently unable to withdraw funds. Please complete your identity verification to unlock full institutional features.</p>
                <label>
                    <span>Nomor Induk Kependudukan (NIK)</span>
                    <input type="text" placeholder="16-digit ID number">
                </label>
                <label>
                    <span>Document Upload (KTP)</span>
                    <div class="sg-doc-drop">
                        <iconify-icon icon="ph:cloud-arrow-up"></iconify-icon>
                        <strong>Drag &amp; drop or click to upload</strong>
                        <small>Upload front side of your National ID (Max 5MB)</small>
                    </div>
                </label>
                <button type="button">Submit KYC Documents</button>
            </section>

            <section class="sg-panel sg-security-panel">
                <h2>Security</h2>
                <div class="sg-security-grid">
                    <article>
                        <iconify-icon icon="ph:lock-key"></iconify-icon>
                        <h3>Password</h3>
                        <p>Regularly update your password to keep your account secure.</p>
                        <button type="button">Update Password</button>
                    </article>
                    <article>
                        <iconify-icon icon="ph:fingerprint"></iconify-icon>
                        <h3>Passkeys</h3>
                        <p>Use biometrics for faster, more secure sign-ins and approvals.</p>
                        <button type="button">+ Register Passkey</button>
                    </article>
                </div>
            </section>
        </div>

        <aside class="sg-panel sg-profile-panel">
            <div class="sg-panel-title-row">
                <h2>Profile Information</h2>
                <button type="button" aria-label="Edit profile"><iconify-icon icon="ph:pencil-simple"></iconify-icon></button>
            </div>
            <div class="sg-profile-avatar">JD<span><iconify-icon icon="ph:camera"></iconify-icon></span></div>
            <strong class="sg-change-photo">Change Photo</strong>
            <label><span>Full Name</span><input type="text" value="John Doe Institutional"></label>
            <label><span>Email Address</span><input type="email" value="john.doe@safegate.corp"></label>
            <label><span>Phone Number</span><input type="tel" value="+62 812 3456 7890"></label>
            <div class="sg-account-tier">
                <span>Account Tier</span>
                <strong>Institutional Gold</strong>
                <iconify-icon icon="ph:medal"></iconify-icon>
            </div>
        </aside>
    </div>
</section>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/dashboard_layout.php';
?>
