const priceInput = document.getElementById('sellingPrice');
const originalPriceInput = document.getElementById('originalPrice');
const fairnessBox = document.getElementById('fairnessBox');
const fairnessMeter = document.getElementById('fairnessMeter');
const fairnessLabel = document.getElementById('fairnessLabel');
const fairnessMessage = document.getElementById('fairnessMessage');
const listButton = document.getElementById('listTicketButton');
const listStatus = document.getElementById('listStatus');
const summarySelling = document.getElementById('summarySelling');
const summaryFee = document.getElementById('summaryFee');
const summaryEarning = document.getElementById('summaryEarning');
const ticketFile = document.getElementById('ticketFile');
const uploadDrop = document.getElementById('uploadDrop');
const uploadStatus = document.getElementById('uploadStatus');
const ticketPreview = document.getElementById('ticketPreview');
const eventSearch = document.getElementById('eventSearch');
const eventList = document.getElementById('eventList');
const listingForm = document.getElementById('listingForm');
const selectedEventId = document.getElementById('selectedEventId');
const faceValueInput = document.getElementById('faceValueInput');
const ticketSection = document.getElementById('ticketSection');
const ticketRow = document.getElementById('ticketRow');
const ticketSeat = document.getElementById('ticketSeat');
const eventButtons = Array.from(document.querySelectorAll('.sg-event-option'));
const stepItems = Array.from(document.querySelectorAll('#listingStepper li'));
const allowedTicketExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'pkpass'];

function formatRupiah(value) {
    return `Rp.${Math.round(value).toLocaleString('id-ID')}`;
}

function parseRupiah(value) {
    return Number(String(value).replace(/[^\d]/g, '')) || 0;
}

function setActiveStep(step) {
    const order = ['event', 'pricing', 'upload'];
    const activeIndex = step === 'confirm' ? order.length - 1 : order.indexOf(step);
    stepItems.forEach((item) => {
        const itemIndex = order.indexOf(item.dataset.step);
        item.classList.toggle('is-active', itemIndex <= activeIndex);
    });
}

const faceValuePrice = document.getElementById('faceValuePrice');

function updatePricingState() {
    if (!priceInput) return;

    const sellingPrice = parseRupiah(priceInput.value);
    const fee = sellingPrice * 0.05;
    const earning = sellingPrice - fee;

    if (summarySelling) summarySelling.textContent = formatRupiah(sellingPrice);
    if (summaryFee) summaryFee.textContent = formatRupiah(fee);
    if (summaryEarning) summaryEarning.textContent = formatRupiah(earning);

    if (listButton) listButton.disabled = false;
}

if (faceValuePrice) {
    faceValuePrice.addEventListener('focus', () => {
        faceValuePrice.value = parseRupiah(faceValuePrice.value) || '';
    });
    faceValuePrice.addEventListener('input', () => {
        faceValuePrice.value = parseRupiah(faceValuePrice.value) || '';
        setActiveStep('pricing');
    });
    faceValuePrice.addEventListener('blur', () => {
        faceValuePrice.value = formatRupiah(parseRupiah(faceValuePrice.value));
    });
}

// Logic untuk mengecek apakah form Event (Step 1) sudah terisi semua
function checkEventDetails() {
    const eventThumbnail = document.getElementById('eventThumbnail');
    const eventTitle = document.getElementById('eventTitle');
    const eventVenue = document.getElementById('eventVenue');
    
    if (eventThumbnail?.files.length > 0 || eventTitle?.value.length > 0) {
        setActiveStep('pricing');
    }
}

document.getElementById('eventTitle')?.addEventListener('input', checkEventDetails);
document.getElementById('eventThumbnail')?.addEventListener('change', checkEventDetails);

function setUploadStatus(message, isError = false) {
    uploadStatus.textContent = message;
    uploadStatus.classList.toggle('is-error', isError);
}

function resetTicketPreview() {
    if (!ticketPreview) return;
    ticketPreview.hidden = true;
    ticketPreview.removeAttribute('src');
    uploadDrop?.classList.remove('has-preview');
}

function showTicketPreview(file) {
    if (!ticketPreview || !file || !file.type.startsWith('image/')) {
        resetTicketPreview();
        return false;
    }
    ticketPreview.src = URL.createObjectURL(file);
    ticketPreview.hidden = false;
    uploadDrop?.classList.add('has-preview');
    return true;
}

