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

        form?.addEventListener('submit', (event) => event.preventDefault());
        search?.addEventListener('input', updateRows);
        statusSelect?.addEventListener('change', updateRows);
        qs('select[name="date_range"]', form)?.addEventListener('change', () => {
            showToast('Filter tanggal siap disambungkan ke database.');
        });

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

        qs('.sg-icon-button')?.addEventListener('click', () => {
            const csv = ['Event,ID,Date,Type,Amount,Status'].concat(rows.map((row) => [
                qs('.sg-event-cell h2', row)?.textContent || '',
                qs('.sg-event-cell p', row)?.textContent.replace('ID: ', '') || '',
                qs('.sg-date-cell strong', row)?.textContent || '',
                qs('.sg-type-pill', row)?.textContent || '',
                qs('.sg-amount-cell strong', row)?.textContent || '',
                qs('.sg-status', row)?.textContent.trim() || '',
            ].map((value) => `"${value.replace(/"/g, '""')}"`).join(','))).join('\n');

            const link = document.createElement('a');
            link.href = URL.createObjectURL(new Blob([csv], { type: 'text/csv' }));
            link.download = 'safegate-transactions.csv';
            link.click();
            URL.revokeObjectURL(link.href);
            showToast('CSV transaksi dibuat.');
        });

        qsa('.sg-pagination button:not([disabled])').forEach((button) => {
            button.addEventListener('click', () => {
                qsa('.sg-pagination button').forEach((item) => item.classList.remove('is-active'));
                if (!/[‹›]/.test(button.textContent.trim())) {
                    button.classList.add('is-active');
                }
                showToast('Pagination demo siap disambungkan ke database.');
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
        const tbody = qs('.sg-withdraw-table tbody');

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

        button?.addEventListener('click', () => {
            const value = parseNumber(amount.value);
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>Hari ini</td>
                <td>${method.value.split(' ')[0]}</td>
                <td><strong>${formatRupiah(value)}</strong></td>
                <td><span class="sg-withdraw-status is-processing">Processing</span></td>
            `;
            tbody?.prepend(row);
            destination.value = '';
            amount.value = '';
            validate();
            showToast('Permintaan tarik dana masuk antrean verifikasi.');
        });

        qs('.sg-withdraw-table a')?.addEventListener('click', (event) => {
            event.preventDefault();
            showToast('Riwayat lengkap siap ditarik dari database.');
        });
    }

    function initActiveListings() {
        if (!body.classList.contains('sg-page-active_listings')) return;

        qsa('.sg-listing-card').forEach((card) => {
            const button = qs('button', card);
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
                        showToast(`${title}: ${action} siap disambungkan ke backend.`);
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
        const nik = qs('input', kycPanel);
        const submit = qs('button', kycPanel);
        const drop = qs('.sg-doc-drop', kycPanel);
        const danger = qs('.sg-danger-label', kycPanel);
        const fileInput = document.createElement('input');
        fileInput.type = 'file';
        fileInput.accept = '.jpg,.jpeg,.png,.pdf';
        fileInput.hidden = true;
        drop?.appendChild(fileInput);

        function validateKyc() {
            const validNik = /^\d{16}$/.test(nik.value.trim());
            const hasFile = fileInput.files.length > 0;
            submit.disabled = !(validNik && hasFile);
            submit.classList.toggle('is-ready', validNik && hasFile);
        }

        nik?.addEventListener('input', () => {
            nik.value = nik.value.replace(/[^\d]/g, '').slice(0, 16);
            validateKyc();
        });

        drop?.addEventListener('click', () => fileInput.click());
        fileInput.addEventListener('change', () => {
            const file = fileInput.files[0];
            if (!file) return;
            qs('strong', drop).textContent = file.name;
            qs('small', drop).textContent = 'Dokumen siap dikirim untuk verifikasi.';
            validateKyc();
        });

        submit?.addEventListener('click', () => {
            danger.innerHTML = '<iconify-icon icon="ph:clock"></iconify-icon> Status: Waiting Review';
            danger.classList.add('is-warning');
            showToast('Dokumen KYC tersimpan untuk review admin.');
        });
        validateKyc();

        const profilePanel = qs('.sg-profile-panel');
        const editButton = qs('.sg-panel-title-row button', profilePanel);
        const profileInputs = qsa('input', profilePanel);
        profileInputs.forEach((input) => input.readOnly = true);
        editButton?.addEventListener('click', () => {
            const editing = profilePanel.classList.toggle('is-editing');
            profileInputs.forEach((input) => input.readOnly = !editing);
            editButton.innerHTML = editing
                ? '<iconify-icon icon="ph:check"></iconify-icon>'
                : '<iconify-icon icon="ph:pencil-simple"></iconify-icon>';
            showToast(editing ? 'Mode edit profil aktif.' : 'Perubahan profil tersimpan lokal.');
        });

        qs('.sg-change-photo')?.addEventListener('click', () => {
            showToast('Upload foto profil siap disambungkan ke storage.');
        });

        qsa('.sg-security-grid button').forEach((button) => {
            button.addEventListener('click', () => {
                showToast(`${button.textContent.trim()} siap disambungkan ke auth backend.`);
            });
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
