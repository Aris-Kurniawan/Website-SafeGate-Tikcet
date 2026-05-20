<?php
$page_title = 'Sell Your Ticket - SafeGate';
$dashboard_page = 'sell_ticket';
$extra_scripts = ['assets/js/sell-ticket.js'];

$selected_event = [
    'title' => 'The Eras Tour - Wembley Stadium',
    'date' => 'August 17, 2024',
    'time' => '19:00',
    'face_value' => 125000,
    'selling_price' => 135000,
    'service_fee_rate' => 0.05,
];

$event_options = [
    $selected_event,
    [
        'title' => 'Coldplay Music of the Spheres',
        'date' => 'September 08, 2024',
        'time' => '20:00',
        'face_value' => 950000,
        'selling_price' => 1010000,
        'service_fee_rate' => 0.05,
    ],
    [
        'title' => 'Premier League: London Derby',
        'date' => 'October 12, 2024',
        'time' => '18:30',
        'face_value' => 1800000,
        'selling_price' => 1890000,
        'service_fee_rate' => 0.05,
    ],
];

$service_fee = $selected_event['selling_price'] * $selected_event['service_fee_rate'];
$earning = $selected_event['selling_price'] - $service_fee;

ob_start();
?>

<section class="sg-sell-page sg-sell-v2">
    <div class="sg-sell-v2-hero">
        <div class="sg-sell-header">
            <h1>Sell Your Ticket</h1>
            <p>Securely list your tickets for the SafeGate marketplace in four simple steps.</p>
        </div>

        <ol class="sg-stepper" id="listingStepper" aria-label="Ticket listing steps">
            <li class="is-active" data-step="event"><span>1</span><strong>Event</strong></li>
            <li data-step="pricing"><span>2</span><strong>Pricing</strong></li>
            <li data-step="upload"><span>3</span><strong>Upload</strong></li>
        </ol>
    </div>

    <div class="sg-sell-grid">
        <div class="sg-sell-stack">
            <section class="sg-seller-panel sg-event-panel">
                <h2 class="sg-section-label">01. Event Details</h2>
                <label class="sg-input-with-icon">
                    <iconify-icon icon="ph:magnifying-glass"></iconify-icon>
                    <input id="eventSearch" type="search" placeholder="Find your event...">
                </label>

                <div class="sg-event-list" id="eventList">
                    <?php foreach ($event_options as $index => $event_option): ?>
                        <button
                            type="button"
                            class="sg-event-option <?= $index === 0 ? 'is-selected' : '' ?>"
                            data-title="<?= htmlspecialchars($event_option['title']) ?>"
                            data-date="<?= htmlspecialchars($event_option['date']) ?>"
                            data-time="<?= htmlspecialchars($event_option['time']) ?>"
                            data-face-value="<?= $event_option['face_value'] ?>"
                            data-selling-price="<?= $event_option['selling_price'] ?>"
                            <?= $index === 0 ? '' : 'hidden' ?>
                        >
                            <span class="sg-event-icon"><iconify-icon icon="ph:ticket"></iconify-icon></span>
                            <span class="sg-event-copy">
                                <strong><?= $index === 0 ? 'The Eras Tour - London' : htmlspecialchars($event_option['title']) ?></strong>
                                <em><?= $index === 0 ? 'Wembley' : htmlspecialchars($event_option['date']) ?> &bull; <?= $index === 0 ? 'Aug 17, 2024' : htmlspecialchars($event_option['time']) ?></em>
                            </span>
                            <small>Face Value</small>
                            <b>Rp.<?= number_format($event_option['face_value'], 0, ',', '.') ?></b>
                        </button>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="sg-seller-panel sg-pricing-panel">
                <h2 class="sg-section-label">02. Pricing &amp; Seats</h2>
                <div class="sg-price-row">
                    <label class="sg-listed-price">
                        <span>Listed Price</span>
                        <input id="sellingPrice" type="text" inputmode="numeric" value="Rp.<?= number_format($selected_event['selling_price'], 0, ',', '.') ?>" data-face-value="<?= $selected_event['face_value'] ?>">
                    </label>

                    <div class="sg-fairness-box" id="fairnessBox">
                        <div>
                            <span>Fairness</span>
                            <strong id="fairnessLabel">Good Value</strong>
                        </div>
                        <div class="sg-fairness-track">
                            <i id="fairnessMeter"></i>
                        </div>
                        <p id="fairnessMessage">Harga masih dalam batas 110%. Listing dengan harga fair lebih cepat terjual.</p>
                    </div>
                </div>

                <input id="originalPrice" type="hidden" value="Rp.125.000">

                <div class="sg-seat-grid">
                    <label>
                        <span>Section</span>
                        <input type="text" value="104">
                    </label>
                    <label>
                        <span>Row</span>
                        <input type="text" value="G">
                    </label>
                    <label>
                        <span>Seat</span>
                        <input type="text" value="22">
                    </label>
                </div>
            </section>

            <section class="sg-seller-panel sg-upload-panel">
                <h2 class="sg-section-label">03. Ticket Proof</h2>
                <label class="sg-upload-drop" id="uploadDrop">
                    <input id="ticketFile" type="file" accept=".pdf,.jpg,.jpeg,.png,.pkpass">
                    <span class="sg-upload-icon"><iconify-icon icon="ph:cloud-arrow-up"></iconify-icon></span>
                    <strong>Upload Digital Ticket</strong>
                    <span>PDF, JPG, or Apple Wallet Pass (Max 10MB)</span>
                </label>
                <p class="sg-upload-status" id="uploadStatus" aria-live="polite"></p>
            </section>
        </div>

        <aside class="sg-sell-aside">
            <section class="sg-payout-card">
                <h2>Earnings Summary</h2>
                <dl>
                    <div>
                        <dt>Ticket Price</dt>
                        <dd id="summarySelling">Rp.<?= number_format($selected_event['selling_price'], 0, ',', '.') ?></dd>
                    </div>
                    <div>
                        <dt>SafeGate Fee <span class="sg-fee-pill">5%</span></dt>
                        <dd class="danger" id="summaryFee">Rp.<?= number_format($service_fee, 0, ',', '.') ?></dd>
                    </div>
                    <div class="sg-total-row">
                        <dt>You Receive</dt>
                        <dd id="summaryEarning">Rp.<?= number_format($earning, 0, ',', '.') ?></dd>
                    </div>
                </dl>
                <div class="sg-escrow-note">
                    <iconify-icon icon="ph:seal-check"></iconify-icon>
                    <p><strong>SafeGate Escrow:</strong> Your funds are secured and released 24h after the event ends.</p>
                </div>
                <button id="listTicketButton" class="sg-list-button" type="button">List My Ticket <iconify-icon icon="ph:arrow-right-bold"></iconify-icon></button>
                <p class="sg-list-status" id="listStatus" aria-live="polite"></p>
                <p>By listing, you agree to our vendor agreement</p>
            </section>

            <section class="sg-encryption-card">
                <span><iconify-icon icon="ph:shield"></iconify-icon></span>
                <div>
                    <strong>256-Bit AES Encryption</strong>
                    <p>Your data is secured with bank-grade protocols.</p>
                </div>
            </section>
        </aside>
    </div>
</section>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/dashboard_layout.php';
?>
