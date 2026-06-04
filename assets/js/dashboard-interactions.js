(() => {
    const body = document.body;
    const rupiahFormatter = new Intl.NumberFormat('id-ID');

    function qs(selector, root = document) {
        return root.querySelector(selector);
    }

    function qsa(selector, root = document) {
        return Array.from(root.querySelectorAll(selector));
    }

    function parseNumber(value) {
        return Number(String(value || '').replace(/[^\d]/g, '')) || 0;
    }

    function formatRupiah(value) {
        return `Rp ${rupiahFormatter.format(Math.max(0, Math.round(value)))}`;
    }

    function showToast(message, type = 'success') {
        let toast = qs('.sg-dashboard-toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.className = 'sg-dashboard-toast';
            toast.setAttribute('role', 'status');
            toast.setAttribute('aria-live', 'polite');
            document.body.appendChild(toast);
        }

        toast.textContent = message;
        toast.dataset.type = type;
        toast.classList.add('is-visible');
        window.clearTimeout(showToast.timer);
        showToast.timer = window.setTimeout(() => {
            toast.classList.remove('is-visible');
        }, 2600);
    }

    function openModal(title, rows) {
        let modal = qs('.sg-dashboard-modal');
        if (!modal) {
            modal = document.createElement('div');
            modal.className = 'sg-dashboard-modal';
            modal.innerHTML = `
                <div class="sg-dashboard-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="sgModalTitle">
                    <button class="sg-dashboard-modal__close" type="button" aria-label="Close">
                        <iconify-icon icon="ph:x"></iconify-icon>
                    </button>
                    <h2 id="sgModalTitle"></h2>
                    <dl></dl>
                </div>
            `;
            document.body.appendChild(modal);
            modal.addEventListener('click', (event) => {
                if (event.target === modal || event.target.closest('.sg-dashboard-modal__close')) {
                    modal.classList.remove('is-open');
                }
            });
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    modal.classList.remove('is-open');
                }
            });
        }

        qs('h2', modal).textContent = title;
        qs('dl', modal).innerHTML = rows.map(([label, value]) => (
            `<div><dt>${label}</dt><dd>${value}</dd></div>`
        )).join('');
        modal.classList.add('is-open');
    }

    function initOverview() {
        if (!body.classList.contains('sg-page-overview')) return;

        const chart = qs('.sg-sales-chart svg');
        const line = qs('.line', chart);
        const area = qs('.area', chart);
        const points = qsa('.points circle', chart);
        const datasets = {
            '30D': {
                line: 'M0 255 C70 235 78 150 135 132 S235 48 300 72 S390 205 455 170 S560 82 620 112 S685 212 720 220',
                area: 'M0 255 C70 235 78 150 135 132 S235 48 300 72 S390 205 455 170 S560 82 620 112 S685 212 720 220 L720 300 L0 300Z',
                points: [[0,255], [135,132], [300,72], [455,170], [560,112], [620,112], [685,212]],
            },
            '90D': {
                line: 'M0 230 C80 190 120 208 165 142 S285 126 335 92 S455 152 500 116 S610 60 720 94',
                area: 'M0 230 C80 190 120 208 165 142 S285 126 335 92 S455 152 500 116 S610 60 720 94 L720 300 L0 300Z',
                points: [[0,230], [165,142], [335,92], [500,116], [610,60], [720,94]],
            },
        };

        qsa('.sg-chart-tabs button').forEach((button) => {
            button.addEventListener('click', () => {
                qsa('.sg-chart-tabs button').forEach((item) => item.classList.remove('is-active'));
                button.classList.add('is-active');
                const data = datasets[button.textContent.trim()] || datasets['30D'];
                line?.setAttribute('d', data.line);
                area?.setAttribute('d', data.area);
                points.forEach((point, index) => {
                    const coordinate = data.points[index] || data.points[data.points.length - 1];
                    point.setAttribute('cx', coordinate[0]);
                    point.setAttribute('cy', coordinate[1]);
                    point.style.display = index < data.points.length ? '' : 'none';
                });
                showToast(`Grafik diperbarui ke ${button.textContent.trim()}.`);
            });
        });

        qsa('.sg-alert-panel p').forEach((alert) => {
            alert.addEventListener('click', () => {
                alert.classList.toggle('is-muted');
                showToast(alert.classList.contains('is-muted') ? 'Alert ditandai selesai.' : 'Alert dibuka kembali.');
            });
        });
    }

    function initTransactions() {
        if (!body.classList.contains('sg-page-transaction')) return;

        const form = qs('.sg-filter-bar');
        const search = qs('input[name="q"]', form);
        const statusSelect = qs('select[name="status"]', form);
        const rows = qsa('.sg-transaction-row');
        const footer = qs('.sg-table-footer strong');

        function updateRows() {
            const keyword = search.value.trim().toLowerCase();
            const status = statusSelect.value.toLowerCase();
            let visibleCount = 0;

            rows.forEach((row) => {
                const title = qs('.sg-event-cell h2', row)?.textContent.toLowerCase() || '';
                const id = qs('.sg-event-cell p', row)?.textContent.toLowerCase() || '';
                const rowStatus = qs('.sg-status', row)?.textContent.trim().toLowerCase() || '';
                const matchesKeyword = !keyword || `${title} ${id}`.includes(keyword);
                const matchesStatus = status === 'all status' || rowStatus.includes(status);
                const isVisible = matchesKeyword && matchesStatus;
                row.hidden = !isVisible;
                if (isVisible) visibleCount += 1;
            });

            if (footer) {
                footer.textContent = `Showing ${visibleCount ? 1 : 0} to ${visibleCount} of ${visibleCount} transactions`;
            }
        }

        search?.addEventListener('input', updateRows);
        statusSelect?.addEventListener('change', updateRows);

        qsa('.sg-details-button').forEach((button) => {
            button.addEventListener('click', () => {
                const row = button.closest('.sg-transaction-row');
                openModal('Transaction Details', [
                    ['Event', qs('.sg-event-cell h2', row)?.textContent || '-'],
                    ['ID', qs('.sg-event-cell p', row)?.textContent.replace('ID: ', '') || '-'],
                    ['Date', qs('.sg-date-cell strong', row)?.textContent || '-'],
                    ['Amount', qs('.sg-amount-cell strong', row)?.textContent || '-'],
                    ['Status', qs('.sg-status', row)?.textContent.trim() || '-'],
                ]);
            });
        });

        qsa('.sg-pagination button:not([disabled])').forEach((button) => {
            button.addEventListener('click', () => {
                qsa('.sg-pagination button').forEach((item) => item.classList.remove('is-active'));
                if (!/[‹›]/.test(button.textContent.trim())) {
                    button.classList.add('is-active');
                }
            });
        });
    }

    function initWallet() {
        if (!body.classList.contains('sg-page-wallet')) return;

        const panel = qs('.sg-withdraw-panel');
        const method = qs('select', panel);
        const inputs = qsa('input', panel);
        const destination = inputs[0];
        const amount = inputs[1];
        const button = qs('button', panel);

        function validate() {
            const value = parseNumber(amount.value);
            const valid = destination.value.trim().length >= 4 && value >= 60000;
            button.disabled = !valid;
            button.classList.toggle('is-ready', valid);
        }

        destination?.addEventListener('input', validate);
        amount?.addEventListener('input', () => {
            amount.value = parseNumber(amount.value) || '';
            validate();
        });
        amount?.addEventListener('blur', () => {
            const value = parseNumber(amount.value);
            amount.value = value ? formatRupiah(value) : '';
        });

        button?.addEventListener('click', validate);
    }

    function initActiveListings() {
        if (!body.classList.contains('sg-page-active_listings')) return;

        qsa('.sg-listing-card').forEach((card) => {
            const button = qs(':scope > button', card);
            button?.addEventListener('click', () => {
                let actions = qs('.sg-listing-actions', card);
                if (!actions) {
                    actions = document.createElement('div');
                    actions.className = 'sg-listing-actions';
                    actions.innerHTML = `
                        <button type="button" data-action="promote">Promote</button>
                        <button type="button" data-action="pause">Pause</button>
                        <button type="button" data-action="edit">Edit</button>
                    `;
                    card.appendChild(actions);
                    actions.addEventListener('click', (event) => {
                        const actionButton = event.target.closest('button');
                        if (!actionButton) return;
                        const title = qs('h2', card)?.textContent || 'Listing';
                        const action = actionButton.dataset.action;
                        showToast(`${title}: ${action}`);
                    });
                }
                actions.classList.toggle('is-open');
                card.classList.toggle('is-expanded', actions.classList.contains('is-open'));
            });
        });
    }

    function initSettings() {
        if (!body.classList.contains('sg-page-settings')) return;

        const kycPanel = qs('.sg-kyc-panel');
        const kycForm = kycPanel;
        const nik = qs('input[name="nik"]', kycPanel);
        const submit = qs('button[type="submit"]', kycPanel);
        const danger = qs('.sg-danger-label', kycPanel);
        const uploadDrops = qsa('.sg-doc-drop', kycPanel).map((drop) => {
            const input = qs('input[type="file"]', drop);
            const preview = qs('img', drop);
            const title = qs('strong', drop);
            const status = qs('small', drop);
            const acceptsPdf = input?.accept?.toLowerCase().includes('pdf') || false;

            return {
                drop,
                input,
                preview,
                title,
                status,
                defaultTitle: title?.textContent || 'Drag & drop or click to upload',
                defaultStatus: status?.textContent || '',
                allowedExtensions: acceptsPdf ? ['jpg', 'jpeg', 'png', 'pdf'] : ['jpg', 'jpeg', 'png'],
                previewLabel: input?.name === 'selfie_photo' ? 'Preview selfie siap.' : 'Preview KTP siap.',
                missingMessage: input?.name === 'selfie_photo' ? 'Upload selfie dengan KTP dulu sebelum submit.' : 'Upload dokumen KTP dulu sebelum submit.',
            };
        });

        function validateKyc() {
            const validNik = nik ? /^\d{16}$/.test(nik.value.trim()) : false;
            const missingUpload = uploadDrops.find(({ input }) => input && !input.disabled && input.files.length === 0);
            const hasUploads = !missingUpload;
            submit?.classList.toggle('is-ready', validNik && hasUploads);
            return { validNik, hasUploads, missingUpload };
        }

        function setKycStatus(target, message, isError = false) {
            const status = target?.status || null;
            if (!status) return;
            status.textContent = message;
            status.style.color = isError ? '#ff6868' : '';
        }

        function resetKycPreview(target, resetText = false) {
            if (!target) return;
            const { preview, drop, title } = target;
            if (preview) {
                preview.hidden = true;
                preview.style.display = 'none';
                preview.removeAttribute('src');
            }
            drop?.classList.remove('has-preview');
            if (resetText) {
                if (title) title.textContent = target.defaultTitle;
                setKycStatus(target, target.defaultStatus);
            }
        }

        nik?.addEventListener('input', () => {
            nik.value = nik.value.replace(/[^\d]/g, '').slice(0, 16);
            validateKyc();
        });

        function handleKycFile(file, target) {
            if (!target) return;
            const { input, preview, drop, title } = target;

            if (!file) {
                resetKycPreview(target, true);
                validateKyc();
                return;
            }

            const extension = file.name.split('.').pop().toLowerCase();
            if (!target.allowedExtensions.includes(extension)) {
                input.value = '';
                resetKycPreview(target);
                if (title) title.textContent = target.defaultTitle;
                setKycStatus(target, target.allowedExtensions.includes('pdf') ? 'Format harus JPG, PNG, atau PDF.' : 'Format harus JPG atau PNG.', true);
                validateKyc();
                return;
            }

            if (file.size > 5 * 1024 * 1024) {
                input.value = '';
                resetKycPreview(target);
                if (title) title.textContent = target.defaultTitle;
                setKycStatus(target, 'Ukuran file maksimal 5MB.', true);
                validateKyc();
                return;
            }

            if (title) title.textContent = file.name;
            const isImage = ['jpg', 'jpeg', 'png'].includes(extension);
            if (isImage && preview) {
                const reader = new FileReader();
                reader.onload = () => {
                    preview.src = reader.result;
                    preview.hidden = false;
                    preview.style.display = 'block';
                    drop?.classList.add('has-preview');
                };
                reader.readAsDataURL(file);
                setKycStatus(target, `${target.previewLabel} Dokumen bisa dikirim untuk verifikasi.`);
            } else {
                resetKycPreview(target);
                if (title) title.textContent = file.name;
                setKycStatus(target, `${file.name} siap dikirim untuk verifikasi. Preview hanya tersedia untuk JPG/PNG.`);
            }
            validateKyc();
        }

        uploadDrops.forEach((target) => {
            const { drop, input } = target;

            drop?.addEventListener('click', (event) => {
                if (event.target === input) return;
                input?.click();
            });

            input?.addEventListener('change', () => {
                handleKycFile(input.files[0], target);
            });

            ['dragenter', 'dragover'].forEach((eventName) => {
                drop?.addEventListener(eventName, (event) => {
                    event.preventDefault();
                    drop.classList.add('is-dragging');
                });
            });

            ['dragleave', 'drop'].forEach((eventName) => {
                drop?.addEventListener(eventName, (event) => {
                    event.preventDefault();
                    drop.classList.remove('is-dragging');
                });
            });

            drop?.addEventListener('drop', (event) => {
                const file = event.dataTransfer?.files?.[0];
                if (!file || !input) return;
                const transfer = new DataTransfer();
                transfer.items.add(file);
                input.files = transfer.files;
                handleKycFile(file, target);
            });
        });

        kycForm?.addEventListener('submit', (event) => {
            const { validNik, hasUploads, missingUpload } = validateKyc();
            if (!validNik || !hasUploads) {
                event.preventDefault();
                const message = !validNik ? 'Isi NIK 16 digit dulu sebelum submit KYC.' : missingUpload?.missingMessage || 'Upload dokumen KYC dulu sebelum submit.';
                if (missingUpload) setKycStatus(missingUpload, message, true);
                showToast(message, 'error');
                return;
            }
            if (danger) {
                danger.innerHTML = '<iconify-icon icon="ph:clock"></iconify-icon> Status: Waiting Review';
                danger.classList.add('is-warning');
            }
            showToast('Dokumen KYC sedang dikirim untuk review admin.');
        });
        validateKyc();

        const profilePanel = qs('.sg-profile-panel');
        const profileInputs = qsa('input', profilePanel);
        profileInputs.forEach((input) => {
            if (input.name) {
                input.readOnly = false;
            }
        });


    }

    document.addEventListener('DOMContentLoaded', () => {
        initOverview();
        initTransactions();
        initWallet();
        initActiveListings();
        initSettings();
    });
})();
