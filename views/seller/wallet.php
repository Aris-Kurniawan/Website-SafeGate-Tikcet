<?php
require_once __DIR__ . '/../../core/safegate_repository.php';

$page_title = 'Wallet & Escrow - SafeGate';
$dashboard_page = 'wallet';

$seller_id = sg_current_user_id('seller');
$metrics = sg_get_seller_overview($seller_id);
$withdrawals = sg_get_seller_withdrawals($seller_id);
$flash = sg_flash();

ob_start();
?>

<section class="sg-vendor-page sg-wallet-page">
    <header class="sg-vendor-heading">
        <h1>Wallet &amp; Escrow</h1>
        <p>Kelola pendapatan dan lakukan penarikan dana dengan aman.</p>
    </header>

    <?php if ($flash): ?>
        <p class="sg-list-status <?= $flash['type'] === 'error' ? 'is-error' : '' ?>"><?= sg_h($flash['message']) ?></p>
    <?php endif; ?>

    <div class="sg-wallet-balance-grid">
        <article class="sg-wallet-balance-card">
            <div>
                <span>Escrow Balance</span>
                <strong><?= sg_rupiah($metrics['escrow_balance']) ?></strong>
            </div>
            <iconify-icon icon="ph:lock-key"></iconify-icon>
            <div class="sg-release-row"><span>Release Progress</span><b>76% Locked</b></div>
            <div class="sg-release-track"><i></i></div>
        </article>

        <article class="sg-wallet-balance-card is-available">
            <div>
                <span>Available Balance</span>
                <strong><?= sg_rupiah($metrics['available_balance']) ?></strong>
                <small><iconify-icon icon="ph:seal-check"></iconify-icon> Ready for withdrawal</small>
            </div>
            <iconify-icon icon="ph:wallet"></iconify-icon>
        </article>
    </div>

    <div class="sg-wallet-grid">
        <form class="sg-panel sg-withdraw-panel" action="index.php?page=wallet" method="post">
            <input type="hidden" name="sg_action" value="withdrawal">
            <h2><iconify-icon icon="ph:money"></iconify-icon> Withdraw Funds</h2>
            <label>
                <span>Metode Pencairan</span>
                <select name="method">
                    <option value="bank_transfer">Transfer Bank (BCA, Mandiri, BNI)</option>
                    <option value="dana">DANA</option>
                    <option value="gopay">GoPay</option>
                    <option value="ovo">OVO</option>
                    <option value="usdc">USDC Wallet</option>
                </select>
            </label>
            <label>
                <span>Nomor Rekening / Wallet Address</span>
                <input name="destination_account" type="text" placeholder="Masukkan detail tujuan..." required>
            </label>
            <label>
                <span>Nominal Penarikan (Rp)</span>
                <input name="amount" type="text" inputmode="numeric" placeholder="Rp    Min. 60.000" required>
            </label>
            <button type="submit">Tarik Dana <iconify-icon icon="ph:lightning"></iconify-icon></button>
        </form>

        <section class="sg-panel sg-withdraw-table">
            <div class="sg-panel-title-row">
                <h2><iconify-icon icon="ph:clock-counter-clockwise"></iconify-icon> Recent Withdrawals</h2>
                <a href="#">View All</a>
            </div>
            <table>
                <thead><tr><th>Tanggal</th><th>Metode</th><th>Jumlah</th><th>Status</th></tr></thead>
                <tbody>
                    <?php if (!$withdrawals): ?>
                        <tr>
                            <td colspan="4" style="color: var(--safegate-text-sec);">Belum ada riwayat penarikan dari database.</td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($withdrawals as $withdrawal): ?>
                        <tr>
                            <td><?= $withdrawal['date'] ?></td>
                            <td><?= $withdrawal['method'] ?></td>
                            <td><strong><?= $withdrawal['amount'] ?></strong></td>
                            <td><span class="sg-withdraw-status is-<?= $withdrawal['class'] ?>"><?= $withdrawal['status'] ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </div>
</section>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/dashboard_layout.php';
?>
