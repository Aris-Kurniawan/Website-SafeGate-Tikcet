<?php
$id = isset($id) ? $id : 'bank';
$icon = isset($icon) ? $icon : 'ph:bank-bold';
$title_opt = isset($title_opt) ? $title_opt : 'Transfer Bank';
$subtitle_opt = isset($subtitle_opt) ? $subtitle_opt : 'Virtual Account (BCA, Mandiri, BNI, BRI)';
$selected = isset($selected) ? $selected : false;
?>

<div class="p-4 rounded-4 mb-3 transition-all cursor-pointer d-flex align-items-center justify-content-between sg-payment-option-card <?= $selected ? 'sg-payment-selected' : 'sg-payment-unselected' ?>" 
     data-id="<?= $id ?>" 
     onclick="selectPaymentMethod('<?= $id ?>')"
     style="user-select: none; transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);">
    
    <div class="d-flex align-items-center gap-4">
        <!-- Radio indicator & Icon -->
        <div class="d-flex align-items-center gap-3">
            <div class="sg-radio-indicator d-flex align-items-center">
                <?php if ($selected): ?>
                    <iconify-icon icon="ph:radio-button-fill" class="text-safegate-neon fs-4"></iconify-icon>
                <?php else: ?>
                    <iconify-icon icon="ph:circle-bold" class="text-safegate-text-sec fs-4"></iconify-icon>
                <?php endif; ?>
            </div>
            <div class="sg-method-icon-box d-flex align-items-center justify-content-center rounded-3" 
                 style="width: 48px; height: 48px; background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.05); color: <?= $selected ? 'var(--safegate-neon)' : 'var(--safegate-text-sec)' ?>;">
                <iconify-icon icon="<?= $icon ?>" class="fs-3"></iconify-icon>
            </div>
        </div>

        <!-- Info texts -->
        <div>
            <h4 class="fw-bold mb-1 text-white" style="font-size: 1.1rem;"><?= $title_opt ?></h4>
            <p class="mb-0 text-safegate-text-sec" style="font-size: 0.8rem;"><?= $subtitle_opt ?></p>
        </div>
    </div>

    <!-- Checked status badge on right -->
    <?php if ($selected): ?>
        <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" 
             style="width: 28px; height: 28px; background: rgba(217, 255, 0, 0.1); border: 1px solid rgba(217, 255, 0, 0.3); color: var(--safegate-neon);">
            <iconify-icon icon="ph:check-bold" class="fs-6"></iconify-icon>
        </div>
    <?php endif; ?>
</div>
