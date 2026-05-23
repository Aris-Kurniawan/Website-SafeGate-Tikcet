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
});
