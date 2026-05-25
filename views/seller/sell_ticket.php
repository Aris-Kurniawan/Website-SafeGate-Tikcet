<?php
$page_title = 'Sell Your Ticket - SafeGate';
$dashboard_page = 'sell_ticket';
$extra_scripts = ['assets/js/sell-ticket.js'];

$selected_event = [
    'title' => 'The Eras Tour - London',
    'date' => 'August 17, 2024',
    'time' => '19:00 BST',
    'venue' => 'Wembley Stadium, London, UK',
    'face_value' => 1500000,
    'selling_price' => 1000000,
    'service_fee_rate' => 0.05,
];

$event_options = [
    $selected_event,
    [
        'title' => 'Coldplay Music of the Spheres',
        'date' => 'September 08, 2024',
        'time' => '20:00 WIB',
        'venue' => 'GBK Stadium, Jakarta',
        'face_value' => 950000,
        'selling_price' => 1010000,
        'service_fee_rate' => 0.05,
    ],
    [
        'title' => 'Premier League: London Derby',
        'date' => 'October 12, 2024',
        'time' => '18:30 BST',
        'venue' => 'London Stadium, UK',
        'face_value' => 1800000,
        'selling_price' => 1890000,
        'service_fee_rate' => 0.05,
    ],
];

ob_start();
?>

<section class="sg-vendor-page sg-auction-page">
    <header class="sg-vendor-heading">
        <h1>Sell Your Ticket</h1>
        <p>Daftarkan aset tiket Anda dengan aman melalui protokol enkripsi SafeGate.</p>
    </header>

    <div class="sg-auction-grid">
        <div class="sg-auction-stack">
            <section class="sg-panel sg-auction-panel">
                <div class="sg-step-title">
                    <h2><iconify-icon icon="ph:ticket"></iconify-icon> Event Selection</h2>
                    <span>Step 01/03</span>
                </div>

                <label class="sg-auction-search">
                    <iconify-icon icon="ph:magnifying-glass"></iconify-icon>
                    <input id="eventSearch" type="search" placeholder="Find your event...">
                </label>

                <div class="sg-event-list" id="eventList">
                    <?php foreach ($event_options as $index => $event_option): ?>
                        <button
                            type="button"
                            class="sg-event-option sg-auction-event <?= $index === 0 ? 'is-selected' : '' ?>"
                            data-title="<?= htmlspecialchars($event_option['title']) ?>"
                            data-date="<?= htmlspecialchars($event_option['date']) ?>"
                            data-time="<?= htmlspecialchars($event_option['time']) ?>"
                            data-face-value="<?= $event_option['face_value'] ?>"
                            data-selling-price="<?= $event_option['selling_price'] ?>"
                            <?= $index === 0 ? '' : 'hidden' ?>
                        >
                            <span class="sg-event-preview">
                                <span>Official Listing</span>
                            </span>
                            <span class="sg-auction-event-copy">
                                <strong><?= htmlspecialchars($event_option['title']) ?></strong>
                                <em><iconify-icon icon="ph:map-pin"></iconify-icon> <?= htmlspecialchars($event_option['venue']) ?></em>
                                <em><iconify-icon icon="ph:calendar"></iconify-icon> <?= htmlspecialchars($event_option['date']) ?> · <?= htmlspecialchars($event_option['time']) ?></em>
                                <small>"Join Taylor Swift for a legendary journey through her musical eras. This ticket grants access to the North Stand, Section 102."</small>
                            </span>
                            <span class="sg-face-value">
                                <small>Face Value</small>
                                <b>Rp <?= number_format($event_option['face_value'], 0, ',', '.') ?></b>
                            </span>
                        </button>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="sg-panel sg-auction-panel sg-pricing-panel">
                <div class="sg-step-title">
                    <h2><iconify-icon icon="ph:gavel"></iconify-icon> Pricing Strategy</h2>
                    <span>Step 02/03</span>
                </div>
                <div class="sg-mode-toggle">
                    <button type="button">Fixed Price</button>
                    <button type="button" class="is-active">Auction</button>
                </div>
                <div class="sg-auction-input-grid">
                    <label>
                        <span>Starting Bid (Rp)</span>
                        <input id="sellingPrice" type="text" inputmode="numeric" value="<?= $selected_event['selling_price'] ?>" data-face-value="<?= $selected_event['face_value'] ?>">
                    </label>
                    <label>
                        <span>Reserve Price (Rp)</span>
                        <input type="text" placeholder="Min. price to sell">
                    </label>
                    <label>
                        <span>Duration</span>
                        <select><option>24 Hours</option><option>3 Days</option><option>7 Days</option></select>
                    </label>
                </div>
                <input id="originalPrice" type="hidden" value="Rp.<?= number_format($selected_event['face_value'], 0, ',', '.') ?>">
                <div class="sg-auction-fairness" id="fairnessBox">
                    <div><span>Fairness Indicator</span><strong id="fairnessLabel">Fair Market Price</strong></div>
                    <div class="sg-fairness-track"><i id="fairnessMeter"></i></div>
                    <p id="fairnessMessage"><iconify-icon icon="ph:info"></iconify-icon> Anti-Scalping System: Reserve Price tidak boleh melebihi Rp 1.650.000 (+10% dari Harga Asli).</p>
                </div>
            </section>

            <section class="sg-panel sg-auction-panel">
                <div class="sg-step-title">
                    <h2><iconify-icon icon="ph:seal-check"></iconify-icon> Ticket Proof</h2>
                    <span>Step 03/03</span>
                </div>
                <label class="sg-upload-drop sg-auction-upload" id="uploadDrop">
                    <input id="ticketFile" type="file" accept=".pdf,.jpg,.jpeg,.png,.pkpass">
                    <span class="sg-upload-icon"><iconify-icon icon="ph:file-arrow-up"></iconify-icon></span>
                    <strong>Upload Digital Ticket (PDF/JPG)</strong>
                    <span>Max 10MB. Dilengkapi enkripsi end-to-end AES-256.</span>
                </label>
                <p class="sg-upload-status" id="uploadStatus" aria-live="polite"></p>
            </section>
        </div>

        <aside class="sg-auction-aside">
            <section class="sg-panel sg-auction-summary">
                <h2>Auction Summary</h2>
                <dl>
                    <div><dt>Listing Type</dt><dd>Auction (Timed)</dd></div>
                    <div><dt>Security Deposit</dt><dd>Locked by SafeGate</dd></div>
                    <div><dt>Success Fee</dt><dd class="text-safegate-neon">5% from Final Bid</dd></div>
                </dl>
                <div class="sg-auction-note"><iconify-icon icon="ph:shield-check"></iconify-icon> Tiket akan diverifikasi secara otomatis oleh sistem kami sebelum dilepas ke publik.</div>
                <button id="listTicketButton" class="sg-start-auction" type="button">Start Auction <iconify-icon icon="ph:lightning"></iconify-icon></button>
                <p class="sg-list-status" id="listStatus" aria-live="polite"></p>
                <div class="sg-encryption-strip">
                    <strong><i></i> Encryption Active</strong>
                    <span>Network Load: 14%</span>
                    <span>ID: 882-QX-90</span>
                </div>
                <span id="summarySelling" hidden>Rp.<?= number_format($selected_event['selling_price'], 0, ',', '.') ?></span>
                <span id="summaryFee" hidden></span>
                <span id="summaryEarning" hidden></span>
            </section>
        </aside>
    </div>
</section>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/dashboard_layout.php';
?>
