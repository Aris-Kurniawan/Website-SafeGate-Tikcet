<?php
require_once __DIR__ . '/../../core/safegate_repository.php';

$page_title = 'Profil Buyer - SafeGate';
$user = sg_fetch_one(
    'SELECT full_name, email, phone_number, nik, role, created_at FROM users WHERE id = :id LIMIT 1',
    ['id' => sg_current_user_id()]
) ?: [];
$flash = sg_flash();

ob_start();
?>

<section class="container mx-auto py-5" style="max-width: 1040px; padding-left: 1.5rem; padding-right: 1.5rem; margin-top: 3rem; margin-bottom: 5rem;">
    <div class="mb-5">
        <p class="text-safegate-neon fw-bold text-uppercase mb-2" style="font-size: .75rem; letter-spacing: .12em;">Buyer Account</p>
        <h1 class="display-5 fw-bold text-white mb-3 letter-spacing-tight">Profil Saya</h1>
        <p class="text-safegate-text-sec mb-0">Kelola data akun yang dipakai untuk pembelian tiket dan escrow SafeGate.</p>
    </div>

    <?php if ($flash): ?>
        <div class="rounded-4 p-3 mb-4" style="background: <?= ($flash['type'] ?? 'success') === 'error' ? 'rgba(255,85,85,.08)' : 'rgba(217,255,0,.08)' ?>; border: 1px solid <?= ($flash['type'] ?? 'success') === 'error' ? 'rgba(255,85,85,.22)' : 'rgba(217,255,0,.18)' ?>; color: <?= ($flash['type'] ?? 'success') === 'error' ? '#ff6868' : 'var(--safegate-neon)' ?>; font-weight: 700;">
            <?= sg_h($flash['message']) ?>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <form class="sg-glass rounded-4 p-4 p-md-5" action="index.php?page=buyer_profile" method="post">
                <input type="hidden" name="sg_action" value="buyer_profile_update">

                <div class="mb-4">
                    <label class="form-label text-safegate-text-sec fw-bold text-uppercase" style="font-size:.72rem; letter-spacing:.12em;">Nama Lengkap</label>
                    <input type="text" name="full_name" class="form-control bg-black bg-opacity-25 border-secondary text-white rounded-3 py-3" value="<?= sg_h($user['full_name'] ?? '') ?>" required>
                </div>

                <div class="mb-4">
                    <label class="form-label text-safegate-text-sec fw-bold text-uppercase" style="font-size:.72rem; letter-spacing:.12em;">Email</label>
                    <input type="email" class="form-control bg-black bg-opacity-25 border-secondary text-white rounded-3 py-3" value="<?= sg_h($user['email'] ?? '') ?>" disabled>
                    <small class="text-safegate-text-sec">Email dipakai untuk login, jadi dikunci di versi ini.</small>
                </div>

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label text-safegate-text-sec fw-bold text-uppercase" style="font-size:.72rem; letter-spacing:.12em;">Nomor Telepon</label>
                        <input type="text" name="phone_number" class="form-control bg-black bg-opacity-25 border-secondary text-white rounded-3 py-3" value="<?= sg_h($user['phone_number'] ?? '') ?>">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label text-safegate-text-sec fw-bold text-uppercase" style="font-size:.72rem; letter-spacing:.12em;">NIK</label>
                        <input type="text" name="nik" maxlength="16" class="form-control bg-black bg-opacity-25 border-secondary text-white rounded-3 py-3" value="<?= sg_h($user['nik'] ?? '') ?>">
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-3 mt-5">
                    <button type="submit" class="btn btn-safegate-neon rounded-pill fw-bold px-4 py-2">Simpan Profil</button>
                    <a href="index.php?page=my_tickets" class="btn btn-outline-safegate-neon rounded-pill fw-bold px-4 py-2">Tiket Saya</a>
                </div>
            </form>

            <form class="sg-glass rounded-4 p-4 p-md-5 mt-4" action="index.php?page=buyer_profile" method="post">
                <input type="hidden" name="sg_action" value="change_password">
                <input type="hidden" name="return_page" value="buyer_profile">

                <h2 class="h4 fw-bold text-white mb-3">Keamanan Password</h2>
                <p class="text-safegate-text-sec mb-4">Gunakan password baru minimal 8 karakter.</p>

                <div class="mb-3">
                    <label class="form-label text-safegate-text-sec fw-bold text-uppercase" style="font-size:.72rem; letter-spacing:.12em;">Password Lama</label>
                    <input type="password" name="current_password" class="form-control bg-black bg-opacity-25 border-secondary text-white rounded-3 py-3" required autocomplete="current-password">
                </div>
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label text-safegate-text-sec fw-bold text-uppercase" style="font-size:.72rem; letter-spacing:.12em;">Password Baru</label>
                        <input type="password" name="new_password" class="form-control bg-black bg-opacity-25 border-secondary text-white rounded-3 py-3" required minlength="8" autocomplete="new-password">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label text-safegate-text-sec fw-bold text-uppercase" style="font-size:.72rem; letter-spacing:.12em;">Ulangi Password Baru</label>
                        <input type="password" name="confirm_password" class="form-control bg-black bg-opacity-25 border-secondary text-white rounded-3 py-3" required minlength="8" autocomplete="new-password">
                    </div>
                </div>

                <button type="submit" class="btn btn-outline-safegate-neon rounded-pill fw-bold px-4 py-2 mt-4">Update Password</button>
            </form>
        </div>

        <div class="col-12 col-lg-4">
            <aside class="sg-glass rounded-4 p-4 h-100">
                <div class="rounded-circle d-flex align-items-center justify-content-center mb-4" style="width:72px; height:72px; background:rgba(217,255,0,.12); border:1px solid rgba(217,255,0,.25); color:var(--safegate-neon); font-weight:900; font-size:1.6rem;">
                    <?= sg_h(strtoupper(substr($user['full_name'] ?? 'B', 0, 1))) ?>
                </div>
                <p class="text-safegate-text-sec text-uppercase fw-bold mb-1" style="font-size:.7rem; letter-spacing:.12em;">Role</p>
                <h2 class="h5 text-white fw-bold mb-4"><?= sg_h(ucfirst($user['role'] ?? 'buyer')) ?></h2>
                <p class="text-safegate-text-sec text-uppercase fw-bold mb-1" style="font-size:.7rem; letter-spacing:.12em;">Bergabung</p>
                <p class="text-white fw-semibold mb-0"><?= !empty($user['created_at']) ? date('d M Y', strtotime($user['created_at'])) : '-' ?></p>
            </aside>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/public_layout.php';
?>
