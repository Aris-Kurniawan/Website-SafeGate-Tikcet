// File: assets/js/auth_animation.js
// Handles the smooth swapping animation between Login and Sign Up pages without delay.

function triggerAuthTransition(targetUrl) {
    // Langsung pindah ke halaman tujuan untuk menghilangkan delay.
    // Animasi pergerakan akan ditangani sepenuhnya saat halaman baru dimuat (slide in).
    window.location.href = targetUrl;
}

document.addEventListener('DOMContentLoaded', () => {
    const formCol = document.getElementById('auth-form');
    const visualCol = document.getElementById('auth-visual');

    if (!formCol || !visualCol) return;

    const isSignup = window.location.href.includes('signup');

    // Hilangkan transisi sementara untuk mengatur posisi awal masuk
    formCol.style.transition = 'none';
    visualCol.style.transition = 'none';

    // Atur posisi awal animasi masuk (Slide in)
    // Di halaman Signup, form di kanan, kita mulai dari kiri (-100%) agar terlihat meneruskan gerakan.
    // Di halaman Login, form di kiri, kita mulai dari kanan (100%).
    formCol.style.transform = isSignup ? 'translateX(-100%)' : 'translateX(100%)';
    if (visualCol) {
        visualCol.style.transform = isSignup ? 'translateX(100%)' : 'translateX(-100%)';
    }

    // Catatan: Opacity tidak diatur ke 0 agar tidak ada efek berkedip (flicker) / mengulang dari transparan.

    // Force reflow agar browser menerapkan posisi awal sebelum animasi berjalan
    void formCol.offsetWidth;

    // Transisi cepat dan smooth (0.45s) menggunakan ease-out
    const transitionStyle = 'transform 0.45s cubic-bezier(0.22, 1, 0.36, 1)';

    formCol.style.transition = transitionStyle;
    visualCol.style.transition = transitionStyle;

    // Jalankan pergeseran ke posisi aslinya
    formCol.style.transform = 'translateX(0)';
    if (visualCol) {
        visualCol.style.transform = 'translateX(0)';
    }
});

// Fungsi untuk menampilkan/menyembunyikan password
function togglePasswordVisibility(inputId, iconId) {
    const passwordInput = document.getElementById(inputId);
    const toggleIcon = document.getElementById(iconId);
    if (!passwordInput || !toggleIcon) return;

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.setAttribute('icon', 'ph:eye');
    } else {
        passwordInput.type = 'password';
        toggleIcon.setAttribute('icon', 'ph:eye-slash');
    }
}
