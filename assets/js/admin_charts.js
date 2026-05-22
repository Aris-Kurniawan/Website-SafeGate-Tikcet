/* assets/js/admin_charts.js - Script khusus Dashboard Admin SafeGate */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Logika untuk tab grafik di Overview Admin
    const tabs = document.querySelectorAll('.sg-admin-chart-tab');
    if (tabs.length > 0) {
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('is-active'));
                tab.classList.add('is-active');
                
                // Animasi update grafik (SVG line)
                const chartPath = document.querySelector('.sg-admin-sales-chart .line');
                const chartArea = document.querySelector('.sg-admin-sales-chart .area');
                const chartPoints = document.querySelectorAll('.sg-admin-sales-chart .points circle');
                
                if (chartPath && chartArea) {
                    chartPath.style.opacity = '0';
                    chartArea.style.opacity = '0';
                    
                    setTimeout(() => {
                        // Ubah sedikit koordinat point untuk simulasi data baru
                        if (tab.innerText === '90D') {
                            chartPath.setAttribute('d', 'M0 200 C70 180 150 250 250 180 S350 90 450 140 S550 50 650 90 S700 130 720 100');
                            chartArea.setAttribute('d', 'M0 200 C70 180 150 250 250 180 S350 90 450 140 S550 50 650 90 S700 130 720 100 L720 300 L0 300Z');
                            
                            // Update koordinat lingkaran point
                            const coords = [[0, 200], [250, 180], [450, 140], [650, 90], [720, 100]];
                            chartPoints.forEach((point, idx) => {
                                if (coords[idx]) {
                                    point.setAttribute('cx', coords[idx][0]);
                                    point.setAttribute('cy', coords[idx][1]);
                                }
                            });
                        } else {
                            // Kembalikan ke data 30D default
                            chartPath.setAttribute('d', 'M0 240 C70 210 120 140 220 110 S320 230 420 170 S520 80 620 90 S690 190 720 160');
                            chartArea.setAttribute('d', 'M0 240 C70 210 120 140 220 110 S320 230 420 170 S520 80 620 90 S690 190 720 160 L720 300 L0 300Z');
                            
                            const coords = [[0, 240], [220, 110], [420, 170], [620, 90], [720, 160]];
                            chartPoints.forEach((point, idx) => {
                                if (coords[idx]) {
                                    point.setAttribute('cx', coords[idx][0]);
                                    point.setAttribute('cy', coords[idx][1]);
                                }
                            });
                        }
                        
                        chartPath.style.transition = 'all 0.5s ease';
                        chartArea.style.transition = 'all 0.5s ease';
                        chartPath.style.opacity = '1';
                        chartArea.style.opacity = '1';
                    }, 150);
                }
            });
        });
    }

    // 2. Interaksi Aksi Dispute (Resolve / Reject)
    const resolveButtons = document.querySelectorAll('.js-dispute-resolve');
    const refundButtons = document.querySelectorAll('.js-dispute-refund');
    
    resolveButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            const disputeId = e.currentTarget.getAttribute('data-id');
            const statusCell = document.getElementById(`dispute-status-${disputeId}`);
            if (statusCell) {
                statusCell.className = 'sg-admin-status-badge success';
                statusCell.innerHTML = '<iconify-icon icon="ph:check-circle"></iconify-icon> Resolved';
                e.currentTarget.disabled = true;
                const refundBtn = document.querySelector(`.js-dispute-refund[data-id="${disputeId}"]`);
                if (refundBtn) refundBtn.disabled = true;
                
                showToastNotification('Sengketa Berhasil Diselesaikan (Dana Diteruskan ke Penjual)');
            }
        });
    });

    refundButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            const disputeId = e.currentTarget.getAttribute('data-id');
            const statusCell = document.getElementById(`dispute-status-${disputeId}`);
            if (statusCell) {
                statusCell.className = 'sg-admin-status-badge danger';
                statusCell.innerHTML = '<iconify-icon icon="ph:arrow-counter-clockwise"></iconify-icon> Refunded';
                e.currentTarget.disabled = true;
                const resolveBtn = document.querySelector(`.js-dispute-resolve[data-id="${disputeId}"]`);
                if (resolveBtn) resolveBtn.disabled = true;
                
                showToastNotification('Dana Escrow Berhasil Direfund ke Pembeli');
            }
        });
    });

    // 3. Interaksi Persetujuan KYC (Approve / Reject)
    const approveKycBtns = document.querySelectorAll('.js-kyc-approve');
    const rejectKycBtns = document.querySelectorAll('.js-kyc-reject');

    approveKycBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            const kycId = e.currentTarget.getAttribute('data-id');
            const statusCell = document.getElementById(`kyc-status-${kycId}`);
            if (statusCell) {
                statusCell.className = 'sg-admin-status-badge success';
                statusCell.innerHTML = '<iconify-icon icon="ph:seal-check-fill"></iconify-icon> Approved';
                e.currentTarget.disabled = true;
                const rejectBtn = document.querySelector(`.js-kyc-reject[data-id="${kycId}"]`);
                if (rejectBtn) rejectBtn.disabled = true;
                
                showToastNotification('KYC Vendor Berhasil Disetujui');
            }
        });
    });

    rejectKycBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            const kycId = e.currentTarget.getAttribute('data-id');
            const statusCell = document.getElementById(`kyc-status-${kycId}`);
            if (statusCell) {
                statusCell.className = 'sg-admin-status-badge danger';
                statusCell.innerHTML = '<iconify-icon icon="ph:x-circle-fill"></iconify-icon> Rejected';
                e.currentTarget.disabled = true;
                const approveBtn = document.querySelector(`.js-kyc-approve[data-id="${kycId}"]`);
                if (approveBtn) approveBtn.disabled = true;
                
                showToastNotification('KYC Vendor Ditolak & Notifikasi Dikirim');
            }
        });
    });
});

// Toast Helper
function showToastNotification(message) {
    // Check if toast already exists
    let toast = document.getElementById('admin-toast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'admin-toast';
        toast.style.position = 'fixed';
        toast.style.bottom = '24px';
        toast.style.right = '24px';
        toast.style.background = '#0a0d14';
        toast.style.border = '1px solid #D9FF00';
        toast.style.color = '#fff';
        toast.style.padding = '16px 24px';
        toast.style.borderRadius = '8px';
        toast.style.boxShadow = '0 10px 30px rgba(0,0,0,0.5)';
        toast.style.zIndex = '9999';
        toast.style.fontFamily = 'Inter, sans-serif';
        toast.style.fontSize = '14px';
        toast.style.fontWeight = 'bold';
        toast.style.transition = 'all 0.3s ease';
        toast.style.transform = 'translateY(100px)';
        toast.style.opacity = '0';
        toast.style.display = 'flex';
        toast.style.alignItems = 'center';
        toast.style.gap = '10px';
        
        document.body.appendChild(toast);
    }
    
    toast.innerHTML = `<iconify-icon icon="ph:info-bold" style="color:#D9FF00; font-size:18px;"></iconify-icon> ${message}`;
    
    // Animate In
    toast.style.transform = 'translateY(0)';
    toast.style.opacity = '1';
    
    // Animate Out
    setTimeout(() => {
        toast.style.transform = 'translateY(100px)';
        toast.style.opacity = '0';
    }, 3000);
}
