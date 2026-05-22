<?php
// views/admin/transactions.php - Global Ledger Transaksi
require_once __DIR__ . '/../../core/admin_middleware.php';

$page_title = 'Global Ledger - SafeGate Admin';
$admin_page = 'transactions';

ob_start();
?>

<div class="sg-admin-header">
    <div class="sg-admin-title-area">
        <h1>Global Ledger Book</h1>
        <p>Buku Besar Global SafeGate | Pencatatan Real-time Seluruh Transaksi & Status Escrow</p>
    </div>
    <div class="sg-admin-status-badge success">
        <iconify-icon icon="ph:database-fill"></iconify-icon> Ledger Sync: Active
    </div>
</div>

<div class="sg-admin-panel">
    <h2 class="sg-admin-panel-title">
        <iconify-icon icon="ph:list-dashes-fill"></iconify-icon> Aliran Dana & Brankas Escrow Terkini
    </h2>
    
    <div class="sg-admin-table-responsive">
        <table class="sg-admin-table">
            <thead>
                <tr>
                    <th>TXID</th>
                    <th>Buyer</th>
                    <th>Seller</th>
                    <th>Ticket Detail</th>
                    <th>Price</th>
                    <th>Escrow Protection</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><code>TX-08942-SG</code></td>
                    <td>Aditya Pratama</td>
                    <td>Budi Santoso</td>
                    <td>Coldplay Music of the Spheres (CAT 1 - Gate A)</td>
                    <td><strong>Rp 4.500.000</strong></td>
                    <td><span class="sg-admin-status-badge success"><iconify-icon icon="ph:lock-keyhole-fill"></iconify-icon> Secured</span></td>
                    <td>2026-05-22 14:32:01</td>
                </tr>
                <tr>
                    <td><code>TX-08941-SG</code></td>
                    <td>Siti Rahma</td>
                    <td>Budi Santoso</td>
                    <td>Coldplay Music of the Spheres (CAT 3 - Row 12)</td>
                    <td><strong>Rp 2.800.000</strong></td>
                    <td><span class="sg-admin-status-badge success"><iconify-icon icon="ph:lock-keyhole-fill"></iconify-icon> Secured</span></td>
                    <td>2026-05-22 13:12:44</td>
                </tr>
                <tr>
                    <td><code>TX-08722-SG</code></td>
                    <td>Rian Hidayat</td>
                    <td>Dewi Lestari</td>
                    <td>Bruno Mars Live in Jakarta (Festival A)</td>
                    <td><strong>Rp 3.500.000</strong></td>
                    <td><span class="sg-admin-status-badge warning"><iconify-icon icon="ph:hourglass-fill"></iconify-icon> Pending Escrow</span></td>
                    <td>2026-05-22 11:05:12</td>
                </tr>
                <tr>
                    <td><code>TX-08691-SG</code></td>
                    <td>Joko Susilo</td>
                    <td>Rudi Hartono</td>
                    <td>Lany Concert Jakarta (General Admission)</td>
                    <td><strong>Rp 1.500.000</strong></td>
                    <td><span class="sg-admin-status-badge danger"><iconify-icon icon="ph:x-circle-fill"></iconify-icon> Refunded</span></td>
                    <td>2026-05-21 18:22:10</td>
                </tr>
                <tr>
                    <td><code>TX-08544-SG</code></td>
                    <td>Farhan Lubis</td>
                    <td>Melati Indah</td>
                    <td>Tulus Concert Tour (VIP Lounge)</td>
                    <td><strong>Rp 5.200.000</strong></td>
                    <td><span class="sg-admin-status-badge success"><iconify-icon icon="ph:check-circle-fill"></iconify-icon> Released to Seller</span></td>
                    <td>2026-05-21 09:44:59</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/admin_layout.php';
?>
