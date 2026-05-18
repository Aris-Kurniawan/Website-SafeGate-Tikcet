<header class="w-full py-6 px-8 border-b border-gray-800/50 bg-safegate-bg/80 backdrop-blur-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
        <!-- Logo -->
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 bg-safegate-neon rounded-full"></div>
            <a href="index.php?page=home" class="text-xl font-bold tracking-tight text-white">SafeGate</a>
        </div>

        <!-- Navigation -->
        <nav class="hidden md:flex items-center gap-8 text-sm font-medium">
            <a href="index.php?page=home" class="text-white relative group">
                Events
                <span class="absolute -bottom-2 left-0 w-full h-[2px] bg-safegate-neon"></span>
            </a>
            <a href="index.php?page=penjualan" class="text-safegate-text-sec hover:text-white transition-colors">Penjualan</a>
            <a href="index.php?page=cara_kerja" class="text-safegate-text-sec hover:text-white transition-colors">Cara Kerja</a>
        </nav>

        <!-- Actions -->
        <div class="hidden md:flex items-center gap-4">
            <button class="flex items-center gap-2 border border-safegate-success/30 text-safegate-success hover:bg-safegate-success/10 text-xs font-semibold px-4 py-2 rounded-full transition-colors">
                <i class="ph-fill ph-shield-check text-base"></i> SECURED
            </button>
            <a href="index.php?page=login" class="text-white text-sm font-medium hover:text-safegate-neon transition-colors">Login</a>
            <a href="index.php?page=register" class="bg-safegate-neon hover:bg-[#c2e600] text-black text-sm font-bold px-6 py-2 rounded-full transition-all duration-300 transform hover:scale-[1.02]">
                Sign Up
            </a>
        </div>
    </div>
</header>
