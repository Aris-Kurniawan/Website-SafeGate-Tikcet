<?php
// core/request_handlers.php

require_once __DIR__ . '/safegate_repository.php';

function sg_redirect(string $page): void
{
    header('Location: index.php?page=' . urlencode($page));
    exit;
}

function sg_redirect_url(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function sg_role_home(?string $role = null): string
{
    $role = $role ?: ($_SESSION['role'] ?? null);
    if ($role === 'admin') {
        return 'admin_overview';
    }
    if ($role === 'buyer') {
        return 'home';
    }
    return 'seller_overview';
}

function sg_require_route_access(string $page): void
{
    $protectedRoutes = [
        'seller_overview' => ['buyer', 'seller'],
        'sell_ticket' => ['buyer', 'seller'],
        'active_listings' => ['buyer', 'seller'],
        'wallet' => ['buyer', 'seller'],
        'transaction' => ['buyer', 'seller'],
        'settings' => ['buyer', 'seller'],
        'admin_overview' => ['admin'],
        'admin_transactions' => ['admin'],
        'admin_disputes' => ['admin'],
        'admin_kyc' => ['admin'],
        'buyer_dashboard' => ['buyer', 'seller', 'admin'],
        'my_tickets' => ['buyer', 'seller', 'admin'],
        'buyer_wallet' => ['buyer', 'seller', 'admin'],
        'buyer_transactions' => ['buyer', 'seller', 'admin'],
        'buyer_profile' => ['buyer', 'seller', 'admin'],
        'ticket_verify' => ['buyer', 'seller', 'admin'],
    ];

    if (!isset($protectedRoutes[$page])) {
        return;
    }

    if (empty($_SESSION['user_id'])) {
        sg_flash('Silakan login dulu untuk membuka halaman itu.', 'error');
        sg_redirect('login');
    }

    if (!in_array($_SESSION['role'] ?? '', $protectedRoutes[$page], true)) {
        sg_flash('Akun kamu tidak punya akses ke halaman itu.', 'error');
        sg_redirect(sg_role_home());
    }
}

function sg_handle_create_listing(): void
{
    $sellerId = sg_current_user_id();
    if (!$sellerId || !sg_db()) {
        sg_flash('Login dulu sebelum membuat listing.', 'error');
        sg_redirect('login');
    }

    $kycStatus = sg_fetch_one('SELECT status FROM kyc_verifications WHERE user_id = :user_id ORDER BY id DESC LIMIT 1', ['user_id' => $sellerId]);
    if (!$kycStatus || $kycStatus['status'] !== 'approved') {
        sg_flash('Identitas kamu belum diverifikasi oleh admin. Lengkapi KYC dan tunggu persetujuan terlebih dahulu sebelum menjual tiket.', 'error');
        sg_redirect('settings');
    }

    // 1. Tangkap dan Validasi Event Details (Step 1)
    $eventTitle = trim((string) ($_POST['event_title'] ?? ''));
    $eventVenue = trim((string) ($_POST['event_venue'] ?? ''));
    $eventCity = trim((string) ($_POST['event_city'] ?? ''));
    $eventDate = trim((string) ($_POST['event_date'] ?? ''));
    $eventTime = trim((string) ($_POST['event_time'] ?? ''));
    $eventDesc = trim((string) ($_POST['event_description'] ?? ''));

    if ($eventTitle === '' || $eventVenue === '' || $eventDate === '') {
        sg_flash('Detail acara (Nama, Lokasi, Tanggal) wajib diisi.', 'error');
        sg_redirect('sell_ticket');
    }

    $thumbnailPath = sg_upload_file('event_thumbnail', 'events', 'pending-thumbnail', ['jpg', 'jpeg', 'png'], 5 * 1024 * 1024);
    if (sg_upload_error()) {
        sg_flash('Upload thumbnail gagal: ' . sg_upload_error(), 'error');
        sg_redirect('sell_ticket');
    }

    // 2. Tangkap Data Harga & Tiket (Step 2 & 3)
    $startingBid = (int) preg_replace('/[^\d]/', '', (string) ($_POST['starting_bid'] ?? '0'));
    $reservePrice = (int) preg_replace('/[^\d]/', '', (string) ($_POST['reserve_price'] ?? '0'));
    $faceValue = (int) preg_replace('/[^\d]/', '', (string) ($_POST['face_value'] ?? '0'));
    $duration = (int) ($_POST['duration'] ?? 24);
    $section = strtoupper(trim((string) ($_POST['section'] ?? '')));
    $row = strtoupper(trim((string) ($_POST['row'] ?? '')));
    $seat = strtoupper(trim((string) ($_POST['seat'] ?? '')));

    if ($startingBid <= 0 || $faceValue <= 0) {
        sg_flash('Data harga belum lengkap. Isi Face Value dan Harga Jual (Starting Bid) dulu.', 'error');
        sg_redirect('sell_ticket');
    }

    $proofPath = sg_upload_file('ticket_proof', 'tickets', 'pending-ticket-proof', ['pdf', 'jpg', 'jpeg', 'png', 'pkpass'], 10 * 1024 * 1024);
    if (sg_upload_error()) {
        sg_flash('Upload tiket gagal: ' . sg_upload_error(), 'error');
        sg_redirect('sell_ticket');
    }
    if ($proofPath === 'pending-ticket-proof') {
        sg_flash('Upload bukti tiket wajib sebelum membuat listing.', 'error');
        sg_redirect('sell_ticket');
    }
    $auctionEndAt = date('Y-m-d H:i:s', time() + ($duration * 3600));

    // 3. Simpan Event ke Database Terlebih Dahulu
    sg_execute(
        'INSERT INTO events (title, venue, city, event_date, event_time, image_path, description)
         VALUES (:title, :venue, :city, :event_date, :event_time, :image_path, :description)',
        [
            'title' => $eventTitle,
            'venue' => $eventVenue,
            'city' => $eventCity,
            'event_date' => $eventDate,
            'event_time' => $eventTime,
            'image_path' => $thumbnailPath === 'pending-thumbnail' ? null : $thumbnailPath,
            'description' => $eventDesc
        ]
    );
    $eventId = (int) sg_db()->lastInsertId();

    if ($section === '' || $row === '' || $seat === '') {
        sg_flash('Section, row, dan seat wajib diisi agar tempat duduk tiap tiket jelas.', 'error');
        sg_redirect('sell_ticket');
    }

    if (strlen($section) > 20 || strlen($row) > 20 || strlen($seat) > 20) {
        sg_flash('Section, row, dan seat maksimal 20 karakter.', 'error');
        sg_redirect('sell_ticket');
    }

    $duplicateSeat = sg_fetch_one(
        'SELECT id
         FROM ticket_listings
         WHERE event_id = :event_id
           AND section = :section
           AND row = :row
           AND seat = :seat
           AND listing_status NOT IN ("cancelled", "sold")
         LIMIT 1',
        [
            'event_id' => $eventId,
            'section' => $section,
            'row' => $row,
            'seat' => $seat,
        ]
    );
    if ($duplicateSeat) {
        sg_flash('Bangku ini sudah terdaftar untuk event yang sama. Masukkan section, row, atau seat yang berbeda.', 'error');
        sg_redirect('sell_ticket');
    }

    $created = sg_execute(
        'INSERT INTO ticket_listings
            (seller_id, event_id, section, row, seat, face_value, starting_bid, reserve_price, current_highest_bid, auction_duration_hours, auction_end_at, ticket_proof_path, listing_status)
         VALUES
            (:seller_id, :event_id, :section, :row, :seat, :face_value, :starting_bid, :reserve_price, :current_highest_bid, :duration, :auction_end_at, :proof, "active")',
        [
            'seller_id' => $sellerId,
            'event_id' => $eventId,
            'section' => $section,
            'row' => $row,
            'seat' => $seat,
            'face_value' => $faceValue,
            'starting_bid' => $startingBid,
            'reserve_price' => $reservePrice ?: null,
            'current_highest_bid' => $startingBid,
            'duration' => $duration,
            'auction_end_at' => $auctionEndAt,
            'proof' => $proofPath,
        ]
    );

    sg_flash($created ? 'Listing tiket berhasil disimpan ke database dan aktif di marketplace.' : 'Listing gagal disimpan: ' . sg_db_error(), $created ? 'success' : 'error');
    sg_redirect('active_listings');
}

function sg_handle_withdrawal(): void
{
    $sellerId = sg_current_user_id();
    $amount = (int) preg_replace('/[^\d]/', '', (string) ($_POST['amount'] ?? '0'));
    $method = $_POST['method'] ?? 'bank_transfer';
    $destination = trim((string) ($_POST['destination_account'] ?? ''));

    if (!$sellerId || !sg_db()) {
        sg_flash('Login dulu sebelum menarik dana.', 'error');
        sg_redirect('login');
    }

    if ($amount < 60000 || $destination === '') {
        sg_flash('Minimal penarikan Rp 60.000 dan tujuan wajib diisi.', 'error');
        sg_redirect('wallet');
    }

    $created = sg_execute(
        'INSERT INTO withdrawals (seller_id, amount, method, destination_account, status)
         VALUES (:seller_id, :amount, :method, :destination, "pending")',
        [
            'seller_id' => $sellerId,
            'amount' => $amount,
            'method' => $method,
            'destination' => $destination,
        ]
    );

    sg_flash($created ? 'Request penarikan berhasil masuk database.' : 'Penarikan gagal: ' . sg_db_error(), $created ? 'success' : 'error');
    sg_redirect('wallet');
}

function sg_handle_buyer_wallet_topup(): void
{
    $buyerId = sg_current_user_id();
    $amount = (int) preg_replace('/[^\d]/', '', (string) ($_POST['amount'] ?? '0'));

    if (!$buyerId || !sg_db()) {
        sg_flash('Login dulu sebelum top up saldo.', 'error');
        sg_redirect('login');
    }

    if ($amount < 50000) {
        sg_flash('Minimal top up Rp 50.000 untuk saldo jaminan lelang.', 'error');
        sg_redirect('buyer_wallet');
    }

    $created = sg_wallet_activity($buyerId, 'top_up', $amount, 'credit', 'completed', 'Top up saldo jaminan melalui simulasi bank transfer.');
    sg_flash($created ? 'Top up saldo berhasil masuk ke wallet pembeli.' : 'Top up gagal: ' . sg_db_error(), $created ? 'success' : 'error');
    sg_redirect('buyer_wallet');
}

function sg_handle_buyer_wallet_withdraw(): void
{
    $buyerId = sg_current_user_id();
    $amount = (int) preg_replace('/[^\d]/', '', (string) ($_POST['amount'] ?? '0'));
    $destination = trim((string) ($_POST['destination'] ?? ''));

    if (!$buyerId || !sg_db()) {
        sg_flash('Login dulu sebelum tarik saldo.', 'error');
        sg_redirect('login');
    }

    $wallet = sg_get_buyer_wallet_summary($buyerId);
    if ($amount < 50000 || $destination === '' || $amount > $wallet['available']) {
        sg_flash('Nominal tarik saldo tidak valid atau saldo tidak cukup.', 'error');
        sg_redirect('buyer_wallet');
    }

    $created = sg_wallet_activity($buyerId, 'withdrawal', $amount, 'debit', 'pending', 'Permintaan tarik saldo ke ' . $destination . '.');
    sg_flash($created ? 'Permintaan tarik saldo tercatat di database.' : 'Tarik saldo gagal: ' . sg_db_error(), $created ? 'success' : 'error');
    sg_redirect('buyer_wallet');
}

function sg_handle_kyc_submit(): void
{
    $sellerId = sg_current_user_id();
    $nik = preg_replace('/[^\d]/', '', (string) ($_POST['nik'] ?? ''));

    if (!$sellerId || !sg_db()) {
        sg_flash('Login dulu sebelum submit KYC.', 'error');
        sg_redirect('login');
    }

    $kycStatus = sg_fetch_one('SELECT status FROM kyc_verifications WHERE user_id = :user_id ORDER BY id DESC LIMIT 1', ['user_id' => $sellerId]);
    if ($kycStatus && strtolower($kycStatus['status']) === 'approved') {
        sg_flash('KYC kamu sudah diverifikasi, tidak perlu mengirim ulang.', 'error');
        sg_redirect('settings');
    }

    if (strlen($nik) !== 16) {
        sg_flash('NIK harus 16 digit.', 'error');
        sg_redirect('settings');
    }

    $ktpPath = sg_upload_file('ktp_photo', 'kyc', 'pending-ktp-upload', ['jpg', 'jpeg', 'png', 'pdf'], 5 * 1024 * 1024);
    if (sg_upload_error()) {
        sg_flash('Upload KTP gagal: ' . sg_upload_error(), 'error');
        sg_redirect('settings');
    }
    if ($ktpPath === 'pending-ktp-upload') {
        sg_flash('Upload dokumen KTP wajib untuk submit KYC.', 'error');
        sg_redirect('settings');
    }

    $selfiePath = sg_upload_file('selfie_photo', 'kyc', '', ['jpg', 'jpeg', 'png'], 5 * 1024 * 1024);
    if (sg_upload_error()) {
        sg_flash('Upload selfie gagal: ' . sg_upload_error(), 'error');
        sg_redirect('settings');
    }

    $created = sg_execute(
        'INSERT INTO kyc_verifications (user_id, nik, ktp_photo_path, selfie_photo_path, status)
         VALUES (:user_id, :nik, :ktp, :selfie, "pending")',
        [
            'user_id' => $sellerId,
            'nik' => $nik,
            'ktp' => $ktpPath,
            'selfie' => $selfiePath,
        ]
    );

    if ($created) {
        sg_execute('UPDATE users SET nik = :nik WHERE id = :id', ['nik' => $nik, 'id' => $sellerId]);
        $kyc = sg_fetch_one(
            'SELECT id FROM kyc_verifications WHERE user_id = :user_id ORDER BY id DESC LIMIT 1',
            ['user_id' => $sellerId]
        );
        if ($kyc) {
            sg_execute(
                'INSERT INTO seller_profiles (user_id, kyc_id)
                 VALUES (:user_id, :kyc_id)
                 ON DUPLICATE KEY UPDATE kyc_id = VALUES(kyc_id)',
                ['user_id' => $sellerId, 'kyc_id' => $kyc['id']]
            );
        }
    }

    sg_flash($created ? 'Dokumen KYC berhasil dikirim ke database.' : 'KYC gagal dikirim: ' . sg_db_error(), $created ? 'success' : 'error');
    sg_redirect('settings');
}

function sg_handle_buyer_profile_update(): void
{
    $userId = sg_current_user_id();
    if (!$userId || !sg_db()) {
        sg_flash('Login dulu sebelum mengubah profil.', 'error');
        sg_redirect('login');
    }
    sg_ensure_user_profile_schema();

    $fullName = trim((string) ($_POST['full_name'] ?? ''));
    $phone = trim((string) ($_POST['phone_number'] ?? ''));
    $nik = preg_replace('/[^\d]/', '', (string) ($_POST['nik'] ?? ''));

    if ($fullName === '' || ($nik !== '' && strlen($nik) !== 16)) {
        sg_flash('Nama wajib diisi dan NIK harus 16 digit jika digunakan.', 'error');
        sg_redirect('buyer_profile');
    }

    $photoPath = sg_upload_file('profile_photo', 'profiles', '', ['jpg', 'jpeg', 'png', 'webp'], 3 * 1024 * 1024);
    if (sg_upload_error()) {
        sg_flash('Upload foto profil gagal: ' . sg_upload_error(), 'error');
        sg_redirect('buyer_profile');
    }

    $params = [
        'full_name' => $fullName,
        'phone' => $phone ?: null,
        'nik' => $nik ?: null,
        'id' => $userId,
    ];
    $photoSql = '';
    if ($photoPath !== '') {
        $photoSql = ', profile_photo_path = :profile_photo_path';
        $params['profile_photo_path'] = $photoPath;
    }

    $updated = sg_execute(
        'UPDATE users SET full_name = :full_name, phone_number = :phone, nik = :nik' . $photoSql . ' WHERE id = :id',
        $params
    );

    sg_flash($updated ? 'Profil berhasil diperbarui.' : 'Profil gagal diperbarui: ' . sg_db_error(), $updated ? 'success' : 'error');
    sg_redirect('buyer_profile');
}

function sg_handle_change_password(): void
{
    $userId = sg_current_user_id();
    $returnPage = $_POST['return_page'] ?? sg_role_home();
    $allowedReturnPages = ['settings', 'buyer_profile', 'admin_overview'];
    if (!in_array($returnPage, $allowedReturnPages, true)) {
        $returnPage = sg_role_home();
    }

    if (!$userId || !sg_db()) {
        sg_flash('Login dulu sebelum mengganti password.', 'error');
        sg_redirect('login');
    }

    $currentPassword = (string) ($_POST['current_password'] ?? '');
    $newPassword = (string) ($_POST['new_password'] ?? '');
    $confirmPassword = (string) ($_POST['confirm_password'] ?? '');

    $user = sg_fetch_one('SELECT password_hash FROM users WHERE id = :id LIMIT 1', ['id' => $userId]);
    if (!$user || !password_verify($currentPassword, $user['password_hash'])) {
        sg_flash('Password lama tidak sesuai.', 'error');
        sg_redirect($returnPage);
    }

    if (strlen($newPassword) < 8) {
        sg_flash('Password baru minimal 8 karakter.', 'error');
        sg_redirect($returnPage);
    }

    if ($newPassword !== $confirmPassword) {
        sg_flash('Konfirmasi password baru tidak sama.', 'error');
        sg_redirect($returnPage);
    }

    if (password_verify($newPassword, $user['password_hash'])) {
        sg_flash('Password baru tidak boleh sama dengan password lama.', 'error');
        sg_redirect($returnPage);
    }

    $updated = sg_execute(
        'UPDATE users SET password_hash = :password_hash WHERE id = :id',
        [
            'password_hash' => password_hash($newPassword, PASSWORD_DEFAULT),
            'id' => $userId,
        ]
    );

    sg_flash($updated ? 'Password berhasil diperbarui.' : 'Password gagal diperbarui: ' . sg_db_error(), $updated ? 'success' : 'error');
    sg_redirect($returnPage);
}

function sg_handle_seller_profile_update(): void
{
    $sellerId = sg_current_user_id();
    if (!$sellerId || !sg_db()) {
        sg_flash('Login dulu sebelum mengubah profil.', 'error');
        sg_redirect('login');
    }
    sg_ensure_user_profile_schema();

    $fullName = trim((string) ($_POST['full_name'] ?? ''));
    $phone = trim((string) ($_POST['phone_number'] ?? ''));

    if ($fullName === '') {
        sg_flash('Nama seller wajib diisi.', 'error');
        sg_redirect('settings');
    }

    $photoPath = sg_upload_file('profile_photo', 'profiles', '', ['jpg', 'jpeg', 'png', 'webp'], 3 * 1024 * 1024);
    if (sg_upload_error()) {
        sg_flash('Upload foto profil gagal: ' . sg_upload_error(), 'error');
        sg_redirect('settings');
    }

    $params = [
        'full_name' => $fullName,
        'phone' => $phone ?: null,
        'id' => $sellerId,
    ];
    $photoSql = '';
    if ($photoPath !== '') {
        $photoSql = ', profile_photo_path = :profile_photo_path';
        $params['profile_photo_path'] = $photoPath;
    }

    $updated = sg_execute(
        'UPDATE users SET full_name = :full_name, phone_number = :phone' . $photoSql . ' WHERE id = :id',
        $params
    );

    sg_flash($updated ? 'Profil seller berhasil diperbarui.' : 'Profil seller gagal diperbarui: ' . sg_db_error(), $updated ? 'success' : 'error');
    sg_redirect('settings');
}

function sg_handle_register_passkey(): void
{
    $userId = sg_current_user_id();
    if (!$userId) {
        sg_flash('Login dulu sebelum mendaftarkan passkey.', 'error');
        sg_redirect('login');
    }

    $deviceName = trim((string) ($_POST['device_name'] ?? 'Browser Device'));
    $credentialId = 'local-demo-' . $userId . '-' . bin2hex(random_bytes(8));
    $created = sg_execute(
        'INSERT INTO passkeys (user_id, credential_id, public_key, device_name)
         VALUES (:user_id, :credential_id, :public_key, :device_name)',
        [
            'user_id' => $userId,
            'credential_id' => $credentialId,
            'public_key' => 'demo-public-key-' . $credentialId,
            'device_name' => $deviceName ?: 'Browser Device',
        ]
    );

    sg_flash($created ? 'Passkey berhasil dicatat ke database.' : 'Passkey gagal dibuat: ' . sg_db_error(), $created ? 'success' : 'error');
    sg_redirect(($_SESSION['role'] ?? '') === 'admin' ? 'admin_overview' : 'settings');
}

function sg_handle_admin_kyc_decision(): void
{
    $adminId = sg_current_user_id('admin');
    $kycId = (int) ($_POST['kyc_id'] ?? 0);
    $decision = $_POST['decision'] === 'approve' ? 'approved' : 'rejected';

    if (!$adminId || $kycId <= 0) {
        sg_flash('Data KYC tidak valid.', 'error');
        sg_redirect('admin_kyc');
    }

    $updated = sg_execute(
        'UPDATE kyc_verifications
         SET status = :status, reviewed_by = :admin_id, reviewed_at = NOW(), rejection_reason = :reason
         WHERE id = :id',
        [
            'status' => $decision,
            'admin_id' => $adminId,
            'reason' => $decision === 'rejected' ? 'Rejected from admin panel' : null,
            'id' => $kycId,
        ]
    );

    if ($updated && $decision === 'approved') {
        $kyc = sg_fetch_one('SELECT id, user_id FROM kyc_verifications WHERE id = :id', ['id' => $kycId]);
        if ($kyc) {
            sg_execute(
                'INSERT INTO seller_profiles (user_id, kyc_id)
                 VALUES (:user_id, :kyc_id)
                 ON DUPLICATE KEY UPDATE kyc_id = VALUES(kyc_id)',
                ['user_id' => $kyc['user_id'], 'kyc_id' => $kyc['id']]
            );
        }
    }

    if ($updated) {
        $kyc = sg_fetch_one('SELECT user_id FROM kyc_verifications WHERE id = :id', ['id' => $kycId]);
        if ($kyc) {
            $approved = $decision === 'approved';
            sg_execute(
                'INSERT INTO admin_audit_logs (admin_id, action, target_type, target_id, notes, ip_address)
                 VALUES (:admin_id, :action, "kyc", :target_id, :notes, :ip_address)',
                [
                    'admin_id' => $adminId,
                    'action' => $approved ? 'approve_kyc' : 'reject_kyc',
                    'target_id' => $kycId,
                    'notes' => ($approved ? 'Approved KYC submission ' : 'Rejected KYC submission ') . '#' . $kycId,
                    'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
                ]
            );
            sg_notify(
                (int) $kyc['user_id'],
                $approved ? 'kyc_approved' : 'kyc_rejected',
                $approved ? 'KYC Disetujui' : 'KYC Ditolak',
                $approved
                ? 'Verifikasi identitas kamu sudah disetujui. Fitur seller SafeGate sudah aktif.'
                : 'Verifikasi identitas kamu ditolak. Silakan cek data dan kirim ulang dokumen.',
                $kycId
            );
        }
    }

    sg_flash($updated ? 'Status KYC berhasil diperbarui.' : 'Gagal update KYC: ' . sg_db_error(), $updated ? 'success' : 'error');
    sg_redirect('admin_kyc');
}

function sg_handle_dispute_decision(): void
{
    $adminId = sg_current_user_id('admin');
    $disputeId = (int) ($_POST['dispute_id'] ?? 0);
    $decision = $_POST['decision'] === 'release' ? 'resolved_release' : 'resolved_refund';
    $resolution = $_POST['decision'] === 'release' ? 'release_seller' : 'refund_buyer';

    if (!$adminId || $disputeId <= 0) {
        sg_flash('Data dispute tidak valid.', 'error');
        sg_redirect('admin_disputes');
    }

    $updated = sg_execute(
        'UPDATE disputes
         SET status = :status, resolution = :resolution, handled_by_admin = :admin_id, resolved_at = NOW()
         WHERE id = :id',
        [
            'status' => $decision,
            'resolution' => $resolution,
            'admin_id' => $adminId,
            'id' => $disputeId,
        ]
    );

    if ($updated) {
        $dispute = sg_fetch_one(
            'SELECT d.transaction_id, d.buyer_id, d.seller_id, t.transaction_code
             FROM disputes d
             JOIN transactions t ON t.id = d.transaction_id
             WHERE d.id = :id',
            ['id' => $disputeId]
        );
        if ($dispute) {
            sg_execute(
                'UPDATE transactions SET escrow_status = :escrow_status WHERE id = :id',
                [
                    'escrow_status' => $resolution === 'release_seller' ? 'released' : 'refunded',
                    'id' => $dispute['transaction_id'],
                ]
            );

            $isRelease = $resolution === 'release_seller';
            $transaction = sg_fetch_one(
                'SELECT seller_earning, total_amount FROM transactions WHERE id = :id LIMIT 1',
                ['id' => $dispute['transaction_id']]
            );
            if ($transaction) {
                $ledgerAmount = $isRelease ? (int) $transaction['seller_earning'] : (int) $transaction['total_amount'];
                sg_execute(
                    'INSERT INTO escrow_ledger (transaction_id, user_id, type, amount, balance_after, notes)
                     VALUES (:transaction_id, :user_id, :type, :amount, :balance_after, :notes)',
                    [
                        'transaction_id' => $dispute['transaction_id'],
                        'user_id' => $isRelease ? $dispute['seller_id'] : $dispute['buyer_id'],
                        'type' => $isRelease ? 'release' : 'refund',
                        'amount' => $ledgerAmount,
                        'balance_after' => $ledgerAmount,
                        'notes' => $isRelease ? 'Dispute resolved: escrow released to seller.' : 'Dispute resolved: refund returned to buyer.',
                    ]
                );
            }
            sg_execute(
                'INSERT INTO admin_audit_logs (admin_id, action, target_type, target_id, notes, ip_address)
                 VALUES (:admin_id, :action, "dispute", :target_id, :notes, :ip_address)',
                [
                    'admin_id' => $adminId,
                    'action' => $isRelease ? 'resolve_dispute_release' : 'resolve_dispute_refund',
                    'target_id' => $disputeId,
                    'notes' => ($isRelease ? 'Resolved dispute with seller release for ' : 'Resolved dispute with buyer refund for ') . $dispute['transaction_code'],
                    'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
                ]
            );
            sg_notify(
                (int) $dispute['buyer_id'],
                $isRelease ? 'escrow_released' : 'dispute_opened',
                $isRelease ? 'Escrow Dilepas ke Seller' : 'Dana Dikembalikan',
                $isRelease
                ? 'Admin menyelesaikan dispute ' . $dispute['transaction_code'] . ' dan melepas dana ke seller.'
                : 'Admin menyelesaikan dispute ' . $dispute['transaction_code'] . ' dan refund diproses ke buyer.',
                (int) $dispute['transaction_id']
            );
            sg_notify(
                (int) $dispute['seller_id'],
                $isRelease ? 'escrow_released' : 'dispute_opened',
                $isRelease ? 'Escrow Berhasil Dilepas' : 'Dispute Berakhir Refund',
                $isRelease
                ? 'Dana transaksi ' . $dispute['transaction_code'] . ' sudah dilepas ke saldo seller.'
                : 'Transaksi ' . $dispute['transaction_code'] . ' diselesaikan dengan refund ke buyer.',
                (int) $dispute['transaction_id']
            );
        }
    }

    sg_flash($updated ? 'Keputusan dispute berhasil disimpan.' : 'Gagal update dispute: ' . sg_db_error(), $updated ? 'success' : 'error');
    sg_redirect('admin_disputes');
}

function sg_handle_admin_emergency_lock(): void
{
    $adminId = sg_current_user_id('admin');
    if (!$adminId) {
        sg_flash('Akses admin diperlukan.', 'error');
        sg_redirect('login');
    }

    $logged = sg_execute(
        'INSERT INTO admin_audit_logs (admin_id, action, target_type, target_id, notes, ip_address)
         VALUES (:admin_id, "emergency_lock", "system", 0, :notes, :ip_address)',
        [
            'admin_id' => $adminId,
            'notes' => 'Emergency lock requested from admin sidebar. Manual operational follow-up required.',
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
        ]
    );

    sg_notify_admins(
        'dispute_opened',
        'Emergency Lock Dicatat',
        'Admin menjalankan emergency lock. Cek audit log untuk tindak lanjut manual.',
        null
    );

    sg_flash($logged ? 'Emergency lock tercatat di audit log database.' : 'Emergency lock gagal dicatat: ' . sg_db_error(), $logged ? 'success' : 'error');
    sg_redirect('admin_overview');
}

function sg_handle_admin_settle_transaction(): void
{
    $adminId = sg_current_user_id('admin');
    $transactionId = (int) ($_POST['transaction_id'] ?? 0);
    $decision = $_POST['decision'] ?? '';

    if (!$adminId || $transactionId <= 0 || !in_array($decision, ['release', 'refund'], true)) {
        sg_flash('Aksi settlement transaksi tidak valid.', 'error');
        sg_redirect('admin_transactions');
    }

    $transaction = sg_fetch_one(
        'SELECT t.*, e.title
         FROM transactions t
         JOIN ticket_listings tl ON tl.id = t.listing_id
         JOIN events e ON e.id = tl.event_id
         WHERE t.id = :id
         LIMIT 1',
        ['id' => $transactionId]
    );

    if (!$transaction) {
        sg_flash('Transaksi tidak ditemukan.', 'error');
        sg_redirect('admin_transactions');
    }

    if ($transaction['payment_status'] !== 'paid' || !in_array($transaction['escrow_status'], ['holding', 'disputed'], true)) {
        sg_flash('Transaksi ini tidak bisa diselesaikan. Status payment/escrow sudah final atau belum paid.', 'error');
        sg_redirect('admin_transactions');
    }

    $isRelease = $decision === 'release';
    $updated = sg_execute(
        'UPDATE transactions
         SET escrow_status = :escrow_status,
             payment_status = :payment_status,
             escrow_released_at = NOW()
         WHERE id = :id AND payment_status = "paid" AND escrow_status IN ("holding", "disputed")',
        [
            'escrow_status' => $isRelease ? 'released' : 'refunded',
            'payment_status' => $isRelease ? 'paid' : 'refunded',
            'id' => $transactionId,
        ]
    );

    if (!$updated) {
        sg_flash('Settlement gagal: ' . sg_db_error(), 'error');
        sg_redirect('admin_transactions');
    }

    sg_execute(
        'INSERT INTO escrow_ledger (transaction_id, user_id, type, amount, balance_after, notes)
         VALUES (:transaction_id, :user_id, :type, :amount, :balance_after, :notes)',
        [
            'transaction_id' => $transactionId,
            'user_id' => $isRelease ? $transaction['seller_id'] : $transaction['buyer_id'],
            'type' => $isRelease ? 'release' : 'refund',
            'amount' => $isRelease ? $transaction['seller_earning'] : $transaction['total_amount'],
            'balance_after' => $isRelease ? $transaction['seller_earning'] : $transaction['total_amount'],
            'notes' => ($isRelease ? 'Escrow released by admin for ' : 'Refund processed by admin for ') . $transaction['transaction_code'],
        ]
    );

    sg_execute(
        'INSERT INTO admin_audit_logs (admin_id, action, target_type, target_id, notes, ip_address)
         VALUES (:admin_id, :action, "transaction", :target_id, :notes, :ip_address)',
        [
            'admin_id' => $adminId,
            'action' => $isRelease ? 'release_escrow' : 'refund_transaction',
            'target_id' => $transactionId,
            'notes' => ($isRelease ? 'Released escrow for ' : 'Refunded transaction ') . $transaction['transaction_code'],
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
        ]
    );

    sg_notify(
        (int) $transaction['buyer_id'],
        $isRelease ? 'escrow_released' : 'dispute_opened',
        $isRelease ? 'Escrow Selesai' : 'Refund Diproses',
        $isRelease
        ? 'Admin melepas escrow transaksi ' . $transaction['transaction_code'] . ' untuk ' . $transaction['title'] . '.'
        : 'Admin memproses refund transaksi ' . $transaction['transaction_code'] . ' untuk ' . $transaction['title'] . '.',
        $transactionId
    );
    sg_notify(
        (int) $transaction['seller_id'],
        $isRelease ? 'escrow_released' : 'dispute_opened',
        $isRelease ? 'Dana Escrow Dilepas' : 'Transaksi Direfund',
        $isRelease
        ? 'Dana seller untuk transaksi ' . $transaction['transaction_code'] . ' sudah dilepas.'
        : 'Transaksi ' . $transaction['transaction_code'] . ' direfund oleh admin.',
        $transactionId
    );

    sg_flash($isRelease ? 'Escrow berhasil dilepas ke seller.' : 'Refund berhasil diproses ke buyer.', 'success');
    sg_redirect('admin_transactions');
}

function sg_handle_signup(): void
{
    if (!sg_db()) {
        sg_flash('Database belum aktif. Nyalakan MySQL dan import safgate_db.sql dulu.', 'error');
        sg_redirect('signup');
    }

    $email = strtolower(trim((string) ($_POST['email'] ?? '')));
    $fullName = trim((string) ($_POST['full_name'] ?? ''));
    $phone = trim((string) ($_POST['phone_number'] ?? ''));
    $nik = preg_replace('/[^\d]/', '', (string) ($_POST['nik'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $passwordConfirm = (string) ($_POST['password_confirm'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $fullName === '' || strlen($password) < 6 || $password !== $passwordConfirm) {
        sg_flash('Data signup belum valid. Pastikan email benar dan password minimal 6 karakter.', 'error');
        sg_redirect('signup');
    }

    $existing = sg_fetch_one('SELECT id FROM users WHERE email = :email LIMIT 1', ['email' => $email]);
    if ($existing) {
        sg_flash('Email sudah terdaftar. Silakan login.', 'error');
        sg_redirect('login');
    }

    $created = sg_execute(
        'INSERT INTO users (full_name, email, phone_number, nik, password_hash, role)
         VALUES (:full_name, :email, :phone, :nik, :password_hash, "buyer")',
        [
            'full_name' => $fullName,
            'email' => $email,
            'phone' => $phone,
            'nik' => $nik ?: null,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
        ]
    );

    sg_flash($created ? 'Akun berhasil dibuat. Silakan login.' : 'Signup gagal: ' . sg_db_error(), $created ? 'success' : 'error');
    sg_redirect($created ? 'login' : 'signup');
}

function sg_handle_login(): void
{
    if (!sg_db()) {
        sg_flash('Database belum aktif. Nyalakan MySQL dan import safgate_db.sql dulu.', 'error');
        sg_redirect('login');
    }

    $email = strtolower(trim((string) ($_POST['email'] ?? '')));
    $password = (string) ($_POST['password'] ?? '');

    if (in_array($email, ['admin@safegate.local', 'seller@safegate.local'], true)) {
        sg_ensure_demo_user(strpos($email, 'admin@') === 0 ? 'admin' : 'seller');
    }

    $user = sg_fetch_one('SELECT id, password_hash, role, is_active FROM users WHERE email = :email LIMIT 1', [
        'email' => $email,
    ]);

    if (!$user || !(int) $user['is_active'] || !password_verify($password, $user['password_hash'])) {
        sg_flash('Email atau password salah.', 'error');
        sg_redirect('login');
    }

    $_SESSION['user_id'] = (int) $user['id'];
    $_SESSION['role'] = $user['role'];

    sg_redirect(sg_role_home($user['role']));
}

function sg_handle_checkout_payment(): void
{
    if (!sg_db()) {
        sg_flash('Database belum aktif. Nyalakan MySQL dan import safgate_db.sql dulu.', 'error');
        sg_redirect('penjualan');
    }

    // Ensure database columns are up-to-date
    sg_ensure_buyer_finance_schema();

    $listingId = (int) ($_POST['listing_id'] ?? 0);
    $method = $_POST['payment_method'] ?? 'bank_transfer';
    $listing = $listingId > 0 ? sg_get_listing_detail($listingId) : null;

    if (!$listing) {
        sg_flash('Listing tidak ditemukan atau belum berasal dari database.', 'error');
        sg_redirect('penjualan');
    }

    $buyerId = sg_current_user_id();
    if (!$buyerId) {
        sg_flash('Login dulu sebelum melakukan pembayaran.', 'error');
        sg_redirect('login');
    }

    if ((int) $listing['seller_id'] === (int) $buyerId) {
        sg_flash('Seller tidak bisa membeli listing miliknya sendiri.', 'error');
        sg_redirect('penjualan');
    }

    if (!in_array($listing['listing_status'], ['active', 'promoted'], true)) {
        sg_flash('Listing ini sudah tidak tersedia untuk dibeli.', 'error');
        sg_redirect_url('index.php?page=detail_tiket&listing_id=' . $listingId);
    }

    sg_process_expired_bid_deadlines($listingId);
    $winningBid = sg_fetch_one(
        'SELECT id, bidder_id FROM bids WHERE listing_id = :listing_id AND is_winning_bid = 1 ORDER BY bid_amount DESC LIMIT 1',
        ['listing_id' => $listingId]
    );

    if ($winningBid && (int) $winningBid['bidder_id'] !== $buyerId) {
        sg_flash('Saat ini hanya pemenang lelang yang bisa menyelesaikan pembayaran tiket ini.', 'error');
        sg_redirect_url('index.php?page=detail_tiket&listing_id=' . $listingId);
    }

    $basePrice = (int) ($listing['current_highest_bid'] ?: $listing['starting_bid']);
    $reservePrice = (int) ($listing['reserve_price'] ?? 0);
    if ($reservePrice > 0 && $basePrice < $reservePrice) {
        sg_flash('Pembayaran belum bisa diproses. Bid tertinggi harus mencapai reserve price ' . sg_rupiah($reservePrice) . ' dulu.', 'error');
        sg_redirect_url('index.php?page=detail_tiket&listing_id=' . $listingId);
    }

    $serviceFee = (int) round($basePrice * 0.05);
    $escrowInsurance = (int) round($basePrice * 0.11);
    $totalAmount = $basePrice + $serviceFee + $escrowInsurance;
    $sellerEarning = $basePrice - $serviceFee;
    $transactionCode = 'SG-TX-' . strtoupper(substr(uniqid(), -6));

    if ($method === 'usdc') {
        // USDC Wallet (Mock Crypto) - instant success logic as originally written
        $created = sg_execute(
            'INSERT INTO transactions
                (transaction_code, listing_id, buyer_id, seller_id, winning_bid_id, base_price, service_fee, escrow_insurance, total_amount, platform_revenue, seller_earning, payment_method, payment_status, escrow_status, paid_at)
             VALUES
                (:code, :listing_id, :buyer_id, :seller_id, :winning_bid_id, :base_price, :service_fee, :escrow_insurance, :total_amount, :platform_revenue, :seller_earning, :payment_method, "paid", "holding", NOW())',
            [
                'code' => $transactionCode,
                'listing_id' => $listingId,
                'buyer_id' => $buyerId,
                'seller_id' => $listing['seller_id'],
                'winning_bid_id' => $winningBid['id'] ?? null,
                'base_price' => $basePrice,
                'service_fee' => $serviceFee,
                'escrow_insurance' => $escrowInsurance,
                'total_amount' => $totalAmount,
                'platform_revenue' => $serviceFee,
                'seller_earning' => $sellerEarning,
                'payment_method' => $method,
            ]
        );

        if ($created) {
            $transaction = sg_fetch_one('SELECT id FROM transactions WHERE transaction_code = :code', ['code' => $transactionCode]);
            if ($transaction) {
                sg_execute(
                    'INSERT INTO escrow_ledger (transaction_id, user_id, type, amount, balance_after, notes)
                     VALUES (:transaction_id, :user_id, "lock", :amount, :balance_after, :notes)',
                    [
                        'transaction_id' => $transaction['id'],
                        'user_id' => $listing['seller_id'],
                        'amount' => $sellerEarning,
                        'balance_after' => $sellerEarning,
                        'notes' => 'Escrow locked from checkout ' . $transactionCode,
                    ]
                );
                if ($winningBid) {
                    sg_execute('UPDATE bids SET bid_status = "paid", deposit_status = "refunded" WHERE id = :id', ['id' => $winningBid['id']]);
                    sg_execute(
                        'UPDATE buyer_wallet_transactions
                         SET direction = "debit", status = "completed", description = :description
                         WHERE bid_id = :bid_id AND type = "bid_deposit_lock"',
                        ['bid_id' => $winningBid['id'], 'description' => 'Jaminan lelang selesai dipakai dan siap dikembalikan.']
                    );
                    $refundExists = sg_fetch_one(
                        'SELECT id FROM buyer_wallet_transactions
                         WHERE bid_id = :bid_id AND user_id = :user_id AND type = "bid_deposit_refund"
                         LIMIT 1',
                        ['bid_id' => $winningBid['id'], 'user_id' => $buyerId]
                    );
                    if (!$refundExists) {
                        sg_wallet_activity($buyerId, 'bid_deposit_refund', sg_bid_deposit_amount(), 'release', 'completed', 'Jaminan lelang dikembalikan setelah pembayaran selesai.', (int) $transaction['id'], (int) $winningBid['id']);
                    }
                }
            }

            sg_execute('UPDATE ticket_listings SET listing_status = "sold" WHERE id = :id', ['id' => $listingId]);
            sg_notify(
                $buyerId,
                'payment_success',
                'Pembayaran Berhasil',
                'Tiket ' . $listing['title'] . ' masuk ke akun kamu. Dana sekarang dikunci di escrow.',
                $transaction ? (int) $transaction['id'] : $listingId
            );
            sg_notify(
                (int) $listing['seller_id'],
                'payment_success',
                'Listing Terjual',
                'Listing ' . $listing['title'] . ' terjual. Dana ditahan sementara di escrow SafeGate.',
                $transaction ? (int) $transaction['id'] : $listingId
            );
            sg_flash('Pembayaran berhasil. Tiket masuk ke daftar tiket kamu dan dana dikunci di escrow.', 'success');
            sg_redirect('my_tickets');
        }
    } else {
        // MIDTRANS INTEGRATION for standard payment methods (bank_transfer, dana, gopay, ovo, etc.)
        $created = sg_execute(
            'INSERT INTO transactions
                (transaction_code, listing_id, buyer_id, seller_id, winning_bid_id, base_price, service_fee, escrow_insurance, total_amount, platform_revenue, seller_earning, payment_method, payment_status, escrow_status)
             VALUES
                (:code, :listing_id, :buyer_id, :seller_id, :winning_bid_id, :base_price, :service_fee, :escrow_insurance, :total_amount, :platform_revenue, :seller_earning, :payment_method, "pending", "holding")',
            [
                'code' => $transactionCode,
                'listing_id' => $listingId,
                'buyer_id' => $buyerId,
                'seller_id' => $listing['seller_id'],
                'winning_bid_id' => $winningBid['id'] ?? null,
                'base_price' => $basePrice,
                'service_fee' => $serviceFee,
                'escrow_insurance' => $escrowInsurance,
                'total_amount' => $totalAmount,
                'platform_revenue' => $serviceFee,
                'seller_earning' => $sellerEarning,
                'payment_method' => $method,
            ]
        );

        if ($created) {
            $transaction = sg_fetch_one('SELECT id FROM transactions WHERE transaction_code = :code', ['code' => $transactionCode]);
            if ($transaction) {
                // Fetch buyer details
                $buyer = sg_fetch_one('SELECT full_name, email, phone_number FROM users WHERE id = :id', ['id' => $buyerId]);
                
                // Initialize Midtrans Snap SDK
                \Midtrans\Config::$serverKey = SG_MIDTRANS_SERVER_KEY;
                \Midtrans\Config::$isProduction = SG_MIDTRANS_IS_PRODUCTION;
                \Midtrans\Config::$isSanitized = true;
                \Midtrans\Config::$is3ds = true;

                // Build params
                $params = [
                    'transaction_details' => [
                        'order_id' => $transactionCode,
                        'gross_amount' => $totalAmount,
                    ],
                    'customer_details' => [
                        'first_name' => $buyer['full_name'] ?? 'Buyer',
                        'email' => $buyer['email'] ?? '',
                        'phone' => $buyer['phone_number'] ?? '',
                    ],
                    'item_details' => [
                        [
                            'id' => 'ticket-' . $listingId,
                            'price' => $basePrice,
                            'quantity' => 1,
                            'name' => 'Tiket: ' . substr($listing['title'], 0, 40),
                        ],
                        [
                            'id' => 'service-fee',
                            'price' => $serviceFee,
                            'quantity' => 1,
                            'name' => 'Biaya Layanan',
                        ],
                        [
                            'id' => 'escrow-insurance',
                            'price' => $escrowInsurance,
                            'quantity' => 1,
                            'name' => 'Asuransi Escrow',
                        ]
                    ]
                ];

                try {
                    $snapToken = \Midtrans\Snap::getSnapToken($params);
                    // Update token in DB
                    sg_execute(
                        'UPDATE transactions SET midtrans_snap_token = :token WHERE id = :id',
                        ['token' => $snapToken, 'id' => $transaction['id']]
                    );
                    
                    // Redirect to pembayaran page with snap token to trigger popup automatically
                    sg_redirect_url('index.php?page=pembayaran&listing_id=' . $listingId . '&snap_token=' . $snapToken);
                } catch (\Throwable $e) {
                    // Delete the pending transaction to let them retry
                    sg_execute('DELETE FROM transactions WHERE id = :id', ['id' => $transaction['id']]);
                    sg_flash('Midtrans Error: ' . $e->getMessage(), 'error');
                    sg_redirect_url('index.php?page=pembayaran&listing_id=' . $listingId);
                }
            }
        } else {
            sg_flash('Gagal membuat transaksi: ' . sg_db_error(), 'error');
            sg_redirect_url('index.php?page=pembayaran&listing_id=' . $listingId);
        }
    }

    sg_flash('Pembayaran gagal: ' . sg_db_error(), 'error');
    sg_redirect('pembayaran');
}

function sg_handle_submit_bid(): void
{
    sg_ensure_buyer_finance_schema();

    if (!sg_db()) {
        sg_flash('Database belum aktif. Nyalakan MySQL dulu.', 'error');
        sg_redirect('penjualan');
    }

    $buyerId = sg_current_user_id();
    $listingId = (int) ($_POST['listing_id'] ?? 0);
    $bidAmount = (int) preg_replace('/[^\d]/', '', (string) ($_POST['bid_amount'] ?? '0'));
    $listing = $listingId > 0 ? sg_get_listing_detail($listingId) : null;

    if (!$buyerId) {
        sg_flash('Login dulu sebelum mengirim tawaran.', 'error');
        sg_redirect('login');
    }

    if (!$listing) {
        sg_flash('Listing tidak ditemukan.', 'error');
        sg_redirect('penjualan');
    }

    if (!in_array($listing['listing_status'], ['active', 'promoted'], true)) {
        sg_flash('Listing tidak tersedia untuk ditawar.', 'error');
        sg_redirect_url('index.php?page=detail_tiket&listing_id=' . $listingId);
    }

    if ((int) $listing['seller_id'] === $buyerId) {
        sg_flash('Seller tidak bisa menawar listing miliknya sendiri.', 'error');
        sg_redirect_url('index.php?page=detail_tiket&listing_id=' . $listingId);
    }

    $minimumBid = max((int) $listing['starting_bid'], (int) ($listing['current_highest_bid'] ?? 0)) + 10000;
    if ($bidAmount < $minimumBid) {
        sg_flash('Tawaran minimal ' . sg_rupiah($minimumBid) . '.', 'error');
        sg_redirect_url('index.php?page=detail_tiket&listing_id=' . $listingId);
    }

    $previousWinner = sg_fetch_one(
        'SELECT bidder_id FROM bids WHERE listing_id = :listing_id AND is_winning_bid = 1 ORDER BY bid_amount DESC LIMIT 1',
        ['listing_id' => $listingId]
    );

    if ($previousWinner && (int) $previousWinner['bidder_id'] === $buyerId) {
        sg_flash('Tawaranmu masih yang tertinggi. Tunggu sampai ada pembeli lain yang menawar di atasmu.', 'error');
        sg_redirect_url('index.php?page=detail_tiket&listing_id=' . $listingId);
    }

    $deposit = sg_bid_deposit_amount();
    $hasLockedDeposit = sg_fetch_one(
        'SELECT id FROM bids WHERE listing_id = :listing_id AND bidder_id = :bidder_id AND deposit_status = "locked" LIMIT 1',
        ['listing_id' => $listingId, 'bidder_id' => $buyerId]
    );

    $requiredDepositAmount = $hasLockedDeposit ? 0 : $deposit;

    if ($requiredDepositAmount > 0) {
        $wallet = sg_get_buyer_wallet_summary($buyerId);
        if ($wallet['available'] < $requiredDepositAmount) {
            sg_flash('Saldo jaminan belum cukup. Top up minimal ' . sg_rupiah($requiredDepositAmount) . ' di Wallet & Escrow pembeli untuk mengikuti lelang.', 'error');
            sg_redirect('buyer_wallet');
        }
    }

    sg_execute('UPDATE bids SET is_winning_bid = 0, bid_status = "outbid" WHERE listing_id = :listing_id AND is_winning_bid = 1', [
        'listing_id' => $listingId,
    ]);

    $created = sg_execute(
        'INSERT INTO bids (listing_id, bidder_id, bid_amount, deposit_amount, deposit_status, payment_deadline_at, bid_status, is_winning_bid, ip_address)
         VALUES (:listing_id, :bidder_id, :bid_amount, :deposit_amount, "locked", DATE_ADD(NOW(), INTERVAL 2 HOUR), "winner_pending_payment", 1, :ip_address)',
        [
            'listing_id' => $listingId,
            'bidder_id' => $buyerId,
            'bid_amount' => $bidAmount,
            'deposit_amount' => $requiredDepositAmount,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
        ]
    );

    if ($created) {
        if ($requiredDepositAmount > 0) {
            $bidRow = sg_fetch_one(
                'SELECT id FROM bids WHERE listing_id = :listing_id AND bidder_id = :bidder_id ORDER BY id DESC LIMIT 1',
                ['listing_id' => $listingId, 'bidder_id' => $buyerId]
            );
            if ($bidRow) {
                sg_wallet_activity($buyerId, 'bid_deposit_lock', $requiredDepositAmount, 'hold', 'locked', 'Jaminan lelang dikunci untuk ' . $listing['title'] . '.', null, (int) $bidRow['id']);
            }
        }

        sg_execute('UPDATE ticket_listings SET current_highest_bid = :amount WHERE id = :id', [
            'amount' => $bidAmount,
            'id' => $listingId,
        ]);

        sg_notify(
            $buyerId,
            'bid_placed',
            'Tawaran Terkirim',
            'Tawaran kamu untuk ' . $listing['title'] . ' sekarang menjadi bid tertinggi sementara.',
            $listingId
        );
        sg_notify(
            $buyerId,
            'auction_won',
            'Kamu Memimpin Lelang',
            'Kamu sedang menjadi pemenang sementara untuk ' . $listing['title'] . '. Jika tetap menang, selesaikan pembayaran dalam 2 jam.',
            $listingId
        );
        sg_notify(
            (int) $listing['seller_id'],
            'bid_placed',
            'Bid Baru Masuk',
            'Ada tawaran baru sebesar ' . sg_rupiah($bidAmount) . ' untuk listing ' . $listing['title'] . '.',
            $listingId
        );

        if ($previousWinner && (int) $previousWinner['bidder_id'] !== $buyerId) {
            sg_notify(
                (int) $previousWinner['bidder_id'],
                'auction_lost',
                'Bid Kamu Tersalip',
                'Ada tawaran lebih tinggi untuk ' . $listing['title'] . '. Kamu masih bisa menaikkan bid.',
                $listingId
            );
        }
    }

    sg_flash($created ? 'Tawaran berhasil dikirim dan menjadi bid tertinggi sementara.' : 'Tawaran gagal: ' . sg_db_error(), $created ? 'success' : 'error');
    sg_redirect_url('index.php?page=detail_tiket&listing_id=' . $listingId);
}

function sg_handle_open_dispute(): void
{
    if (!sg_db()) {
        sg_flash('Database belum aktif. Nyalakan MySQL dulu.', 'error');
        sg_redirect('my_tickets');
    }

    $buyerId = sg_current_user_id();
    $transactionId = (int) ($_POST['transaction_id'] ?? 0);
    $claim = trim((string) ($_POST['buyer_claim'] ?? ''));

    if (!$buyerId) {
        sg_flash('Login dulu sebelum membuka dispute.', 'error');
        sg_redirect('login');
    }

    if ($transactionId <= 0 || strlen($claim) < 12) {
        sg_flash('Pilih transaksi dan tulis alasan dispute minimal 12 karakter.', 'error');
        sg_redirect('my_tickets');
    }

    $transaction = sg_fetch_one(
        'SELECT t.id, t.buyer_id, t.seller_id, t.escrow_status, t.transaction_code, e.title
         FROM transactions t
         JOIN ticket_listings tl ON tl.id = t.listing_id
         JOIN events e ON e.id = tl.event_id
         WHERE t.id = :id AND t.buyer_id = :buyer_id
         LIMIT 1',
        ['id' => $transactionId, 'buyer_id' => $buyerId]
    );

    if (!$transaction) {
        sg_flash('Transaksi tidak ditemukan di akun kamu.', 'error');
        sg_redirect('my_tickets');
    }

    if (in_array($transaction['escrow_status'], ['released', 'refunded'], true)) {
        sg_flash('Escrow transaksi ini sudah selesai, dispute tidak bisa dibuka.', 'error');
        sg_redirect('my_tickets');
    }

    $existing = sg_fetch_one(
        'SELECT id FROM disputes WHERE transaction_id = :transaction_id AND status IN ("open", "under_review") LIMIT 1',
        ['transaction_id' => $transactionId]
    );

    if ($existing) {
        sg_flash('Dispute untuk transaksi ini sudah aktif dan sedang ditinjau admin.', 'error');
        sg_redirect('my_tickets');
    }

    $created = sg_execute(
        'INSERT INTO disputes (transaction_id, buyer_id, seller_id, buyer_claim, ip_origin, status)
         VALUES (:transaction_id, :buyer_id, :seller_id, :claim, :ip_origin, "open")',
        [
            'transaction_id' => $transactionId,
            'buyer_id' => $buyerId,
            'seller_id' => $transaction['seller_id'],
            'claim' => $claim,
            'ip_origin' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
        ]
    );

    if ($created) {
        $disputeRow = sg_fetch_one(
            'SELECT id FROM disputes WHERE transaction_id = :transaction_id AND buyer_id = :buyer_id ORDER BY id DESC LIMIT 1',
            ['transaction_id' => $transactionId, 'buyer_id' => $buyerId]
        );
        if ($disputeRow) {
            sg_execute(
                'INSERT INTO dispute_messages (dispute_id, sender_id, sender_role, message)
                 VALUES (:dispute_id, :sender_id, "buyer", :message)',
                [
                    'dispute_id' => $disputeRow['id'],
                    'sender_id' => $buyerId,
                    'message' => $claim,
                ]
            );
        }

        sg_execute('UPDATE transactions SET escrow_status = "disputed" WHERE id = :id', ['id' => $transactionId]);
        sg_notify(
            $buyerId,
            'dispute_opened',
            'Dispute Dibuka',
            'Dispute untuk transaksi ' . $transaction['transaction_code'] . ' sedang menunggu tinjauan admin.',
            $transactionId
        );
        sg_notify(
            (int) $transaction['seller_id'],
            'dispute_opened',
            'Dispute Baru',
            'Buyer membuka dispute untuk ' . $transaction['title'] . '. Dana escrow sementara dibekukan.',
            $transactionId
        );
        sg_notify_admins(
            'dispute_opened',
            'Dispute Baru Dibuka',
            'Transaksi ' . $transaction['transaction_code'] . ' membutuhkan investigasi admin.',
            $transactionId
        );
    }

    sg_flash($created ? 'Dispute berhasil dibuka. Admin akan meninjau escrow transaksi ini.' : 'Dispute gagal dibuat: ' . sg_db_error(), $created ? 'success' : 'error');
    sg_redirect('my_tickets');
}

function sg_handle_buyer_confirm_ticket(): void
{
    $buyerId = sg_current_user_id();
    $transactionId = (int) ($_POST['transaction_id'] ?? 0);

    if (!$buyerId || $transactionId <= 0) {
        sg_flash('Login dan pilih tiket valid dulu.', 'error');
        sg_redirect('my_tickets');
    }

    $transaction = sg_get_buyer_ticket_for_verification($transactionId, $buyerId);
    if (!$transaction) {
        sg_flash('Tiket tidak ditemukan di akun kamu.', 'error');
        sg_redirect('my_tickets');
    }

    if (($transaction['buyer_ticket_status'] ?? '') === 'confirmed_used') {
        sg_flash('Tiket ini sudah pernah dikonfirmasi valid.', 'success');
        sg_redirect_url('index.php?page=ticket_verify&transaction_id=' . $transactionId);
    }

    if (($transaction['buyer_ticket_status'] ?? '') === 'reported_issue') {
        sg_flash('Tiket ini sudah dilaporkan bermasalah dan sedang ditinjau.', 'error');
        sg_redirect_url('index.php?page=ticket_verify&transaction_id=' . $transactionId);
    }

    if (($transaction['payment_status'] ?? '') !== 'paid' || !in_array($transaction['escrow_status'], ['holding', 'disputed', 'released'], true)) {
        sg_flash('Tiket ini tidak bisa dikonfirmasi.', 'error');
        sg_redirect('my_tickets');
    }

    $updated = sg_execute(
        'UPDATE transactions
         SET buyer_ticket_status = "confirmed_used",
             escrow_status = "released",
             buyer_confirmed_at = NOW(),
             escrow_released_at = COALESCE(escrow_released_at, NOW())
         WHERE id = :id AND buyer_id = :buyer_id',
        ['id' => $transactionId, 'buyer_id' => $buyerId]
    );

    if ($updated && $transaction['escrow_status'] !== 'released') {
        sg_execute(
            'INSERT INTO escrow_ledger (transaction_id, user_id, type, amount, balance_after, notes)
             VALUES (:transaction_id, :user_id, "release", :amount, :balance_after, :notes)',
            [
                'transaction_id' => $transactionId,
                'user_id' => $transaction['seller_id'],
                'amount' => $transaction['seller_earning'],
                'balance_after' => $transaction['seller_earning'],
                'notes' => 'Buyer confirmed ticket usable for ' . $transaction['transaction_code'],
            ]
        );
        sg_notify((int) $transaction['seller_id'], 'escrow_released', 'Tiket Dikonfirmasi Buyer', 'Buyer mengonfirmasi tiket ' . $transaction['title'] . ' valid. Escrow dilepas ke saldo seller.', $transactionId);
    }

    sg_flash($updated ? 'Tiket dikonfirmasi valid.' . ($transaction['escrow_status'] === 'released' ? '' : ' Escrow dilepas ke seller.') : 'Konfirmasi gagal: ' . sg_db_error(), $updated ? 'success' : 'error');
    sg_redirect_url('index.php?page=ticket_verify&transaction_id=' . $transactionId);
}

function sg_handle_buyer_report_ticket(): void
{
    $buyerId = sg_current_user_id();
    $transactionId = (int) ($_POST['transaction_id'] ?? 0);
    $claim = trim((string) ($_POST['buyer_claim'] ?? ''));

    if (!$buyerId || $transactionId <= 0 || strlen($claim) < 12) {
        sg_flash('Tulis alasan pelaporan minimal 12 karakter.', 'error');
        sg_redirect('my_tickets');
    }

    sg_execute(
        'UPDATE transactions
         SET buyer_ticket_status = "reported_issue", escrow_status = "disputed", buyer_reported_at = NOW()
         WHERE id = :id AND buyer_id = :buyer_id AND escrow_status IN ("holding", "disputed")',
        ['id' => $transactionId, 'buyer_id' => $buyerId]
    );

    $_POST['sg_action'] = 'open_dispute';
    sg_handle_open_dispute();
}

function sg_handle_dispute_message(): void
{
    $userId = sg_current_user_id();
    $role = $_SESSION['role'] ?? '';
    $disputeId = (int) ($_POST['dispute_id'] ?? 0);
    $transactionCode = trim((string) ($_POST['transaction_code'] ?? ''));
    $message = trim((string) ($_POST['message'] ?? ''));

    if (!$userId || $disputeId <= 0 || strlen($message) < 3) {
        sg_flash('Pesan dispute tidak valid.', 'error');
        sg_redirect_url('index.php?page=transaction_detail&code=' . urlencode($transactionCode));
    }

    $dispute = sg_fetch_one(
        'SELECT d.*, t.transaction_code
         FROM disputes d
         JOIN transactions t ON t.id = d.transaction_id
         WHERE d.id = :id
         LIMIT 1',
        ['id' => $disputeId]
    );

    if (!$dispute || $dispute['transaction_code'] !== $transactionCode) {
        sg_flash('Dispute tidak ditemukan.', 'error');
        sg_redirect_url('index.php?page=transaction_detail&code=' . urlencode($transactionCode));
    }

    $canMessage = $role === 'admin' || in_array($userId, [(int) $dispute['buyer_id'], (int) $dispute['seller_id']], true);
    if (!$canMessage) {
        sg_flash('Akun kamu tidak punya akses ke dispute ini.', 'error');
        sg_redirect_url('index.php?page=transaction_detail&code=' . urlencode($transactionCode));
    }

    $created = sg_execute(
        'INSERT INTO dispute_messages (dispute_id, sender_id, sender_role, message)
         VALUES (:dispute_id, :sender_id, :sender_role, :message)',
        [
            'dispute_id' => $disputeId,
            'sender_id' => $userId,
            'sender_role' => $role,
            'message' => $message,
        ]
    );

    if ($created && $dispute['status'] === 'open') {
        sg_execute('UPDATE disputes SET status = "under_review" WHERE id = :id', ['id' => $disputeId]);
    }

    if ($created && $role === 'seller' && trim((string) ($dispute['seller_defense'] ?? '')) === '') {
        sg_execute(
            'UPDATE disputes SET seller_defense = :message WHERE id = :id',
            ['message' => $message, 'id' => $disputeId]
        );
    }

    if ($created && $role === 'admin') {
        sg_execute(
            'UPDATE disputes SET admin_notes = :message, handled_by_admin = :admin_id WHERE id = :id',
            ['message' => $message, 'admin_id' => $userId, 'id' => $disputeId]
        );
    }

    if ($created) {
        foreach ([(int) $dispute['buyer_id'], (int) $dispute['seller_id']] as $targetUserId) {
            if ($targetUserId !== $userId) {
                sg_notify($targetUserId, 'dispute_opened', 'Pesan Dispute Baru', 'Ada balasan baru untuk transaksi ' . $transactionCode . '.', (int) $dispute['transaction_id']);
            }
        }
        if ($role !== 'admin') {
            sg_notify_admins('dispute_opened', 'Pesan Dispute Baru', 'Ada pesan baru pada dispute transaksi ' . $transactionCode . '.', (int) $dispute['transaction_id']);
        }
    }

    sg_flash($created ? 'Pesan dispute berhasil dikirim.' : 'Pesan dispute gagal dikirim: ' . sg_db_error(), $created ? 'success' : 'error');
    sg_redirect_url('index.php?page=transaction_detail&code=' . urlencode($transactionCode));
}

function sg_handle_listing_status(): void
{
    $sellerId = sg_current_user_id();
    $listingId = (int) ($_POST['listing_id'] ?? 0);
    $status = $_POST['listing_status'] ?? '';
    $allowed = ['active', 'paused', 'cancelled', 'promoted'];

    if (!$sellerId) {
        sg_flash('Login dulu untuk mengelola listing.', 'error');
        sg_redirect('login');
    }

    if ($listingId <= 0 || !in_array($status, $allowed, true)) {
        sg_flash('Aksi listing tidak valid.', 'error');
        sg_redirect('active_listings');
    }

    $listing = sg_fetch_one(
        'SELECT id, listing_status FROM ticket_listings WHERE id = :id AND seller_id = :seller_id LIMIT 1',
        ['id' => $listingId, 'seller_id' => $sellerId]
    );

    if (!$listing || $listing['listing_status'] === 'sold') {
        sg_flash('Listing tidak ditemukan atau sudah terjual.', 'error');
        sg_redirect('active_listings');
    }

    $updated = sg_execute(
        'UPDATE ticket_listings SET listing_status = :status, is_promoted = :promoted WHERE id = :id AND seller_id = :seller_id',
        [
            'status' => $status,
            'promoted' => $status === 'promoted' ? 1 : 0,
            'id' => $listingId,
            'seller_id' => $sellerId,
        ]
    );

    sg_flash($updated ? 'Status listing berhasil diperbarui.' : 'Gagal update listing: ' . sg_db_error(), $updated ? 'success' : 'error');
    sg_redirect('active_listings');
}

function sg_handle_logout(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }

    session_destroy();
    header('Location: index.php?page=home');
    exit;
}

function sg_handle_mark_notifications_read(): void
{
    $userId = sg_current_user_id();
    if ($userId) {
        sg_execute('UPDATE notifications SET is_read = 1 WHERE user_id = :user_id', [
            'user_id' => $userId,
        ]);
    }

    sg_redirect(sg_role_home());
}

function sg_handle_export_transactions(): void
{
    if (($_SESSION['role'] ?? '') !== 'admin') {
        http_response_code(403);
        echo 'Forbidden';
        exit;
    }

    $rows = sg_get_admin_transactions([
        'search' => $_GET['search'] ?? '',
        'payment_status' => $_GET['payment_status'] ?? 'all',
        'escrow_status' => $_GET['escrow_status'] ?? 'all',
        'date' => $_GET['date'] ?? '',
    ]);

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=safegate-transactions.csv');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['TX Code', 'Buyer', 'Seller', 'Event', 'Total Amount', 'Platform Fee', 'Payment Status', 'Escrow Status', 'Created At']);
    foreach ($rows as $row) {
        fputcsv($output, [
            $row['transaction_code'],
            $row['buyer_email'],
            $row['seller_name'],
            $row['event_title'],
            $row['total_amount'],
            $row['platform_revenue'],
            $row['payment_status'],
            $row['escrow_status'],
            $row['created_at'],
        ]);
    }
    fclose($output);
    exit;
}

function sg_handle_export_seller_transactions(): void
{
    $sellerId = sg_current_user_id();
    if (!$sellerId) {
        http_response_code(403);
        echo 'Forbidden';
        exit;
    }

    $ledger = sg_get_seller_transactions($sellerId, [
        'q' => $_GET['q'] ?? '',
        'date_range' => $_GET['date_range'] ?? 'Last 30 Days',
        'status' => $_GET['status'] ?? 'All Status',
    ]);

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=safegate-seller-transactions.csv');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Transaction Code', 'Event', 'Date', 'Type', 'Amount', 'Status', 'Note']);
    foreach ($ledger['transactions'] as $row) {
        fputcsv($output, [
            $row['id'],
            $row['title'],
            $row['date'] . ' ' . $row['time'],
            $row['type'],
            $row['amount'],
            $row['status'],
            $row['note'],
        ]);
    }
    fclose($output);
    exit;
}

if (($_GET['sg_action'] ?? '') === 'logout') {
    sg_handle_logout();
}

if (($_GET['sg_action'] ?? '') === 'mark_notifications_read') {
    sg_handle_mark_notifications_read();
}

if (($_GET['sg_action'] ?? '') === 'export_transactions') {
    sg_handle_export_transactions();
}

if (($_GET['sg_action'] ?? '') === 'export_seller_transactions') {
    sg_handle_export_seller_transactions();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['sg_action'] ?? '';

    if ($action === 'create_listing') {
        sg_handle_create_listing();
    } elseif ($action === 'withdrawal') {
        sg_handle_withdrawal();
    } elseif ($action === 'buyer_wallet_topup') {
        sg_handle_buyer_wallet_topup();
    } elseif ($action === 'buyer_wallet_withdraw') {
        sg_handle_buyer_wallet_withdraw();
    } elseif ($action === 'kyc_submit') {
        sg_handle_kyc_submit();
    } elseif ($action === 'buyer_profile_update') {
        sg_handle_buyer_profile_update();
    } elseif ($action === 'change_password') {
        sg_handle_change_password();
    } elseif ($action === 'seller_profile_update') {
        sg_handle_seller_profile_update();
    } elseif ($action === 'register_passkey') {
        sg_handle_register_passkey();
    } elseif ($action === 'admin_kyc_decision') {
        sg_handle_admin_kyc_decision();
    } elseif ($action === 'admin_emergency_lock') {
        sg_handle_admin_emergency_lock();
    } elseif ($action === 'dispute_decision') {
        sg_handle_dispute_decision();
    } elseif ($action === 'admin_settle_transaction') {
        sg_handle_admin_settle_transaction();
    } elseif ($action === 'signup') {
        sg_handle_signup();
    } elseif ($action === 'login') {
        sg_handle_login();
    } elseif ($action === 'checkout_payment') {
        sg_handle_checkout_payment();
    } elseif ($action === 'submit_bid') {
        sg_handle_submit_bid();
    } elseif ($action === 'open_dispute') {
        sg_handle_open_dispute();
    } elseif ($action === 'buyer_confirm_ticket') {
        sg_handle_buyer_confirm_ticket();
    } elseif ($action === 'buyer_report_ticket') {
        sg_handle_buyer_report_ticket();
    } elseif ($action === 'dispute_message') {
        sg_handle_dispute_message();
    } elseif ($action === 'listing_status') {
        sg_handle_listing_status();
    }
}
