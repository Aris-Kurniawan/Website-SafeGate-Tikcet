<aside class="bg-safegate-surface h-screen w-64 p-6 text-white flex flex-col border-r border-gray-800/50">
    <!-- Brand / Dashboard Title -->
    <div class="mb-10">
        <h2 class="text-2xl font-semibold tracking-tight text-white">SafeGate Dashboard</h2>
        <p class="text-safegate-success text-sm">Verified Vendor</p>
    </div>

    <!-- Navigation -->
    <nav class="flex flex-col gap-3 text-sm font-medium">
        <a href="index.php?page=sell_ticket" 
           class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-safegate-bg transition-colors">
            <i class="ph ph-ticket text-safegate-neon"></i>
            <span>Sell Tickets</span>
        </a>

        <a href="index.php?page=transaction" 
           class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-safegate-bg transition-colors">
            <i class="ph ph-list-checks text-safegate-neon"></i>
            <span>Transaction History</span>
        </a>

        <a href="#" 
           class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-safegate-bg transition-colors">
            <i class="ph ph-question text-safegate-neon"></i>
            <span>Help Center</span>
        </a>

        <a href="index.php?page=logout" 
           class="flex items-center gap-2 px-3 py-2 rounded-lg text-safegate-danger hover:bg-red-600 transition-colors">
            <i class="ph ph-sign-out"></i>
            <span>Log Out</span>
        </a>
    </nav>

    <!-- Footer Info -->
    <div class="mt-auto pt-6 border-t border-gray-800/50 text-xs text-safegate-text-sec leading-relaxed">
        <p>256-bit AES Encryption</p>
        <p>SafeGate Escrow Active</p>
    </div>
</aside>
