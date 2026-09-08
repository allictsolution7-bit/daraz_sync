{{-- Visual Search by Image & Camera Modal --}}
<div id="imageSearchModal" class="img-search-overlay" role="dialog" aria-modal="true" aria-labelledby="imgSearchTitle">
    <div class="img-search-modal">
        {{-- Header --}}
        <div class="img-search-header">
            <div class="img-search-title-wrap">
                <div class="img-search-icon-badge">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                        <circle cx="12" cy="13" r="4"></circle>
                    </svg>
                </div>
                <div>
                    <h3 id="imgSearchTitle" class="img-search-title">Search by Image</h3>
                    <p class="img-search-subtitle">Snap a photo or upload an image to find matching products</p>
                </div>
            </div>
            <button type="button" id="imgSearchCloseBtn" class="img-search-close-btn" aria-label="Close modal">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        {{-- Body --}}
        <div class="img-search-body">
            {{-- Hidden Native File Inputs --}}
            <input type="file" id="imgSearchCameraInput" accept="image/*" capture="environment" class="img-search-hidden-input">
            <input type="file" id="imgSearchFileInput" accept="image/*" class="img-search-hidden-input">

            {{-- Action Cards (Camera vs Upload) --}}
            <div id="imgSearchActionCards" class="img-search-action-cards">
                {{-- Camera Capture Card --}}
                <div id="imgSearchCameraBtn" class="img-search-action-btn btn-camera-highlight">
                    <div class="img-search-action-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                            <circle cx="12" cy="13" r="4"></circle>
                        </svg>
                    </div>
                    <span class="img-search-action-label">Take a Photo</span>
                    <span class="img-search-action-hint">Direct from your phone camera</span>
                </div>

                {{-- Upload / Drag & Drop Card --}}
                <div id="imgSearchDropZone" class="img-search-action-btn">
                    <div class="img-search-action-icon" style="color: #64748b;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" y1="3" x2="12" y2="15"></line>
                        </svg>
                    </div>
                    <span class="img-search-action-label">Upload Image</span>
                    <span class="img-search-action-hint">Drop file here or browse gallery</span>
                </div>
            </div>

            {{-- Image Preview & Scanner Beam --}}
            <div id="imgSearchPreviewContainer" class="img-search-preview-container">
                <div class="img-search-preview-box">
                    <img id="imgSearchPreviewImg" src="" alt="Query image" class="img-search-preview-img">
                    <div class="img-search-laser"></div>
                </div>
                <div id="imgSearchScannerStatus" class="img-search-scanner-status">
                    <div class="img-search-spinner"></div>
                    <span>Scanning visual features & matching products...</span>
                </div>
                <button type="button" id="imgSearchRetakeBtn" class="img-search-retake-btn">
                    &larr; Choose another photo
                </button>
            </div>

            {{-- Results Grid --}}
            <div id="imgSearchResultsWrap" class="img-search-results-wrap">
                <div class="img-search-results-header">
                    <div class="img-search-results-title">
                        <span>Similar Products</span>
                        <span id="imgSearchResultsCount" class="img-search-count-badge">0 found</span>
                    </div>
                </div>
                <div id="imgSearchResultsGrid" class="img-search-grid"></div>
            </div>
        </div>
    </div>
</div>
