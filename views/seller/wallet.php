<?php
$page_title = 'Wallet & Escrow - SafeGate';
$dashboard_page = 'wallet';

$withdrawals = [
    ['date' => '24 Okt<br>2023', 'method' => 'Bank<br>Transfer', 'amount' => 'Rp<br>1.500.000', 'status' => 'Processing', 'class' => 'processing'],
    ['date' => '22 Okt<br>2023', 'method' => 'DANA', 'amount' => 'Rp 450.000', 'status' => 'Success', 'class' => 'success'],
    ['date' => '19 Okt<br>2023', 'method' => 'USDC<br>Wallet', 'amount' => 'Rp<br>2.000.000', 'status' => 'Failed', 'class' => 'failed'],
];

ob_start();
?>

<section class="sg-vendor-page sg-wallet-page">
    <header class="sg-vendor-heading">
        <h1>Wallet &amp; Escrow</h1>
        <p>Kelola pendapatan dan lakukan penarikan dana dengan aman.</p>
    </header>

    <div class="sg-wallet-balance-grid">
        <article class="sg-wallet-balance-card">
            <div>
                <span>Escrow Balance</span>
                <strong>Rp 12.500.000</strong>
            </div>
            <iconify-icon icon="ph:lock-key"></iconify-icon>
            <div class="sg-release-row"><span>Release Progress</span><b>76% Locked</b></div>
            <div class="sg-release-track"><i></i></div>
        </article>

        <article class="sg-wallet-balance-card is-available">
            <div>
                <span>Available Balance</span>
                <strong>Rp 4.250.000</strong>
                <small><iconify-icon icon="ph:seal-check"></iconify-icon> Ready for withdrawal</small>
            </div>
            <iconify-icon icon="ph:wallet"></iconify-icon>
        </article>
    </div>

    <div class="sg-wallet-grid">
        <section class="sg-panel sg-withdraw-panel">
            <h2><iconify-icon icon="ph:money"></iconify-icon> Withdraw Funds</h2>
            <label>
                <span>Metode Pencairan</span>
                <select>
                    <option>Transfer Bank (BCA, Mandiri, BNI)</option>
                    <option>DANA</option>
                    <option>USDC Wallet</option>
                </select>
            </label>
            <label>
                <span>Nomor Rekening / Wallet Address</span>
                <input type="text" placeholder="Masukkan detail tujuan...">
            </label>
            <label>
                <span>Nominal Penarikan (Rp)</span>
                <input type="text" placeholder="Rp    Min. 60.000">
            </label>
            <button type="button" disabled>Tarik Dana <iconify-icon icon="ph:lightning"></iconify-icon></button>
        </section>

        <section class="sg-panel sg-withdraw-table">
            <div class="sg-panel-title-row">
                <h2><iconify-icon icon="ph:clock-counter-clockwise"></iconify-icon> Recent Withdrawals</h2>
                <a href="#">View All</a>
            </div>
            <table>
                <thead><tr><th>Tanggal</th><th>Metode</th><th>Jumlah</th><th>Status</th></tr></thead>
                <tbody>
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
