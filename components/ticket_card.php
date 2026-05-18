<?php
// Default values if variables are not passed
$image = isset($image) ? $image : 'https://images.unsplash.com/photo-1540039155733-d7696f4ad9b2?auto=format&fit=crop&q=80&w=800';
$title = isset($title) ? $title : 'Event Title';
$date = isset($date) ? $date : 'Date • Location';
$price = isset($price) ? $price : '100.000';
$originalPrice = isset($originalPrice) ? $originalPrice : '150.000';
?>
<div class="bg-safegate-surface border border-gray-800/50 rounded-2xl overflow-hidden flex flex-col group hover:border-gray-700 transition-colors">
    <!-- Image Wrapper -->
    <div class="h-48 w-full overflow-hidden relative">
        <img src="<?= $image ?>" alt="<?= $title ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        <!-- Badge Layer -->
        <div class="absolute top-4 left-4 flex items-center gap-1.5 bg-safegate-neon text-black text-[10px] font-bold px-2.5 py-1 rounded-sm shadow-sm">
            <i class="ph-fill ph-check-circle"></i> VERIFIED
        </div>
    </div>
    
    <!-- Content Body -->
    <div class="p-6 flex-1 flex flex-col">
        <h3 class="text-xl font-medium text-white mb-2"><?= $title ?></h3>
        <p class="text-xs text-safegate-text-sec mb-5 flex items-center gap-1.5 font-medium">
            <i class="ph ph-calendar-blank text-sm"></i> <?= $date ?>
        </p>
        
        <div class="flex gap-2.5 mb-7">
            <span class="text-[9px] font-bold text-gray-300 bg-gray-800/40 border border-gray-700 px-3 py-1.5 rounded-full uppercase tracking-widest">KYC Secured</span>
            <span class="text-[9px] font-bold text-gray-300 bg-gray-800/40 border border-gray-700 px-3 py-1.5 rounded-full uppercase tracking-widest">Escrow Active</span>
        </div>
        
        <!-- Footer / Price -->
        <div class="mt-auto flex items-end justify-between border-t border-gray-800/50 pt-5">
            <div>
                <p class="text-[9px] text-safegate-neon font-bold uppercase tracking-widest mb-1.5">Face Value Cap</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-[22px] font-medium text-white tabular-nums">Rp.<?= $price ?></span>
                    <span class="text-xs text-gray-500 line-through tabular-nums decoration-gray-500">Rp.<?= $originalPrice ?></span>
                </div>
            </div>
            <button class="border border-safegate-neon text-safegate-neon hover:bg-safegate-neon hover:text-black font-semibold text-xs px-5 py-2.5 rounded-full transition-all duration-300 mb-1">
                BUY
            </button>
        </div>
    </div>
</div>
