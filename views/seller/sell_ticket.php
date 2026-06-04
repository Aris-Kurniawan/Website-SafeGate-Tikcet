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

        <div class="sg-auction-stack">
            <section class="sg-panel sg-auction-panel">
                <div class="sg-step-title">
                    <h2><iconify-icon icon="ph:ticket"></iconify-icon> Event Selection</h2>
                    <span>Step 01/03</span>
                </div>

                <style>
                    #eventThumbnail::-webkit-file-upload-button,
                    #eventThumbnail::file-selector-button {
                        background: #1a1e28;
                        border: 1px solid rgba(255,255,255,0.1);
                        color: #c8c3ba;
                        padding: 8px 14px;
                        border-radius: 4px;
                        margin-right: 12px;
                        cursor: pointer;
                        text-transform: uppercase;
                        font-size: 11px;
                        font-weight: 800;
                        transition: all 0.2s ease;
                        vertical-align: middle;
                    }
                    #eventThumbnail::-webkit-file-upload-button:hover,
                    #eventThumbnail::file-selector-button:hover {
                        background: rgba(217, 255, 0, 0.1);
                        color: var(--safegate-neon);
                        border-color: rgba(217, 255, 0, 0.3);
                    }
                </style>
                <div class="sg-auction-input-grid">
                    <label style="grid-column: span 3;">
                        <span>Thumbnail / Poster Event (JPG/PNG, Max 5MB)</span>
                        <input id="eventThumbnail" name="event_thumbnail" type="file" accept=".jpg,.jpeg,.png" required style="padding: 11px 16px; height: 58px; box-sizing: border-box; line-height: normal;">
                    </label>
                    <label style="grid-column: span 3;">
                        <span>Nama Acara / Konser</span>
                        <input id="eventTitle" name="event_title" type="text" placeholder="Contoh: Coldplay - Music of the Spheres" maxlength="200" required>
                    </label>
                    <label style="grid-column: span 2;">
                        <span>Lokasi (Venue)</span>
                        <input id="eventVenue" name="event_venue" type="text" placeholder="Contoh: Gelora Bung Karno" maxlength="200" required>
                    </label>
                    <label style="grid-column: span 1;">
                        <span>Kota</span>
                        <input id="eventCity" name="event_city" type="text" placeholder="Jakarta" maxlength="100" style="min-width: 0; width: 100%; box-sizing: border-box;" required>
                    </label>
                    <label style="grid-column: span 1;">
                        <span>Tanggal Acara</span>
                        <input id="eventDate" name="event_date" type="date" required>
                    </label>
                    <label style="grid-column: span 1;">
                        <span>Waktu (Jam)</span>
                        <input id="eventTime" name="event_time" type="time" required>
                    </label>
                    <label style="grid-column: span 3;">
                        <span>Kategori Tiket, Benefit, dan Alasan Jual</span>
                        <textarea id="eventDescription" name="event_description" placeholder="Sebutkan kategori tiket (misal VIP Platinum), apa saja benefitnya, dan kenapa tiket ini dijual..." rows="3" style="width: 100%; background: transparent; border: 1px solid rgba(255,255,255,0.1); color: white; padding: 12px; border-radius: 8px;" required></textarea>
                    </label>
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
                <div class="sg-auction-input-grid" id="pricingInputsGrid" style="grid-template-columns: repeat(3, minmax(0, 1fr));">
                    <label id="labelFaceValue">
                        <span>Face Value / Harga Asli (Rp)</span>
                        <input id="faceValuePrice" name="face_value" type="text" inputmode="numeric" placeholder="Contoh: 1500000" required>
                    </label>
                    <label id="labelStartingBid">
                        <span id="textStartingBid">Starting Bid (Rp)</span>
                        <input id="sellingPrice" name="starting_bid" type="text" inputmode="numeric" placeholder="Contoh: 2000000" required>
                    </label>
                    <label id="labelReservePrice">
                        <span>Reserve Price (Rp)</span>
                        <input id="reservePrice" name="reserve_price" type="text" placeholder="Min. price to sell">
                    </label>
                    <label id="labelDuration">
                        <span>Waktu Lelang</span>
                        <select id="auctionDuration" name="duration">
                            <option value="6">6 Jam</option>
                            <option value="12">12 Jam</option>
                            <option value="24" selected>24 Jam</option>
                            <option value="48">2 Hari</option>
                            <option value="72">3 Hari</option>
                            <option value="168">7 Hari</option>
                            <option value="custom">Custom</option>
                        </select>
                    </label>
                    <label id="customDurationWrap" hidden>
                        <span>Durasi Custom</span>
                        <input id="customDuration" name="custom_duration" type="number" min="1" max="43200" step="1" placeholder="Contoh: 30">
                    </label>
                    <label id="customDurationUnitWrap" hidden>
                        <span>Satuan Custom</span>
                        <select id="customDurationUnit" name="custom_duration_unit">
                            <option value="minutes">Menit</option>
                            <option value="hours" selected>Jam</option>
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
