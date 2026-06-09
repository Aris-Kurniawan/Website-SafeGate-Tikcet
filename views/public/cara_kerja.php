<?php
// 1. Definisikan judul halaman
$page_title = "Cara Kerja - Protocol SafeGate";

// 2. Mulai menangkap output konten (Output Buffering)
ob_start();
?>

<!-- KONTEN UTAMA HALAMAN CARA KERJA -->
<main class="container mx-auto py-4"
    style="max-width: 1200px; padding-left: 1.5rem; padding-right: 1.5rem; margin-bottom: 5rem;">
    <div class="row align-items-center" style="row-gap: 4rem;">

        <!-- Left Column: Protocol Details -->
        <div class="col-12 col-lg-6 d-flex flex-column align-items-start" style="animation: fadeInUp 0.8s ease;">

            <!-- Hero Header -->
            <h1 class="display-4 fw-bold text-white mb-4" style="line-height: 1.15; letter-spacing: -0.04em;">
                Protocol <span class="text-safegate-neon">SafeGate</span>
            </h1>

            <p class="text-safegate-text-sec mb-5" style="font-size: 1.05rem; line-height: 1.6; max-width: 32rem;">
                We've built a proprietary verification layer that eliminates the risks of the secondary market. <span
                    class="text-white fw-bold">Institutional-grade safety, consumer-grade experience.</span>
            </p>

            <!-- Features Stack -->
            <div class="d-flex flex-column gap-5 w-100">

                <!-- Feature 1: Multi-Step Verification -->
                <div class="d-flex gap-4 align-items-start">
                    <div class="safegate-logo-box bg-transparent text-safegate-neon border d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width: 56px; height: 56px; border-radius: 14px; border-color: rgba(255,255,255,0.06) !important; background-color: rgba(255,255,255,0.03) !important; box-shadow: none;">
                        <iconify-icon icon="ph:shield-check-bold" class="fs-3 text-safegate-neon"></iconify-icon>
                    </div>
                    <div>
                        <h3 class="h5 fw-bold text-white mb-2" style="letter-spacing: -0.02em;">Multi-Step Verification
                        </h3>
                        <p class="text-safegate-text-sec mb-0"
                            style="font-size: 0.92rem; line-height: 1.6; max-width: 28rem;">
                            Setiap tiket diverifikasi secara kriptografis terhadap database penerbit utama sebelum dapat
                            ditampilkan pada grid kami.
                        </p>
                    </div>
                </div>

                <!-- Feature 2: Escrow Protection -->
                <div class="d-flex gap-4 align-items-start">
                    <div class="safegate-logo-box bg-transparent text-safegate-neon border d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width: 56px; height: 56px; border-radius: 14px; border-color: rgba(255,255,255,0.06) !important; background-color: rgba(255,255,255,0.03) !important; box-shadow: none;">
                        <iconify-icon icon="ph:arrows-counter-clockwise-bold"
                            class="fs-3 text-safegate-neon"></iconify-icon>
                    </div>
                    <div>
                        <h3 class="h5 fw-bold text-white mb-2" style="letter-spacing: -0.02em;">Escrow Protection</h3>
                        <p class="text-safegate-text-sec mb-0"
                            style="font-size: 0.92rem; line-height: 1.6; max-width: 28rem;">
                            Dana Anda tersimpan dalam brankas digital yang aman. Kami hanya akan meneruskan pembayaran
                            kepada penjual setelah Anda berhasil memindai tiket di lokasi acara.
                        </p>
                    </div>
                </div>

                <!-- Feature 3: Price Ceiling Enforcement -->
                <div class="d-flex gap-4 align-items-start">
                    <div class="safegate-logo-box bg-transparent text-safegate-neon border d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width: 56px; height: 56px; border-radius: 14px; border-color: rgba(255,255,255,0.06) !important; background-color: rgba(255,255,255,0.03) !important; box-shadow: none;">
                        <iconify-icon icon="ph:trend-down-bold" class="fs-3 text-safegate-neon"></iconify-icon>
                    </div>
                    <div>
                        <h3 class="h5 fw-bold text-white mb-2" style="letter-spacing: -0.02em;">Price Ceiling
                            Enforcement</h3>
                        <p class="text-safegate-text-sec mb-0"
                            style="font-size: 0.92rem; line-height: 1.6; max-width: 28rem;">
                            Resale prices are hard-capped. We eliminate scalping by strictly preventing listings from
                            exceeding original face value plus verification fees.
                        </p>
                    </div>
                </div>

            </div>
        </div>

        <!-- Right Column: Glassmorphic Security Analytics Card -->
        <div class="col-12 col-lg-6 d-flex justify-content-center justify-content-lg-end"
            style="animation: fadeInUp 1s ease;">
            <div class="position-relative w-100" style="max-width: 500px;">

                <!-- Ambient Glow Behind Card -->
                <div class="position-absolute bg-safegate-neon rounded-circle"
                    style="opacity: 0.08; filter: blur(90px); top: 10%; left: 10%; right: 10%; bottom: 10%; z-index: -1;">
                </div>

                <!-- Glassmorphism Card -->
                <div class="rounded-4 p-5" style="
                    background: rgba(15, 20, 28, 0.45); 
                    backdrop-filter: blur(24px); 
                    -webkit-backdrop-filter: blur(24px);
                    border: 1px solid rgba(255,255,255,0.07);
                    box-shadow: 0 32px 64px rgba(0,0,0,0.5), inset 0 1px 1px rgba(255,255,255,0.05);
                    transition: transform 0.4s ease;
                " onmouseover="this.style.transform='translateY(-6px)'" onmouseout="this.style.transform='none'">

                    <!-- Card Header -->
                    <div class="d-flex align-items-center justify-content-between mb-5">
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center justify-content-center rounded-circle bg-safegate-neon flex-shrink-0"
                                style="width: 36px; height: 36px; box-shadow: 0 0 10px rgba(217, 255, 0, 0.35);">
                                <iconify-icon icon="ph:cpu-fill" class="text-black fs-5"></iconify-icon>
                            </div>
                            <span class="fw-bold text-white fs-6"
                                style="letter-spacing: 0.06em; font-family: 'Inter', sans-serif;">SECURITY
                                ANALYTICS</span>
                        </div>
                        <span class="badge text-black fw-bold px-2 py-1"
                            style="background-color: var(--safegate-neon); font-size: 0.65rem; border-radius: 4px; letter-spacing: 0.05em;">ACTIVE</span>
                    </div>

                    <!-- Metric 1: Verification Rate -->
                    <div class="mb-5">
                        <div class="d-flex justify-content-between align-items-end mb-2">
                            <span class="text-safegate-text-sec fw-bold"
                                style="font-size: 0.72rem; letter-spacing: 0.1em; text-transform: uppercase;">Verification
                                Rate</span>
                            <span class="text-safegate-neon fw-bold"
                                style="font-size: 0.95rem; letter-spacing: -0.01em;">99.8% SUCCESS</span>
                        </div>
                        <div class="w-100 rounded-pill"
                            style="height: 4px; background-color: rgba(255,255,255,0.08); overflow: hidden;">
                            <div class="h-100 bg-safegate-neon rounded-pill animate-bar-99"
                                style="width: 99.8%; box-shadow: 0 0 10px rgba(217, 255, 0, 0.8);"></div>
                        </div>
                    </div>

                    <!-- Metric 2: Escrow Liquidity -->
                    <div class="mb-5">
                        <div class="d-flex justify-content-between align-items-end mb-2">
                            <span class="text-safegate-text-sec fw-bold"
                                style="font-size: 0.72rem; letter-spacing: 0.1em; text-transform: uppercase;">Escrow
                                Liquidity</span>
                            <span class="text-white fw-bold"
                                style="font-size: 0.95rem; letter-spacing: -0.01em;">STABLE</span>
                        </div>
                        <div class="w-100 rounded-pill"
                            style="height: 4px; background-color: rgba(255,255,255,0.08); overflow: hidden;">
                            <div class="h-100 bg-white rounded-pill" style="width: 80%; opacity: 0.85;"></div>
                        </div>
                    </div>

                    <!-- Biometric Sync Panel -->
                    <div class="d-flex align-items-center gap-3 p-3 rounded-3"
                        style="background-color: rgba(9, 11, 16, 0.4); border: 1px solid rgba(255,255,255,0.05); margin-top: 2rem;">
                        <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                            style="width: 32px; height: 32px; background-color: rgba(217, 255, 0, 0.08); border: 1px solid rgba(217, 255, 0, 0.15);">
                            <iconify-icon icon="ph:fingerprint-bold" class="text-safegate-neon fs-5"></iconify-icon>
                        </div>
                        <div>
                            <div class="text-white fw-bold" style="font-size: 0.85rem;">Biometric Sync Active</div>
                            <div class="text-safegate-text-sec" style="font-size: 0.72rem;">Device ID: SG-7729-VERIFIED
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</main>

<!-- CSS Keyframe Animations for Smooth Entry -->
<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>



<?php
// 3. Simpan konten ke dalam variabel
$content = ob_get_clean();

// 4. Panggil Layout Publik yang sudah berisi Header dan Footer
require_once __DIR__ . '/../../layouts/public_layout.php';
?>