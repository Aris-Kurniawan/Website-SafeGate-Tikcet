<?php
require_once __DIR__ . '/../../core/safegate_repository.php';

$page_title = 'Daftar Seller - SafeGate';
$buyer_page = 'seller_register';
$userId = sg_current_user_id();
$user = sg_fetch_one(
    'SELECT full_name, email, phone_number, nik, profile_photo_path FROM users WHERE id = :id LIMIT 1',
    ['id' => $userId]
) ?: [];
$submission = sg_get_user_kyc_submission($userId);
$status = strtolower((string) ($submission['status'] ?? 'unsubmitted'));
$canSubmit = in_array($status, ['unsubmitted', 'rejected'], true);
$flash = sg_flash();

$statusCopy = [
    'approved' => [
        'label' => 'Approved',
        'title' => 'Pendaftaran seller sudah disetujui',
        'body' => 'Akses jual tiket sudah aktif. Kamu bisa masuk ke dashboard seller untuk membuat listing dan memantau penjualan.',
        'icon' => 'ph:seal-check-fill',
    ],
    'pending' => [
        'label' => 'Waiting Review',
        'title' => 'Pendaftaran seller sedang ditinjau admin',
        'body' => 'Dashboard seller bisa dibuka, tetapi fitur jual tiket, listing, wallet seller, dan transaksi seller tetap dikunci sampai admin menyetujui KYC.',
        'icon' => 'ph:clock-countdown',
    ],
    'rejected' => [
        'label' => 'Rejected',
        'title' => 'Pendaftaran perlu dikirim ulang',
        'body' => 'Admin menolak data sebelumnya. Periksa kembali NIK, foto KTP, dan selfie dengan KTP lalu kirim ulang.',
        'icon' => 'ph:warning-diamond',
    ],
    'unsubmitted' => [
        'label' => 'Not Registered',
        'title' => 'Daftar untuk membuka fitur seller',
        'body' => 'Kirim NIK, foto KTP, dan selfie dengan KTP. Setelah disetujui admin, akun kamu bisa menjual tiket di SafeGate.',
        'icon' => 'ph:storefront',
    ],
];
$copy = $statusCopy[$status] ?? $statusCopy['unsubmitted'];

ob_start();
?>