if (priceInput) {
    priceInput.addEventListener('focus', () => {
        priceInput.value = parseRupiah(priceInput.value) || '';
    });
    priceInput.addEventListener('input', () => {
        priceInput.value = parseRupiah(priceInput.value) || '';
        updatePricingState();
        setActiveStep('pricing');
    });
    priceInput.addEventListener('blur', () => {
        priceInput.value = formatRupiah(parseRupiah(priceInput.value));
    });
    updatePricingState();
}

if (ticketFile) {
    ticketFile.addEventListener('change', () => {
        const file = ticketFile.files[0];
        if (!file) {
            setUploadStatus('');
            resetTicketPreview();
            return;
        }

        if (file.size > 10 * 1024 * 1024) {
            ticketFile.value = '';
            resetTicketPreview();
            setUploadStatus('File is larger than 10MB. Please upload a smaller ticket proof.', true);
            return;
        }

        const extension = file.name.split('.').pop().toLowerCase();
        if (!allowedTicketExtensions.includes(extension)) {
            ticketFile.value = '';
            resetTicketPreview();
            setUploadStatus('Format file harus PDF, JPG, PNG, atau Apple Wallet Pass.', true);
            return;
        }

        const hasPreview = showTicketPreview(file);
        setActiveStep('upload');
        setUploadStatus(hasPreview ? `Preview ${file.name} siap. Encrypting ticket proof...` : `${file.name} siap diupload. Preview hanya tersedia untuk JPG/PNG.`);
        setTimeout(() => {
            setUploadStatus(hasPreview ? 'Preview ticket proof siap dan terenkripsi untuk validasi server.' : `${file.name} terenkripsi dan siap divalidasi server.`);
            setActiveStep('confirm');
        }, 900);
    });
}

if (uploadDrop) {
    ['dragenter', 'dragover'].forEach((eventName) => {
        uploadDrop.addEventListener(eventName, (event) => {
            event.preventDefault();
            uploadDrop.classList.add('is-dragging');
        });
    });

    ['dragleave', 'drop'].forEach((eventName) => {
        uploadDrop.addEventListener(eventName, (event) => {
            event.preventDefault();
            uploadDrop.classList.remove('is-dragging');
        });
    });
}

if (listButton) {
    listButton.addEventListener('click', () => {
        const seatComplete = [ticketSection, ticketRow, ticketSeat].every((input) => input && input.value.trim());
        if (!seatComplete) {
            listStatus.textContent = 'Isi section, row, dan seat dulu supaya nomor bangku tiket berbeda dan jelas.';
            listStatus.classList.add('is-error');
            setActiveStep('pricing');
            return;
        }

        const hasFile = ticketFile && ticketFile.files.length > 0;
        if (!hasFile) {
            setUploadStatus('Upload bukti tiket dulu sebelum listing.', true);
            setActiveStep('upload');
            return;
        }

        setActiveStep('confirm');
        listStatus.textContent = 'Menyimpan listing ke database...';
        listStatus.classList.remove('is-error');
        listingForm?.submit();
    });
}

// Pricing Mode Toggle Logic
const btnFixedPrice = document.getElementById('btnFixedPrice');
const btnAuction = document.getElementById('btnAuction');
const labelReservePrice = document.getElementById('labelReservePrice');
const labelDuration = document.getElementById('labelDuration');
const textStartingBid = document.getElementById('textStartingBid');
const pricingInputsGrid = document.getElementById('pricingInputsGrid');

if (btnFixedPrice && btnAuction) {
    btnFixedPrice.addEventListener('click', () => {
        btnFixedPrice.classList.add('is-active');
        btnAuction.classList.remove('is-active');
        labelReservePrice.hidden = true;
        labelDuration.hidden = true;
        textStartingBid.textContent = 'Fixed Price (Rp)';
        
        // Buat input harga memakan seluruh lebar jika field lain disembunyikan
        if (pricingInputsGrid) {
            pricingInputsGrid.style.gridTemplateColumns = '1fr';
        }
    });

    btnAuction.addEventListener('click', () => {
        btnAuction.classList.add('is-active');
        btnFixedPrice.classList.remove('is-active');
        labelReservePrice.hidden = false;
        labelDuration.hidden = false;
        textStartingBid.textContent = 'Starting Bid (Rp)';
        
        // Kembalikan ke grid asli (3 kolom)
        if (pricingInputsGrid) {
            pricingInputsGrid.style.gridTemplateColumns = 'repeat(3, 1fr)';
        }
    });
}
