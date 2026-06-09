<?php
$page_title = "Admin Login - SafeGate";
$base_path = (strpos($_SERVER['SCRIPT_NAME'], 'views/') !== false) ? '../../' : '';
$flash = function_exists('sg_flash') ? sg_flash() : null;
ob_start();
?>
<!-- Iconify -->
<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

<div class="container-fluid p-0 min-vh-100 d-flex flex-column flex-lg-row overflow-hidden"
    style="background-color: var(--safegate-bg);">

    <!-- Left Column: Form -->
    <div id="auth-form"
        class="col-12 col-lg-6 d-flex flex-column justify-content-center px-4 px-md-5 py-5 position-relative z-2"
        style="transition: transform 0.6s cubic-bezier(0.65, 0, 0.35, 1), opacity 0.6s;">
        <div class="w-100 mx-auto" style="max-width: 420px;">

            <!-- Brand -->
            <a href="<?= $base_path ?>index.php?page=home" class="d-flex align-items-center mb-5 gap-3 text-decoration-none">
                <div class="safegate-logo-box" style="width: 38px; height: 38px; border-radius: 10px; background-color: rgba(255, 62, 62, 0.12); border: 1px solid rgba(255, 62, 62, 0.25);">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#ff3e3e"
                        stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"
                        style="width: 20px; height: 20px;">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                </div>
                <h3 class="mb-0 fw-bold text-white fs-4"
                    style="letter-spacing: -0.04em; font-family: 'Inter', sans-serif;">SafeGate <span class="text-danger" style="font-size: 0.75rem; letter-spacing: 0.05em; text-transform: uppercase; font-weight: 800;">ADMIN</span></h3>
            </a>

            <!-- Header -->
            <h1 class="fw-bolder text-white mb-2" style="font-size: 2.5rem; letter-spacing: -0.04em;">Admin Console.</h1>
            <p class="mb-4" style="color: var(--safegate-text-sec); font-size: 0.95rem;">Secure access to SafeGate administrative control center.</p>

            <?php if ($flash): ?>
                <div class="mb-3 rounded-3 px-3 py-2 fw-semibold" style="background: rgba(255,62,62,0.08); border: 1px solid rgba(255,62,62,0.2); color: #ff6868; font-size: .85rem;">
                    <?= sg_h($flash['message']) ?>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <form action="<?= $base_path ?>index.php?page=admin_login" method="POST">
                <input type="hidden" name="sg_action" value="admin_login">
                
                <div class="mb-3 d-flex align-items-center rounded-3 input-group-custom"
                    style="background-color: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.05); transition: 0.3s;">
                    <div class="px-3 d-flex align-items-center" style="color: #6c757d;">
                        <iconify-icon icon="ph:shield-user" class="fs-5"></iconify-icon>
                    </div>
                    <input type="email" name="email"
                        class="form-control text-white py-3 pe-3 ps-0 border-0 bg-transparent shadow-none"
                        placeholder="Admin Email" required
                        onfocus="this.parentElement.style.borderColor='#ff3e3e'"
                        onblur="this.parentElement.style.borderColor='rgba(255,255,255,0.05)'">
                </div>

                <div class="mb-4 d-flex align-items-center rounded-3 position-relative input-group-custom"
                    style="background-color: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.05); transition: 0.3s;">
                    <div class="px-3 d-flex align-items-center" style="color: #6c757d;">
                        <iconify-icon icon="ph:key" class="fs-5"></iconify-icon>
                    </div>
                    <input type="password" name="password" id="adminPassword"
                        class="form-control text-white py-3 pe-5 ps-0 border-0 bg-transparent shadow-none"
                        placeholder="Admin Password" required style="font-size: 0.85rem;"
                        onfocus="this.parentElement.style.borderColor='#ff3e3e'"
                        onblur="this.parentElement.style.borderColor='rgba(255,255,255,0.05)'">
                    <iconify-icon icon="ph:eye-slash" id="toggleAdminPassword" class="position-absolute end-0 me-3 fs-6"
                        style="color: #6c757d; cursor: pointer;" onclick="togglePasswordVisibility('adminPassword', 'toggleAdminPassword')"></iconify-icon>
                </div>

                <button type="submit"
                    class="btn btn-danger w-100 rounded-3 py-3 fs-6 fw-bold mb-4" style="background-color: #ff3e3e; border: none; box-shadow: 0 4px 15px rgba(255, 62, 62, 0.3);">Access Console</button>
            </form>

            <div class="text-center mt-3 mb-0">
                <p style="color: #5a6270; font-size: 0.8rem; font-weight: 500;">
                    Need a new admin account? <a href="index.php?page=admin_signup" style="color: #ff3e3e; font-weight: bold; text-decoration: none;">Register Admin</a>
                </p>
                <p style="color: #5a6270; font-size: 0.65rem; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; margin-top: 2rem;">
                    © 2026 SafeGate Protocol. Admin Authorization Center.
                </p>
            </div>
        </div>
    </div>

    <!-- Right Column: Visual -->
    <div id="auth-visual"
        class="d-none d-lg-flex col-lg-6 position-relative align-items-center justify-content-center overflow-hidden"
        style="transition: transform 0.6s cubic-bezier(0.65, 0, 0.35, 1), opacity 0.6s;">

        <!-- Abstract Background Image -->
        <img src="assets/images/auth_bg.webp" alt="Abstract Background" class="position-absolute w-100 h-100"
            style="object-fit: cover; opacity: 0.4; z-index: 0; filter: grayscale(1) contrast(1.3) brightness(0.6);"
            onerror="this.style.display='none'">

        <!-- Glassmorphism Card -->
        <div class="position-relative z-3 rounded-4 p-5 text-center d-flex flex-column align-items-center justify-content-center"
            style="
            width: 440px; 
            height: 300px;
            background: rgba(15, 20, 28, 0.5); 
            backdrop-filter: blur(24px); 
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255,255,255,0.08);
            box-shadow: 0 32px 64px rgba(0,0,0,0.5), inset 0 1px 1px rgba(255,255,255,0.1);
        ">
            <div class="d-flex align-items-center justify-content-center rounded-3 mb-4"
                style="width: 52px; height: 52px; background-color: rgba(255, 62, 62, 0.12); border: 1px solid rgba(255, 62, 62, 0.25);">
                <iconify-icon icon="ph:shield-warning-fill" class="text-danger fs-2"></iconify-icon>
            </div>

            <h2 class="text-white fw-bolder mb-3"
                style="font-size: 2.25rem; line-height: 1.1; letter-spacing: -0.04em;">Authorized<br>Personnel Only
            </h2>
            <p class="mb-5"
                style="color: #ff3e3e; font-size: 0.8rem; font-weight: 800; letter-spacing: 0.15em; text-transform: uppercase;">
                SafeGate Admin Console</p>

            <div class="d-flex gap-2">
                <div class="rounded-circle"
                    style="width: 6px; height: 6px; background-color: #ff3e3e; box-shadow: 0 0 8px rgba(255, 62, 62, 0.5);"></div>
                <div class="rounded-circle" style="width: 6px; height: 6px; background-color: rgba(255,255,255,0.2);"></div>
                <div class="rounded-circle" style="width: 6px; height: 6px; background-color: rgba(255,255,255,0.2);"></div>
            </div>
        </div>

        <!-- Gradient Overlay on the left edge -->
        <div class="position-absolute top-0 bottom-0 start-0"
            style="width: 30%; background: linear-gradient(to right, var(--safegate-bg), transparent); z-index: 1;">
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/auth_layout.php';
?>