<section class="sg-buyer-content sg-seller-register-page">
    <?php if ($flash): ?>
        <div class="sg-buyer-notice <?= $flash['type'] === 'error' ? 'is-error' : '' ?>"><?= sg_h($flash['message']) ?></div>
    <?php endif; ?>

    <div class="sg-buyer-titlebar">
        <div>
            <h1>Daftar Jadi Seller</h1>
            <p>Lengkapi verifikasi identitas agar kamu bisa menjual tiket dengan sistem escrow SafeGate.</p>
        </div>
        <div class="sg-buyer-actions">
            <a class="sg-buyer-btn" href="index.php?page=buyer_dashboard"><iconify-icon icon="ph:arrow-left"></iconify-icon> Dashboard Pengguna</a>
            <a class="sg-buyer-btn is-neon" href="index.php?page=seller_overview"><iconify-icon icon="ph:storefront"></iconify-icon> Dashboard Seller</a>
        </div>
    </div>

    <div class="sg-seller-register-grid">
        <section class="sg-buyer-panel sg-seller-register-status">
            <span class="sg-buyer-panel-label">Seller Onboarding</span>
            <div class="sg-seller-register-status-icon">
                <iconify-icon icon="<?= sg_h($copy['icon']) ?>"></iconify-icon>
            </div>
            <strong><?= sg_h($copy['label']) ?></strong>
            <h2><?= sg_h($copy['title']) ?></h2>
            <p><?= sg_h($copy['body']) ?></p>
            <?php if ($status === 'pending' && !empty($submission['submitted_at'])): ?>
                <small>Dikirim <?= sg_h(date('d M Y, H:i', strtotime($submission['submitted_at']))) ?></small>
            <?php elseif ($status === 'rejected' && !empty($submission['rejection_reason'])): ?>
                <small>Catatan admin: <?= sg_h($submission['rejection_reason']) ?></small>
            <?php endif; ?>
        </section>

        <section class="sg-buyer-panel sg-seller-register-form-panel">
            <?php if ($canSubmit): ?>
                <form class="sg-seller-register-form" action="index.php?page=seller_register" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="sg_action" value="kyc_submit">
                    <input type="hidden" name="return_page" value="seller_register">
                    <input type="hidden" name="success_page" value="seller_overview">

                    <div class="sg-panel-title-row">
                        <h2>Verifikasi Seller</h2>
                        <span>Step 01/01</span>
                    </div>
                    <p>Data ini masuk ke antrean admin. Pastikan foto jelas supaya approval tidak tertunda.</p>

                    <label>
                        <span>Nama Akun</span>
                        <input type="text" value="<?= sg_h($user['full_name'] ?? '') ?>" readonly>
                    </label>
                    <label>
                        <span>Email</span>
                        <input type="email" value="<?= sg_h($user['email'] ?? '') ?>" readonly>
                    </label>
                    <label>
                        <span>Nomor Induk Kependudukan (NIK)</span>
                        <input name="nik" type="text" placeholder="16 digit NIK" value="<?= sg_h($submission['nik'] ?: ($user['nik'] ?? '')) ?>" maxlength="16" inputmode="numeric" pattern="\d{16}" required>
                    </label>

                    <label>
                        <span>Foto KTP</span>
                        <div class="sg-doc-drop sg-register-drop" data-upload-drop>
                            <input name="ktp_photo" type="file" accept=".jpg,.jpeg,.png,.pdf" hidden required>
                            <iconify-icon icon="ph:cloud-arrow-up"></iconify-icon>
                            <img class="sg-kyc-proof-preview" src="" alt="Preview KTP" hidden>
                            <strong>Drag &amp; drop or click to upload</strong>
                            <small>JPG, PNG, atau PDF - Max 5MB</small>
                        </div>
                    </label>

                    <label>
                        <span>Selfie dengan KTP</span>
                        <div class="sg-doc-drop sg-register-drop" data-upload-drop>
                            <input name="selfie_photo" type="file" accept=".jpg,.jpeg,.png" hidden required>
                            <iconify-icon icon="ph:user-focus"></iconify-icon>
                            <img class="sg-kyc-proof-preview" src="" alt="Preview selfie dengan KTP" hidden>
                            <strong>Drag &amp; drop or click to upload</strong>
                            <small>Foto selfie memegang KTP - JPG/PNG Max 5MB</small>
                        </div>
                    </label>

                    <button class="sg-buyer-btn is-neon sg-register-submit" type="submit">Kirim Pendaftaran Seller</button>
                </form>
            <?php else: ?>
                <div class="sg-seller-register-locked">
                    <iconify-icon icon="<?= $status === 'approved' ? 'ph:seal-check-fill' : 'ph:hourglass-high' ?>"></iconify-icon>
                    <h2><?= $status === 'approved' ? 'Akses seller aktif' : 'Data sedang direview' ?></h2>
                    <p><?= $status === 'approved' ? 'Kamu sudah bisa membuat listing dan mengelola penjualan.' : 'Tunggu admin menyetujui KYC. Kalau sudah approved, fitur jual tiket otomatis terbuka.' ?></p>
                    <a class="sg-buyer-btn is-neon" href="index.php?page=seller_overview">Masuk Dashboard Seller</a>
                </div>
            <?php endif; ?>
        </section>
    </div>
</section>

<script>
document.querySelectorAll('[data-upload-drop]').forEach((drop) => {
    const input = drop.querySelector('input[type="file"]');
    const preview = drop.querySelector('img');
    const title = drop.querySelector('strong');
    const status = drop.querySelector('small');

    function setFile(file) {
        if (!file || !input) return;
        title.textContent = file.name;
        status.textContent = 'Dokumen siap dikirim untuk verifikasi.';
        const extension = file.name.split('.').pop().toLowerCase();
        if (['jpg', 'jpeg', 'png'].includes(extension) && preview) {
            preview.src = URL.createObjectURL(file);
            preview.hidden = false;
            drop.classList.add('has-preview');
        } else if (preview) {
            preview.hidden = true;
            preview.removeAttribute('src');
            drop.classList.remove('has-preview');
        }
    }

    drop.addEventListener('click', (event) => {
        if (event.target !== input) input?.click();
    });
    input?.addEventListener('change', () => setFile(input.files?.[0]));
    ['dragenter', 'dragover'].forEach((eventName) => {
        drop.addEventListener(eventName, (event) => {
            event.preventDefault();
            drop.classList.add('is-dragging');
        });
    });
    ['dragleave', 'drop'].forEach((eventName) => {
        drop.addEventListener(eventName, (event) => {
            event.preventDefault();
            drop.classList.remove('is-dragging');
        });
    });
    drop.addEventListener('drop', (event) => {
        const file = event.dataTransfer?.files?.[0];
        if (!file || !input) return;
        const transfer = new DataTransfer();
        transfer.items.add(file);
        input.files = transfer.files;
        setFile(file);
    });
});
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/buyer_layout.php';
?>
