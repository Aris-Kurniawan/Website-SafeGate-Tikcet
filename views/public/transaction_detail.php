<?php
require_once __DIR__ . '/../../core/safegate_repository.php';

$code = trim((string) ($_GET['code'] ?? ''));
$transaction = $code !== '' ? sg_get_transaction_detail($code) : null;
$page_title = $transaction ? 'Transaction ' . $transaction['transaction_code'] . ' - SafeGate' : 'Transaction Detail - SafeGate';
$flash = sg_flash();

if ($transaction && ($_SESSION['role'] ?? '') !== 'admin') {
    $userId = (int) ($_SESSION['user_id'] ?? 0);
    if ($userId <= 0 || !in_array($userId, [(int) $transaction['buyer_id'], (int) $transaction['seller_id']], true)) {
        http_response_code(403);
        $transaction = null;
    }
}

$ledger = $transaction ? sg_get_transaction_ledger((int) $transaction['id']) : [];
$disputes = $transaction ? sg_get_transaction_disputes((int) $transaction['id']) : [];
$role = $_SESSION['role'] ?? '';
$currentUserId = (int) ($_SESSION['user_id'] ?? 0);
$ledgerLabels = [
    'lock' => 'Dana Dikunci',
    'release' => 'Dana Dilepas',
    'refund' => 'Refund Buyer',
    'fee_deduct' => 'Fee SafeGate',
];
$statusText = [
    'pending' => 'Menunggu',
    'paid' => 'Terbayar',
    'failed' => 'Gagal',
    'refunded' => 'Refund',
    'holding' => 'Escrow Aktif',
    'released' => 'Dana Dilepas',
    'disputed' => 'Dispute Aktif',
    'open' => 'Open',
    'under_review' => 'Ditinjau',
    'resolved_refund' => 'Selesai Refund',
    'resolved_release' => 'Selesai Release',
    'closed' => 'Ditutup',
];

ob_start();
?>

<style>
    .sg-transaction-detail-page {
        max-width: 1120px;
        padding: 3.25rem 1.5rem 5rem;
    }

    .sg-transaction-detail-card {
        background: linear-gradient(145deg, rgba(18, 22, 31, .96), rgba(9, 13, 20, .96));
        border: 1px solid rgba(122, 153, 197, .2);
        box-shadow: 0 26px 80px rgba(0, 0, 0, .35);
    }

    .sg-transaction-stat {
        min-height: 116px;
        background: rgba(2, 10, 22, .52);
        border: 1px solid rgba(122, 153, 197, .16);
    }

    .sg-ledger-list,
    .sg-dispute-box {
        background: rgba(2, 10, 22, .45);
        border: 1px solid rgba(122, 153, 197, .16);
    }

    .sg-ledger-item {
        position: relative;
        display: grid;
        grid-template-columns: 34px 1fr auto;
        gap: 1rem;
        padding: 1.05rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, .07);
    }

    .sg-ledger-item:last-child {
        border-bottom: 0;
    }

    .sg-ledger-dot {
        width: 34px;
        height: 34px;
        display: grid;
        place-items: center;
        color: var(--safegate-neon);
        border-radius: 999px;
        background: rgba(217, 255, 0, .08);
        border: 1px solid rgba(217, 255, 0, .25);
    }

    .sg-dispute-message {
        max-width: 78%;
        background: rgba(255, 255, 255, .045);
        border: 1px solid rgba(255, 255, 255, .08);
    }

    .sg-dispute-message.is-mine {
        margin-left: auto;
        background: rgba(217, 255, 0, .08);
        border-color: rgba(217, 255, 0, .2);
    }

    .sg-dispute-form textarea {
        min-height: 104px;
        color: #fff;
        background: rgba(0, 8, 18, .76);
        border: 1px solid rgba(122, 153, 197, .24);
    }

    .sg-dispute-form textarea:focus {
        color: #fff;
        background: rgba(0, 8, 18, .9);
        border-color: rgba(217, 255, 0, .55);
        box-shadow: 0 0 0 .2rem rgba(217, 255, 0, .08);
    }

    @media (max-width: 767px) {
        .sg-ledger-item {
            grid-template-columns: 34px 1fr;
        }

        .sg-ledger-amount {
            grid-column: 2;
            text-align: left !important;
        }

        .sg-dispute-message {
            max-width: 100%;
        }
    }
