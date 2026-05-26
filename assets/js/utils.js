// assets/js/utils.js - Fungsi Bantuan (Utility) Global SafeGate

/**
 * Memformat angka menjadi format rupiah (misal: 1000000 -> Rp 1.000.000)
 * @param {number|string} number - Angka yang akan diformat
 * @returns {string} - Hasil format rupiah
 */
function formatRupiah(number) {
    if (number === undefined || number === null) return 'Rp 0';
    const parsed = parseInt(number, 10);
    if (isNaN(parsed)) return 'Rp 0';
    return 'Rp ' + parsed.toLocaleString('id-ID');
}

/**
 * Menambahkan format rupiah ke elemen HTML dengan atribut data-rupiah
 */
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("[data-rupiah]").forEach(el => {
        const val = el.getAttribute("data-rupiah");
        el.textContent = formatRupiah(val);
    });

    const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    const revealTargets = document.querySelectorAll([
        ".sg-glass",
        ".sg-panel",
        ".sg-summary-card",
        ".sg-ticket-card",
        ".card-hover",
        ".sg-wallet-balance-card",
        ".sg-metric-card",
        ".sg-admin-card",
        ".sg-table-card",
        ".sg-sell-card",
        ".sg-settings-card"
    ].join(","));

    if (reduceMotion || !("IntersectionObserver" in window)) {
        revealTargets.forEach(el => el.classList.add("is-visible"));
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            entry.target.classList.add("is-visible");
            observer.unobserve(entry.target);
        });
    }, {
        threshold: 0.08,
        rootMargin: "0px 0px -24px 0px"
    });

    revealTargets.forEach((el, index) => {
        el.classList.add("sg-lift-in");
        el.style.transitionDelay = `${Math.min(index % 6, 5) * 35}ms`;
        observer.observe(el);
    });
});
