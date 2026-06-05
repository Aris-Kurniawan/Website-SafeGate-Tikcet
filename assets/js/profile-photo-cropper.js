(function () {
    const inputs = Array.from(document.querySelectorAll('[data-profile-crop-input]'));
    if (!inputs.length) return;

    let activeInput = null;
    let activeImage = null;
    let previewTarget = null;
    let hiddenTarget = null;
    let initialsTarget = null;
    let avatarTarget = null;
    let stage = null;
    let cropImage = null;
    let zoomInput = null;
    let state = null;
    let drag = null;

    function getCropStageSize() {
        const viewportLimit = Math.min(window.innerWidth * 0.62, window.innerHeight * 0.46);
        return Math.max(160, Math.min(210, Math.floor(viewportLimit)));
    }

    function applyModalSizing() {
        const cropSize = getCropStageSize();

        modal.style.cssText = [
            'position:fixed',
            'inset:0',
            'z-index:9999',
            'display:grid',
            'place-items:center',
            'padding:16px',
            'background:rgba(0,0,0,.78)',
            'backdrop-filter:blur(8px)'
        ].join(';');

        const dialog = modal.querySelector('.sg-avatar-crop-dialog');
        if (dialog) {
            dialog.style.width = 'min(340px, 94vw)';
            dialog.style.maxWidth = '340px';
        }

        if (stage) {
            stage.style.width = `${cropSize}px`;
            stage.style.height = `${cropSize}px`;
            stage.style.maxWidth = '68vw';
            stage.style.maxHeight = '46vh';
            stage.style.overflow = 'hidden';
            stage.style.borderRadius = '50%';
        }

        if (cropImage) {
            cropImage.style.position = 'absolute';
            cropImage.style.maxWidth = 'none';
            cropImage.style.maxHeight = 'none';
        }
    }

    function createModal() {
        const modal = document.createElement('div');
        modal.className = 'sg-avatar-crop-modal';
        modal.hidden = true;
        modal.innerHTML = `
            <div class="sg-avatar-crop-dialog" role="dialog" aria-modal="true" aria-label="Crop profile photo">
                <div class="sg-avatar-crop-head">
                    <div>
                        <strong>Atur Foto</strong>
                        <span>Geser dan zoom foto sampai pas di lingkaran.</span>
                    </div>
                    <button type="button" class="sg-avatar-crop-close" aria-label="Tutup crop foto">
                        <iconify-icon icon="ph:x"></iconify-icon>
                    </button>
                </div>
                <div class="sg-avatar-crop-stage" aria-label="Area crop foto">
                    <img class="sg-avatar-crop-image" alt="Profile crop preview">
                    <div class="sg-avatar-crop-mask"></div>
                </div>
                <label class="sg-avatar-crop-zoom">
                    <span>Zoom</span>
                    <input type="range" min="1" max="3" step="0.01" value="1">
                </label>
                <div class="sg-avatar-crop-actions">
                    <button type="button" class="sg-avatar-crop-cancel">Batal</button>
                    <button type="button" class="sg-avatar-crop-apply">Selesai</button>
                </div>
            </div>
        `;
        document.body.appendChild(modal);

        modal.querySelector('.sg-avatar-crop-close')?.addEventListener('click', closeCropper);
        modal.querySelector('.sg-avatar-crop-cancel')?.addEventListener('click', closeCropper);
        modal.querySelector('.sg-avatar-crop-apply')?.addEventListener('click', applyCrop);
        modal.addEventListener('click', (event) => {
            if (event.target === modal) closeCropper();
        });

        stage = modal.querySelector('.sg-avatar-crop-stage');
        cropImage = modal.querySelector('.sg-avatar-crop-image');
        zoomInput = modal.querySelector('.sg-avatar-crop-zoom input');
        zoomInput?.addEventListener('input', handleZoom);

        stage?.addEventListener('pointerdown', startDrag);
        window.addEventListener('pointermove', moveDrag);
        window.addEventListener('pointerup', endDrag);
        window.addEventListener('resize', () => {
            if (modal.hidden || !state) return;
            const oldSize = stage?.clientWidth || 1;
            applyModalSizing();
            const newSize = stage?.clientWidth || oldSize;
            const ratio = newSize / oldSize;
            state.x *= ratio;
            state.y *= ratio;
            state.baseScale *= ratio;
            state.scale *= ratio;
            renderCropper();
        });

        return modal;
    }

    const modal = createModal();

    function clampPosition() {
        if (!stage || !state) return;
        const size = stage.clientWidth;
        const renderW = state.imageWidth * state.scale;
        const renderH = state.imageHeight * state.scale;

        state.x = renderW <= size ? (size - renderW) / 2 : Math.min(0, Math.max(size - renderW, state.x));
        state.y = renderH <= size ? (size - renderH) / 2 : Math.min(0, Math.max(size - renderH, state.y));
    }

    function renderCropper() {
        if (!cropImage || !state) return;
        clampPosition();
        cropImage.style.width = `${state.imageWidth * state.scale}px`;
        cropImage.style.height = `${state.imageHeight * state.scale}px`;
        cropImage.style.transform = `translate(${state.x}px, ${state.y}px)`;
    }

    function openCropper(file, input) {
        if (!file.type.startsWith('image/')) return;

        activeInput = input;
        previewTarget = document.getElementById(input.dataset.profilePreview || '');
        hiddenTarget = document.getElementById(input.dataset.profileHidden || '');
        initialsTarget = document.getElementById(input.dataset.profileInitials || '');
        avatarTarget = input.closest('.sg-profile-photo-control')?.querySelector('.sg-profile-avatar') || null;

        const reader = new FileReader();
        reader.onload = () => {
            activeImage = new Image();
            activeImage.onload = () => {
                if (!stage || !cropImage || !zoomInput) return;
                cropImage.src = String(reader.result || '');
                modal.hidden = false;
                applyModalSizing();

                const size = stage.clientWidth || getCropStageSize();
                const baseScale = Math.max(size / activeImage.naturalWidth, size / activeImage.naturalHeight);
                state = {
                    imageWidth: activeImage.naturalWidth,
                    imageHeight: activeImage.naturalHeight,
                    baseScale,
                    scale: baseScale,
                    x: (size - activeImage.naturalWidth * baseScale) / 2,
                    y: (size - activeImage.naturalHeight * baseScale) / 2,
                };
                zoomInput.value = '1';
                renderCropper();
            };
            activeImage.src = String(reader.result || '');
        };
        reader.readAsDataURL(file);
    }

    function closeCropper() {
        modal.hidden = true;
        if (activeInput && !hiddenTarget?.value) activeInput.value = '';
        drag = null;
    }

    function handleZoom() {
        if (!stage || !state || !zoomInput) return;
        const size = stage.clientWidth;
        const oldScale = state.scale;
        const oldCenterX = (size / 2 - state.x) / oldScale;
        const oldCenterY = (size / 2 - state.y) / oldScale;

        state.scale = state.baseScale * Number(zoomInput.value);
        state.x = size / 2 - oldCenterX * state.scale;
        state.y = size / 2 - oldCenterY * state.scale;
        renderCropper();
    }

    function startDrag(event) {
        if (!state) return;
        drag = {
            pointerId: event.pointerId,
            startX: event.clientX,
            startY: event.clientY,
            x: state.x,
            y: state.y,
        };
        stage?.setPointerCapture?.(event.pointerId);
    }

    function moveDrag(event) {
        if (!drag || !state || event.pointerId !== drag.pointerId) return;
        state.x = drag.x + event.clientX - drag.startX;
        state.y = drag.y + event.clientY - drag.startY;
        renderCropper();
    }

    function endDrag(event) {
        if (!drag || event.pointerId !== drag.pointerId) return;
        stage?.releasePointerCapture?.(event.pointerId);
        drag = null;
    }

    function applyCrop() {
        if (!stage || !activeImage || !state || !previewTarget || !hiddenTarget) return;

        const outputSize = 512;
        const stageSize = stage.clientWidth || 280;
        const factor = outputSize / stageSize;
        const canvas = document.createElement('canvas');
        canvas.width = outputSize;
        canvas.height = outputSize;

        const context = canvas.getContext('2d');
        context.fillStyle = '#0b0f16';
        context.fillRect(0, 0, outputSize, outputSize);
        context.drawImage(
            activeImage,
            state.x * factor,
            state.y * factor,
            state.imageWidth * state.scale * factor,
            state.imageHeight * state.scale * factor
        );

        const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
        hiddenTarget.value = dataUrl;
        previewTarget.src = dataUrl;
        previewTarget.hidden = false;
        avatarTarget?.classList.add('has-photo');
        if (initialsTarget) initialsTarget.hidden = true;
        modal.hidden = true;
    }

    inputs.forEach((input) => {
        input.addEventListener('change', (event) => {
            const file = event.target.files?.[0];
            if (!file) return;
            if (file.size > 3 * 1024 * 1024) {
                alert('Ukuran foto profil maksimal 3MB.');
                input.value = '';
                return;
            }
            openCropper(file, input);
        });
    });
})();
