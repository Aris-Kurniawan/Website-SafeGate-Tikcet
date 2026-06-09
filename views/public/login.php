<?php
$page_title = "Login - SafeGate";
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
                <div class="safegate-logo-box" style="width: 38px; height: 38px; border-radius: 10px;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#090B10"
                        stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"
                        style="width: 20px; height: 20px;">
                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                        <polyline points="17 6 23 6 23 12"></polyline>
                    </svg>
                </div>
                <h3 class="mb-0 fw-bold text-white fs-4"
                    style="letter-spacing: -0.04em; font-family: 'Inter', sans-serif;">SafeGate</h3>
            </a>

            <!-- Header -->
            <h1 class="fw-bolder text-white mb-2" style="font-size: 2.75rem; letter-spacing: -0.04em;">Welcome Back.
            </h1>
            <p class="mb-4" style="color: var(--safegate-text-sec); font-size: 0.95rem;">Secure access to your
                institutional grade dashboard.</p>

            <?php if ($flash): ?>
                <div class="mb-3 rounded-3 px-3 py-2 fw-semibold" style="background: rgba(217,255,0,0.08); border: 1px solid rgba(217,255,0,0.2); color: var(--safegate-neon); font-size: .85rem;">
                    <?= sg_h($flash['message']) ?>
                </div>
            <?php endif; ?>

            <!-- Tabs -->
            <div class="d-flex rounded-3 p-1 mb-4" style="background-color: rgba(255,255,255,0.05);">
                <button type="button" class="btn btn-safegate-neon flex-grow-1 rounded-3 py-2 fw-bold"
                    style="border: none;">Login</button>
                <button type="button" onclick="triggerAuthTransition('<?= $base_path ?>index.php?page=signup')"
                    class="btn text-white flex-grow-1 rounded-3 py-2 fw-bold opacity-75 hover-white"
                    style="background: transparent; border: none; transition: 0.3s;"
                    onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.75'">Sign Up</button>
            </div>

            <!-- Form -->
            <form action="<?= $base_path ?>index.php?page=login" method="POST">
                <input type="hidden" name="sg_action" value="login">
                <div class="mb-3 d-flex align-items-center rounded-3 input-group-custom"
                    style="background-color: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.05); transition: 0.3s;">
                    <div class="px-3 d-flex align-items-center" style="color: #6c757d;">
                        <iconify-icon icon="ph:envelope-simple" class="fs-5"></iconify-icon>
                    </div>
                    <input type="email" name="email"
                        class="form-control text-white py-3 pe-3 ps-0 border-0 bg-transparent shadow-none"
                        placeholder="Enter your email" ;
                        onfocus="this.parentElement.style.borderColor='var(--safegate-neon)'"
                        onblur="this.parentElement.style.borderColor='rgba(255,255,255,0.05)'">
                </div>

                <div class="mb-3 d-flex align-items-center rounded-3 position-relative input-group-custom"
                    style="background-color: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.05); transition: 0.3s;">
                    <div class="px-3 d-flex align-items-center" style="color: #6c757d;">
                        <iconify-icon icon="ph:key" class="fs-5"></iconify-icon>
                    </div>
                    <input type="password" name="password" id="loginPassword"
                        class="form-control text-white py-3 pe-5 ps-0 border-0 bg-transparent shadow-none"
                        placeholder="Enter your password" required style="font-size: 0.85rem;"
                        onfocus="this.parentElement.style.borderColor='var(--safegate-neon)'"
                        onblur="this.parentElement.style.borderColor='rgba(255,255,255,0.05)'">
                    <iconify-icon icon="ph:eye-slash" id="toggleLoginPassword" class="position-absolute end-0 me-3 fs-6"
                        style="color: #6c757d; cursor: pointer;" onclick="togglePasswordVisibility('loginPassword', 'toggleLoginPassword')"></iconify-icon>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input border-secondary bg-transparent" type="checkbox" id="rememberMe"
                            style="cursor: pointer;">
                        <label class="form-check-label text-safegate-text-sec" for="rememberMe"
                            style="font-size: 0.85rem; cursor: pointer;">
                            Remember me
                        </label>
                    </div>
                    <a href="#" class="text-decoration-none fw-semibold"
                        style="color: #00e5ff; font-size: 0.85rem;">Forgot Password?</a>
                </div>

                <button type="submit"
                    class="btn btn-safegate-neon w-100 rounded-3 py-3 fs-6 fw-bold mb-4">Login</button>

                <!-- Divider -->
                <div class="d-flex align-items-center mb-4">
                    <hr class="flex-grow-1 m-0" style="border-color: rgba(255,255,255,0.1);">
                    <span class="mx-3"
                        style="color: #6c757d; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase;">OR</span>
                    <hr class="flex-grow-1 m-0" style="border-color: rgba(255,255,255,0.1);">
                </div>

                <!-- Social Logins -->
                <button type="button"
                    class="btn text-white w-100 rounded-3 py-3 mb-3 d-flex align-items-center justify-content-center gap-2 fw-semibold"
                    style="background-color: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); transition: background 0.3s;"
                    onmouseover="this.style.backgroundColor='rgba(255,255,255,0.08)'"
                    onmouseout="this.style.backgroundColor='rgba(255,255,255,0.03)'">
                    <iconify-icon icon="ic:baseline-apple" class="fs-3" style="color: white;"></iconify-icon> Log in
                    with
                    Apple
                </button>

                <button type="button"
                    class="btn text-white w-100 rounded-3 py-3 mb-5 d-flex align-items-center justify-content-center gap-2 fw-semibold"
                    style="background-color: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); transition: background 0.3s;"
                    onmouseover="this.style.backgroundColor='rgba(255,255,255,0.08)'"
                    onmouseout="this.style.backgroundColor='rgba(255,255,255,0.03)'">
                    <iconify-icon icon="logos:google-icon" class="fs-5"></iconify-icon> Log in with Google
                </button>
            </form>

            <div class="text-center mt-3 mb-0">
                <p
                    style="color: #5a6270; font-size: 0.65rem; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase;">
                    © 2024 SafeGate Protocol. Institutional Grade Security.
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
            style="object-fit: cover; opacity: 0.65; z-index: 0; filter: contrast(1.1) brightness(0.9);"
            onerror="this.style.display='none'">

        <!-- Glassmorphism Card -->
        <div class="position-relative z-3 rounded-4 p-5 text-center d-flex flex-column align-items-center justify-content-center"
            style="
            width: 440px; 
            height: 300px;
            background: rgba(15, 20, 28, 0.4); 
            backdrop-filter: blur(24px); 
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255,255,255,0.08);
            box-shadow: 0 32px 64px rgba(0,0,0,0.5), inset 0 1px 1px rgba(255,255,255,0.1);
        ">
            <div class="d-flex align-items-center justify-content-center rounded-3 mb-4"
                style="width: 52px; height: 52px; background-color: rgba(217, 255, 0, 0.12); border: 1px solid rgba(217, 255, 0, 0.25);">
                <iconify-icon icon="ph:shield-check-fill" class="text-safegate-neon fs-2"></iconify-icon>
            </div>

            <h2 class="text-white fw-bolder mb-3"
                style="font-size: 2.25rem; line-height: 1.1; letter-spacing: -0.04em;">Institutional Grade<br>Security
            </h2>
            <p class="mb-5"
                style="color: #00e5ff; font-size: 0.8rem; font-weight: 800; letter-spacing: 0.15em; text-transform: uppercase;">
                For the modern fan.</p>

            <div class="d-flex gap-2">
                <div class="rounded-circle bg-safegate-neon"
                    style="width: 6px; height: 6px; box-shadow: 0 0 8px rgba(217, 255, 0, 0.5);"></div>
                <div class="rounded-circle" style="width: 6px; height: 6px; background-color: rgba(255,255,255,0.2);">
                </div>
                <div class="rounded-circle" style="width: 6px; height: 6px; background-color: rgba(255,255,255,0.2);">
                </div>
            </div>
        </div>

        <!-- Gradient Overlay on the left edge to blend with the dark background -->
        <div class="position-absolute top-0 bottom-0 start-0"
            style="width: 30%; background: linear-gradient(to right, var(--safegate-bg), transparent); z-index: 1;">
        </div>
    </div>
</div>



<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/auth_layout.php';
?>
