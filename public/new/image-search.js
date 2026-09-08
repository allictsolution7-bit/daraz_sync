/**
 * Visual Product Search by Image & Phone Camera
 */
document.addEventListener('DOMContentLoaded', function () {
    const modalOverlay = document.getElementById('imageSearchModal');
    const closeBtn = document.getElementById('imgSearchCloseBtn');
    const cameraInput = document.getElementById('imgSearchCameraInput');
    const fileInput = document.getElementById('imgSearchFileInput');
    const actionCards = document.getElementById('imgSearchActionCards');
    const previewContainer = document.getElementById('imgSearchPreviewContainer');
    const previewImg = document.getElementById('imgSearchPreviewImg');
    const scannerStatus = document.getElementById('imgSearchScannerStatus');
    const retakeBtn = document.getElementById('imgSearchRetakeBtn');
    const resultsWrap = document.getElementById('imgSearchResultsWrap');
    const resultsGrid = document.getElementById('imgSearchResultsGrid');
    const resultsCount = document.getElementById('imgSearchResultsCount');
    const dropZone = document.getElementById('imgSearchDropZone');

    // If modal elements don't exist on page, exit safely
    if (!modalOverlay) return;

    // Open Modal Function
    window.openImageSearchModal = function () {
        resetModalState();
        modalOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    };

    // Close Modal Function
    window.closeImageSearchModal = function () {
        modalOverlay.classList.remove('active');
        document.body.style.overflow = '';
    };

    // Bind triggers
    document.querySelectorAll('.btn-image-search-trigger').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            window.openImageSearchModal();
        });
    });

    // Close button & clicking outside
    if (closeBtn) closeBtn.addEventListener('click', window.closeImageSearchModal);
    modalOverlay.addEventListener('click', function (e) {
        if (e.target === modalOverlay) {
            window.closeImageSearchModal();
        }
    });

    // Close on Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modalOverlay.classList.contains('active')) {
            window.closeImageSearchModal();
        }
    });

    // Reset Modal to initial state
    function resetModalState() {
        actionCards.style.display = 'grid';
        previewContainer.style.display = 'none';
        resultsWrap.style.display = 'none';
        resultsGrid.innerHTML = '';
        if (cameraInput) cameraInput.value = '';
        if (fileInput) fileInput.value = '';
    }

    if (retakeBtn) {
        retakeBtn.addEventListener('click', resetModalState);
    }

    // Camera button click
    const cameraBtn = document.getElementById('imgSearchCameraBtn');
    if (cameraBtn && cameraInput) {
        cameraBtn.addEventListener('click', () => cameraInput.click());
        cameraInput.addEventListener('change', handleFileInputChange);
    }

    // File upload button click
    if (dropZone && fileInput) {
        dropZone.addEventListener('click', () => fileInput.click());
        fileInput.addEventListener('change', handleFileInputChange);

        // Drag & Drop
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropZone.classList.add('dragover');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropZone.classList.remove('dragover');
            }, false);
        });

        dropZone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files.length > 0 && files[0].type.startsWith('image/')) {
                processImageFile(files[0]);
            }
        });
    }

    // Clipboard Paste Listener (Ctrl+V anywhere when modal is open)
    window.addEventListener('paste', function (e) {
        if (!modalOverlay.classList.contains('active')) return;
        const items = (e.clipboardData || e.originalEvent.clipboardData).items;
        for (let index in items) {
            const item = items[index];
            if (item.kind === 'file' && item.type.startsWith('image/')) {
                const blob = item.getAsFile();
                processImageFile(blob);
                break;
            }
        }
    });

    function handleFileInputChange(e) {
        if (e.target.files && e.target.files[0]) {
            processImageFile(e.target.files[0]);
        }
    }

    // Process and upload image
    function processImageFile(file) {
        // Show preview & scanner
        const reader = new FileReader();
        reader.onload = function (e) {
            previewImg.src = e.target.result;
            actionCards.style.display = 'none';
            previewContainer.style.display = 'flex';
            resultsWrap.style.display = 'none';
            scannerStatus.innerHTML = `
                <div class="img-search-spinner"></div>
                <span>Scanning visual features & matching products...</span>
            `;

            // Execute upload
            sendSearchRequest(file);
        };
        reader.readAsDataURL(file);
    }

    function sendSearchRequest(file) {
        const formData = new FormData();
        formData.append('image', file);

        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        fetch('/search/image', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            renderResults(data);
        })
        .catch(err => {
            console.error('Visual search error:', err);
            scannerStatus.innerHTML = `
                <span style="color: #ef4444;">Search request failed. Please try another image.</span>
            `;
        });
    }

    function renderResults(data) {
        scannerStatus.innerHTML = `<span>Matching complete!</span>`;

        if (!data.success || !data.products || data.products.length === 0) {
            resultsGrid.innerHTML = `
                <div class="img-search-empty-state" style="grid-column: 1 / -1;">
                    <div class="img-search-empty-icon">🔍</div>
                    <h4 style="font-size: 15px; margin-bottom: 6px; font-weight: 600;">No exact match found</h4>
                    <p style="font-size: 13px; color: #64748b;">Try capturing the product from another angle or with better lighting.</p>
                </div>
            `;
            resultsCount.textContent = '0 found';
            resultsWrap.style.display = 'block';
            return;
        }

        resultsCount.textContent = `${data.products.length} found`;
        let html = '';

        data.products.forEach(p => {
            const similarityBadge = p.similarity >= 85 
                ? `<span class="img-search-match-badge" style="background: #10b981;">✨ ${p.similarity}% Match</span>`
                : (p.similarity >= 65 
                    ? `<span class="img-search-match-badge" style="background: #3b82f6;">${p.similarity}% Match</span>`
                    : `<span class="img-search-match-badge" style="background: #64748b;">Similar</span>`);

            const oldPriceHtml = p.formatted_old_price 
                ? `<span class="img-search-card-old-price">${p.formatted_old_price}</span>` 
                : '';

            html += `
                <a href="${p.url}" class="img-search-card">
                    <div class="img-search-card-thumb-wrap">
                        <img src="${p.image}" alt="${p.title}" class="img-search-card-thumb" loading="lazy">
                        ${similarityBadge}
                    </div>
                    <div class="img-search-card-details">
                        ${p.category_name ? `<span class="img-search-card-category">${p.category_name}</span>` : ''}
                        <div class="img-search-card-title" title="${p.title}">${p.title}</div>
                        <div class="img-search-card-prices">
                            <span class="img-search-card-price">${p.formatted_price}</span>
                            ${oldPriceHtml}
                        </div>
                    </div>
                </a>
            `;
        });

        resultsGrid.innerHTML = html;
        resultsWrap.style.display = 'block';
    }
});
