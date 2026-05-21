<?php
// Default texts if not passed
$escrow_text = isset($escrow_text) ? $escrow_text : 'Dana Anda ditahan dengan aman di brankas Escrow hingga 24 jam setelah acara selesai.';
$escrow_title = isset($escrow_title) ? $escrow_title : 'Perlindungan Pembeli';
$is_small = isset($is_small) ? $is_small : false;
?>

<?php if ($is_small): ?>
    <div class="d-flex align-items-start gap-3 p-3 rounded-3" style="background: rgba(0, 255, 163, 0.03); border: 1px solid rgba(0, 255, 163, 0.12);">
        <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0 mt-0.5" style="width: 32px; height: 32px; background: rgba(0, 255, 163, 0.08); color: var(--safegate-success);">
            <iconify-icon icon="ph:shield-check-fill" class="fs-5"></iconify-icon>
        </div>
        <p class="mb-0 text-safegate-text-sec" style="font-size: 0.72rem; line-height: 1.4;">
            <?= $escrow_text ?>
        </p>
    </div>
<?php else: ?>
    <div class="sg-protection-card" style="background: rgba(18, 22, 31, 0.3) !important; border: 1px solid rgba(0, 255, 163, 0.15) !important;">
        <div class="sg-protection-icon" style="background: rgba(0, 255, 163, 0.08); border: 1px solid rgba(0, 255, 163, 0.25);">
            <iconify-icon icon="ph:lock-keyhole-fill" class="fs-4"></iconify-icon>
        </div>
        <div>
            <h4 class="text-white fw-bold mb-1" style="font-size: 0.85rem;"><?= $escrow_title ?></h4>
            <p class="text-safegate-text-sec mb-0" style="font-size: 0.72rem; line-height: 1.45;">
                Dana Anda ditahan dengan aman di brankas <span class="text-safegate-success fw-bold">Escrow</span> hingga 24 jam setelah acara selesai.
            </p>
        </div>
    </div>
<?php endif; ?>
