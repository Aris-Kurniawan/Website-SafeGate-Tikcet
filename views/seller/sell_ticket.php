<?php
require_once __DIR__ . '/../../core/safegate_repository.php';

$sellerId = sg_current_user_id();
$kycStatus = sg_fetch_one('SELECT status FROM kyc_verifications WHERE user_id = :user_id ORDER BY id DESC LIMIT 1', ['user_id' => $sellerId]);
if (!$kycStatus || $kycStatus['status'] !== 'approved') {
    sg_flash('Identitas kamu belum diverifikasi oleh admin. Lengkapi KYC dan tunggu persetujuan terlebih dahulu sebelum menjual tiket.', 'error');
    sg_redirect('settings');
}

$page_title = 'Sell Your Ticket - SafeGate';
$dashboard_page = 'sell_ticket';
$extra_scripts = ['assets/js/sell-ticket.js'];

$event_options = sg_get_events_for_listing();
$selected_event = $event_options[0];
$flash = sg_flash();

ob_start();
?>

<section class="sg-vendor-page sg-auction-page">
    <header class="sg-vendor-heading">
        <h1>Sell Your Ticket</h1>
        <p>Daftarkan aset tiket Anda dengan aman melalui protokol enkripsi SafeGate.</p>
    </header>

    <?php if ($flash): ?>
        <p class="sg-list-status <?= $flash['type'] === 'error' ? 'is-error' : '' ?>"><?= sg_h($flash['message']) ?></p>
    <?php endif; ?>

    <form id="listingForm" class="sg-auction-grid" action="index.php?page=sell_ticket" method="post" enctype="multipart/form-data">
        <input type="hidden" name="sg_action" value="create_listing">
        <input id="selectedEventId" type="hidden" name="event_id" value="<?= sg_h($selected_event['id'] ?? '') ?>">
        <input id="faceValueInput" type="hidden" name="face_value" value="<?= (int) $selected_event['face_value'] ?>">

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
                            data-event-id="<?= sg_h($event_option['id'] ?? '') ?>"
                            data-title="<?= sg_h($event_option['title']) ?>"
                            data-date="<?= sg_h($event_option['date']) ?>"
                            data-time="<?= sg_h($event_option['time']) ?>"
                            data-face-value="<?= (int) $event_option['face_value'] ?>"
                            data-selling-price="<?= (int) $event_option['selling_price'] ?>"
                            <?= $index === 0 ? '' : 'hidden' ?>
                        >
                            <span class="sg-event-preview">
                                <span>Official Listing</span>
                            </span>
                            <span class="sg-auction-event-copy">
                                <strong><?= sg_h($event_option['title']) ?></strong>
                                <em><iconify-icon icon="ph:map-pin"></iconify-icon> <?= sg_h($event_option['venue']) ?></em>
                                <em><iconify-icon icon="ph:calendar"></iconify-icon> <?= sg_h($event_option['date']) ?> - <?= sg_h($event_option['time']) ?></em>
                                <small>"<?= sg_h($event_option['description'] ?? 'Official SafeGate verified event listing.') ?>"</small>
                            </span>
                            <span class="sg-face-value">
                                <small>Face Value</small>
                                <b><?= sg_rupiah($event_option['face_value']) ?></b>
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
                    <button type="button" id="btnFixedPrice">Fixed Price</button>
                    <button type="button" id="btnAuction" class="is-active">Auction</button>
                </div>
                <div class="sg-auction-input-grid" id="pricingInputsGrid">
                    <label id="labelStartingBid">
                        <span id="textStartingBid">Starting Bid (Rp)</span>
                        <input id="sellingPrice" name="starting_bid" type="text" inputmode="numeric" value="<?= (int) $selected_event['selling_price'] ?>" data-face-value="<?= (int) $selected_event['face_value'] ?>">
                    </label>
                    <label id="labelReservePrice">
                        <span>Reserve Price (Rp)</span>
                        <input id="reservePrice" name="reserve_price" type="text" placeholder="Min. price to sell">
                    </label>
                    <label id="labelDuration">
                        <span>Duration</span>
                        <select id="auctionDuration" name="duration">
                            <option value="24">24 Hours</option>
                            <option value="72">3 Days</option>
                            <option value="168">7 Days</option>
                        </select>
                    </label>
                </div>
                <div class="sg-auction-input-grid sg-seat-input-grid">
                    <label>
                        <span>Section</span>
                        <input id="ticketSection" name="section" type="text" value="102" maxlength="20" required>
                    </label>
                    <label>
                        <span>Row</span>
                        <input id="ticketRow" name="row" type="text" value="A" maxlength="20" required>
                    </label>
                    <label>
                        <span>Seat</span>
                        <input id="ticketSeat" name="seat" type="text" value="1" maxlength="20" required>
                    </label>
                </div>
                <input id="originalPrice" type="hidden" value="<?= sg_rupiah($selected_event['face_value']) ?>">
                <div class="sg-auction-fairness" id="fairnessBox">
                    <div><span>Fairness Indicator</span><strong id="fairnessLabel">Fair Market Price</strong></div>
                    <div class="sg-fairness-track"><i id="fairnessMeter"></i></div>
                    <p id="fairnessMessage"><iconify-icon icon="ph:info"></iconify-icon> Anti-Scalping System: Reserve Price tidak boleh melebihi 110% dari Harga Asli.</p>
                </div>
            </section>

            <section class="sg-panel sg-auction-panel">
                <div class="sg-step-title">
                    <h2><iconify-icon icon="ph:seal-check"></iconify-icon> Ticket Proof</h2>
                    <span>Step 03/03</span>
                </div>
                <label class="sg-upload-drop sg-auction-upload" id="uploadDrop">
                    <input id="ticketFile" name="ticket_proof" type="file" accept=".pdf,.jpg,.jpeg,.png,.pkpass">
                    <span class="sg-upload-icon"><iconify-icon icon="ph:file-arrow-up"></iconify-icon></span>
                    <img id="ticketPreview" class="sg-ticket-proof-preview" src="" alt="Ticket proof preview" hidden>
                    <strong>Upload Digital Ticket (PDF/JPG/PNG)</strong>
                    <span>Max 10MB. PDF, JPG, PNG, atau Apple Wallet Pass.</span>
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
                <span id="summarySelling" hidden><?= sg_rupiah($selected_event['selling_price']) ?></span>
                <span id="summaryFee" hidden></span>
                <span id="summaryEarning" hidden></span>
            </section>
        </aside>
    </form>
</section>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/dashboard_layout.php';
?>