</style>

<section class="sg-transaction-detail-page container mx-auto">
    <a href="javascript:history.back()" class="text-safegate-text-sec hover-neon text-decoration-none fw-bold d-inline-flex align-items-center gap-2 mb-4">
        <iconify-icon icon="ph:arrow-left"></iconify-icon> Kembali
    </a>

    <?php if ($flash): ?>
        <div class="rounded-4 p-3 mb-4 fw-semibold" style="background: <?= ($flash['type'] ?? 'success') === 'error' ? 'rgba(255,85,85,.08)' : 'rgba(217,255,0,.08)' ?>; border: 1px solid <?= ($flash['type'] ?? 'success') === 'error' ? 'rgba(255,85,85,.22)' : 'rgba(217,255,0,.18)' ?>; color: <?= ($flash['type'] ?? 'success') === 'error' ? '#ff6868' : 'var(--safegate-neon)' ?>;">
            <?= sg_h($flash['message']) ?>
        </div>
    <?php endif; ?>

    <?php if (!$transaction): ?>
        <div class="sg-glass rounded-4 p-5 text-center">
            <h1 class="h3 fw-bold text-white mb-2">Transaksi Tidak Ditemukan</h1>
            <p class="text-safegate-text-sec mb-0">Kode transaksi tidak valid atau akun kamu tidak punya akses.</p>
        </div>
    <?php else: ?>
        <div class="sg-transaction-detail-card rounded-4 p-4 p-md-5">
            <div class="d-flex flex-column flex-md-row justify-content-between gap-4 mb-5">
                <div>
                    <p class="text-safegate-neon fw-bold text-uppercase mb-2" style="letter-spacing:.12em; font-size:.75rem;">SafeGate Ledger</p>
                    <h1 class="display-6 fw-bold text-white mb-2"><?= sg_h($transaction['transaction_code']) ?></h1>
                    <p class="text-safegate-text-sec mb-1"><?= sg_h($transaction['title']) ?></p>
                    <p class="text-safegate-text-sec mb-0"><?= sg_h($transaction['venue']) ?>, <?= sg_h($transaction['city']) ?> · <?= date('d M Y', strtotime($transaction['event_date'])) ?></p>
                </div>
                <div class="text-md-end">
                    <span class="badge rounded-pill bg-safegate-neon text-black fw-bold px-3 py-2"><?= sg_h($statusText[$transaction['payment_status']] ?? strtoupper($transaction['payment_status'])) ?></span>
                    <span class="badge rounded-pill ms-2 px-3 py-2" style="background: rgba(122,153,197,.14); color: #d7e2f4; border: 1px solid rgba(122,153,197,.24);"><?= sg_h($statusText[$transaction['escrow_status']] ?? ucwords($transaction['escrow_status'])) ?></span>
                    <p class="text-safegate-text-sec mt-3 mb-0"><?= date('d M Y, H:i', strtotime($transaction['created_at'])) ?></p>
                </div>
            </div>

            <div class="row g-4 mb-5">
                <div class="col-12 col-md-4">
                    <div class="sg-transaction-stat rounded-4 p-4">
                        <small class="text-safegate-text-sec fw-bold text-uppercase d-block mb-2" style="letter-spacing:.1em;">Base Price</small>
                        <strong class="fs-4"><?= sg_rupiah($transaction['base_price']) ?></strong>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="sg-transaction-stat rounded-4 p-4">
                        <small class="text-safegate-text-sec fw-bold text-uppercase d-block mb-2" style="letter-spacing:.1em;">Service Fee</small>
                        <strong class="fs-4"><?= sg_rupiah($transaction['service_fee']) ?></strong>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="sg-transaction-stat rounded-4 p-4">
                        <small class="text-safegate-text-sec fw-bold text-uppercase d-block mb-2" style="letter-spacing:.1em;">Total Paid</small>
                        <strong class="fs-4 text-safegate-neon"><?= sg_rupiah($transaction['total_amount']) ?></strong>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-5">
                <div class="col-12 col-md-6">
                    <h2 class="h5 fw-bold text-white mb-3">Buyer</h2>
                    <p class="text-safegate-text-sec mb-1"><?= sg_h($transaction['buyer_name']) ?></p>
                    <p class="text-safegate-text-sec mb-0"><?= sg_h($transaction['buyer_email']) ?></p>
                </div>
                <div class="col-12 col-md-6">
                    <h2 class="h5 fw-bold text-white mb-3">Seller</h2>
                    <p class="text-safegate-text-sec mb-1"><?= sg_h($transaction['seller_name']) ?></p>
                    <p class="text-safegate-text-sec mb-0"><?= sg_h($transaction['seller_email']) ?></p>
                </div>
                <div class="col-12">
                    <h2 class="h5 fw-bold text-white mb-3">Seat & Escrow</h2>
                    <p class="text-safegate-text-sec mb-1">Section <?= sg_h($transaction['section']) ?>, Row <?= sg_h($transaction['row']) ?>, Seat <?= sg_h($transaction['seat']) ?></p>
                    <p class="text-safegate-text-sec mb-0">Escrow status: <strong class="text-white"><?= sg_h(ucwords($transaction['escrow_status'])) ?></strong></p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-12 col-lg-5">
                    <div class="sg-ledger-list rounded-4 p-4 h-100">
                        <h2 class="h5 fw-bold text-white mb-4">Escrow Ledger</h2>
                        <?php if (!$ledger): ?>
                            <p class="text-safegate-text-sec mb-0">Belum ada aktivitas escrow untuk transaksi ini.</p>
                        <?php else: ?>
                            <?php foreach ($ledger as $item): ?>
                                <div class="sg-ledger-item">
                                    <div class="sg-ledger-dot">
                                        <iconify-icon icon="<?= $item['entry_type'] === 'refund' ? 'ph:arrow-u-up-left-bold' : ($item['entry_type'] === 'release' ? 'ph:lock-open-bold' : 'ph:lock-key-bold') ?>"></iconify-icon>
                                    </div>
                                    <div>
                                        <p class="fw-bold text-white mb-1"><?= sg_h($ledgerLabels[$item['entry_type']] ?? ucwords(str_replace('_', ' ', $item['entry_type']))) ?></p>
                                        <p class="text-safegate-text-sec small mb-1"><?= sg_h($item['notes'] ?: 'Aktivitas otomatis SafeGate.') ?></p>
                                        <p class="text-safegate-text-sec small mb-0"><?= sg_h($item['full_name']) ?> · <?= date('d M Y, H:i', strtotime($item['created_at'])) ?></p>
                                    </div>
                                    <div class="sg-ledger-amount text-end">
                                        <strong class="text-safegate-neon"><?= sg_rupiah($item['amount']) ?></strong>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-12 col-lg-7">
                    <div class="sg-dispute-box rounded-4 p-4 h-100">
                        <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 mb-4">
                            <div>
                                <h2 class="h5 fw-bold text-white mb-1">Dispute Center</h2>
                                <p class="text-safegate-text-sec small mb-0">Komunikasi buyer, seller, dan admin untuk transaksi ini.</p>
                            </div>
                            <?php if ($disputes): ?>
                                <span class="badge rounded-pill px-3 py-2 align-self-start" style="background: rgba(255, 210, 76, .12); color: #ffd24c; border: 1px solid rgba(255, 210, 76, .24);">
                                    <?= count($disputes) ?> dispute
                                </span>
                            <?php endif; ?>
                        </div>

                        <?php if (!$disputes): ?>
                            <div class="rounded-4 p-4 text-center" style="background: rgba(255,255,255,.035); border: 1px dashed rgba(255,255,255,.12);">
                                <iconify-icon icon="ph:shield-check-bold" class="text-safegate-neon fs-2"></iconify-icon>
                                <p class="fw-bold text-white mt-2 mb-1">Tidak ada dispute aktif</p>
                                <p class="text-safegate-text-sec small mb-0">Transaksi ini belum punya laporan masalah dari buyer.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($disputes as $dispute): ?>
                                <?php $messages = sg_get_dispute_messages((int) $dispute['id']); ?>
                                <article class="rounded-4 p-3 p-md-4 mb-4" style="background: rgba(255,255,255,.035); border: 1px solid rgba(255,255,255,.08);">
                                    <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 mb-3">
                                        <div>
                                            <p class="text-safegate-neon fw-bold text-uppercase mb-1" style="letter-spacing:.1em; font-size:.72rem;">Claim Buyer</p>
                                            <p class="text-white mb-0"><?= nl2br(sg_h($dispute['buyer_claim'])) ?></p>
                                        </div>
                                        <span class="badge rounded-pill px-3 py-2 align-self-start" style="background: rgba(122,153,197,.14); color:#d7e2f4; border: 1px solid rgba(122,153,197,.2);">
                                            <?= sg_h($statusText[$dispute['status']] ?? ucwords(str_replace('_', ' ', $dispute['status']))) ?>
                                        </span>
                                    </div>

                                    <?php if (!empty($dispute['seller_defense']) || !empty($dispute['admin_notes']) || !empty($dispute['resolution'])): ?>
                                        <div class="row g-3 mb-4">
                                            <?php if (!empty($dispute['seller_defense'])): ?>
                                                <div class="col-12 col-md-6">
                                                    <small class="text-safegate-text-sec fw-bold text-uppercase">Seller Defense</small>
                                                    <p class="text-white mb-0 mt-1"><?= nl2br(sg_h($dispute['seller_defense'])) ?></p>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (!empty($dispute['admin_notes'])): ?>
                                                <div class="col-12 col-md-6">
                                                    <small class="text-safegate-text-sec fw-bold text-uppercase">Admin Notes</small>
                                                    <p class="text-white mb-0 mt-1"><?= nl2br(sg_h($dispute['admin_notes'])) ?></p>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (!empty($dispute['resolution'])): ?>
                                                <div class="col-12">
                                                    <small class="text-safegate-text-sec fw-bold text-uppercase">Resolution</small>
                                                    <p class="text-white mb-0 mt-1"><?= nl2br(sg_h($dispute['resolution'])) ?></p>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="d-flex flex-column gap-3 mb-4">
                                        <?php if (!$messages): ?>
                                            <p class="text-safegate-text-sec small mb-0">Belum ada pesan lanjutan.</p>
                                        <?php else: ?>
                                            <?php foreach ($messages as $message): ?>
                                                <?php $mine = (int) $message['sender_id'] === $currentUserId; ?>
                                                <div class="sg-dispute-message <?= $mine ? 'is-mine' : '' ?> rounded-4 p-3">
                                                    <div class="d-flex justify-content-between gap-3 mb-2">
                                                        <strong class="text-white"><?= sg_h($message['full_name']) ?></strong>
                                                        <small class="text-safegate-text-sec"><?= sg_h(ucwords($message['sender_role'])) ?> · <?= date('d M H:i', strtotime($message['created_at'])) ?></small>
                                                    </div>
                                                    <p class="text-safegate-text-sec mb-0"><?= nl2br(sg_h($message['message'])) ?></p>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>

                                    <?php if ($currentUserId && ($role === 'admin' || in_array($currentUserId, [(int) $transaction['buyer_id'], (int) $transaction['seller_id']], true)) && !in_array($dispute['status'], ['resolved_refund', 'resolved_release', 'closed'], true)): ?>
                                        <form method="post" action="index.php" class="sg-dispute-form">
                                            <input type="hidden" name="sg_action" value="dispute_message">
                                            <input type="hidden" name="dispute_id" value="<?= (int) $dispute['id'] ?>">
                                            <input type="hidden" name="transaction_code" value="<?= sg_h($transaction['transaction_code']) ?>">
                                            <label class="text-safegate-text-sec fw-bold small text-uppercase mb-2" style="letter-spacing:.08em;" for="dispute-message-<?= (int) $dispute['id'] ?>">Tambah Pesan</label>
                                            <textarea id="dispute-message-<?= (int) $dispute['id'] ?>" name="message" class="form-control rounded-4 mb-3" placeholder="Tulis update, bukti tambahan, atau catatan admin..." required></textarea>
                                            <button type="submit" class="btn btn-safegate-neon rounded-pill fw-bold px-4">Kirim Pesan</button>
                                        </form>
                                    <?php endif; ?>
                                </article>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</section>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/public_layout.php';
?>
