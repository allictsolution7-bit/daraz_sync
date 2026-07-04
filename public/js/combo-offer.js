/**
 * Enhanced Combo Offer System JavaScript
 * Handles combo offer selection with color and size popups
 */

class ComboOfferSystem {
    constructor() {
        this.currentComboOffer = null;
        this.currentSelections = [];
        this.availableProducts = [];
        this.selectedColor = null;
        this.selectedSize = null;
        this.currentStep = 'color';
        this.isSubmitting = false;
        this.init();
    }

    init() {
        this.bindEvents();
        this.loadComboOffers();
    }

    bindEvents() {
        // Bind variation selection events
        $(document).on('click', '.variation-item', (e) => {
            e.preventDefault();
            this.selectVariation($(e.currentTarget));
        });

        // Bind combo offer selection events
        $(document).on('click', '.combo-selection-box', (e) => {
            e.preventDefault();
            this.openProductSelectionPopup($(e.currentTarget));
        });

        // Bind popup events
        $(document).on('click', '.combo-popup-overlay', (e) => {
            if (e.target === e.currentTarget) {
                this.closePopup();
            }
        });

        $(document).on('click', '.combo-popup-close', (e) => {
            e.preventDefault();
            this.closePopup();
        });

        // Bind product selection events
        $(document).on('click', '.product-item', (e) => {
            e.preventDefault();
            this.selectProduct($(e.currentTarget));
        });

        // Bind back to products button
        $(document).on('click', '.back-to-products', (e) => {
            e.preventDefault();
            this.showProductSelection();
        });

        // Bind size selection events
        $(document).on('click', '.size-item', (e) => {
            e.preventDefault();
            this.selectSize($(e.currentTarget));
        });

        // Bind quantity change events
        $(document).on('change', '.combo-quantity', (e) => {
            this.updateQuantity($(e.currentTarget));
        });

        // Bind add to cart events
        $(document).on('click', '.combo-add-to-cart', (e) => {
            e.preventDefault();
            this.addComboToCart();
        });

        $(document).on('click', '.combo-buy-now', (e) => {
            e.preventDefault();
            this.buyComboNow();
        });

        // Bind quantity buttons
        $(document).on('click', '.quantity-btn.minus', (e) => {
            e.preventDefault();
            this.decreaseQuantity();
        });

        $(document).on('click', '.quantity-btn.plus', (e) => {
            e.preventDefault();
            this.increaseQuantity();
        });
    }

    loadComboOffers() {
        const productId = $('.pcontainer').data('product-id');
        
        if (!productId) {
            return;
        }

        $.ajax({
            url: `/combo/offers/${productId}`,
            method: 'GET',
            success: (response) => {
                if (response.success && response.data.length > 0) {
                    // Store combo offer data for later use
                    this.comboOffersData = response.data;
                    
                    // Make combo offer data available globally
                    window.comboOfferData = {};
                    response.data.forEach(combo => {
                        window.comboOfferData[combo.id] = combo;
                    });
                    
                    this.renderComboOffers(response.data);
                }
            },
            error: (xhr, status, error) => {
                // Handle error silently or show user-friendly message
            }
        });
    }

    renderComboOffers(comboOffers) {
        const comboContainer = $('.combo-offers-container');
        if (comboContainer.length === 0) {
            // Create combo offers container if it doesn't exist
            $('.pcontainer').append('<div class="combo-offers-container"></div>');
        }

        comboOffers.forEach(comboOffer => {
            this.renderComboOffer(comboOffer);
        });
    }

    renderComboOffer(comboOffer) {
        // Clean up any existing combo offers first
        this.cleanupExistingComboOffers();
        
        // Update existing product title and price elements
        this.updateProductDisplay(comboOffer);
        
        // Only create the selection area, no duplicate title/price
        const comboHtml = `
            <div class="combo-offer" data-combo-id="${comboOffer.id}" data-items-count="${comboOffer.items_count}">
                <div class="combo-selection-area">
                    <div class="combo-selection-boxes">
                        ${this.generateSelectionBoxes(comboOffer.items_count)}
                    </div>
                    
                    <div class="combo-validation-message">
                        <i class="fas fa-check"></i>
                        <span>Please select a product for all items.</span>
                    </div>
                </div>
            </div>
        `;

        $('.combo-offers-container').append(comboHtml);
    }

    cleanupExistingComboOffers() {
        // Remove any existing combo offer elements
        $('.combo-offer').remove();
        
        // Reset any modified elements
        const productTitle = document.querySelector('.product-title');
        if (productTitle && productTitle.dataset.originalTitle) {
            productTitle.textContent = productTitle.dataset.originalTitle;
        }
    }

    updateProductDisplay(comboOffer) {
        // Update product title to show combo offer title
        const productTitle = document.querySelector('.product-title');
        if (productTitle) {
            // Store original title if not already stored
            if (!productTitle.dataset.originalTitle) {
                productTitle.dataset.originalTitle = productTitle.textContent;
            }
            productTitle.textContent = comboOffer.title;
        }

        // Remove all duplicate price elements first
        this.removeDuplicatePriceElements();

        // Update price display to show combo pricing - replace the entire content
        const priceElement = document.getElementById('updateOfferPrice');
        if (priceElement) {
            const savings = comboOffer.original_price - comboOffer.combo_price;
            const discountPercentage = Math.round((savings / comboOffer.original_price) * 100);
            
            priceElement.innerHTML = `
                <span style="font-size: 1.5rem; font-weight: bold; color: #059669;">৳${comboOffer.combo_price}</span>
                <span style="text-decoration: line-through; color: #9ca3af; margin-left: 10px;">৳${comboOffer.original_price}</span>
                <span style="color: #dc2626; font-weight: 600; margin-left: 10px;">Save ৳${savings}</span>
                <span style="background: #dc2626; color: white; padding: 4px 8px; border-radius: 4px; margin-left: 10px; font-size: 12px;">-${discountPercentage}% OFF</span>
            `;
        }

        // Modify existing buttons for combo offer functionality
        this.setupComboButtonHandlers(comboOffer);
    }

    removeDuplicatePriceElements() {
        // Remove all duplicate price elements except the main one
        const allPriceElements = document.querySelectorAll('#updateOfferPrice, .oldprice, .original-price, .discount-badge');
        const mainPriceElement = document.getElementById('updateOfferPrice');
        
        allPriceElements.forEach(element => {
            if (element !== mainPriceElement) {
                element.remove();
            }
        });

        // Also remove any duplicate pricearea containers
        const priceAreas = document.querySelectorAll('.pricearea');
        if (priceAreas.length > 1) {
            // Keep only the first one
            for (let i = 1; i < priceAreas.length; i++) {
                priceAreas[i].remove();
            }
        }
    }

    setupComboButtonHandlers(comboOffer) {
        // Get existing buttons
        const cartBtn = document.querySelector('.single-cart-btn');
        const buyNowBtn = document.querySelector('.single-buynow-btn');
        const quantityInput = document.getElementById('sharedQuantity');

        if (cartBtn) {
            // Remove existing event listeners
            cartBtn.replaceWith(cartBtn.cloneNode(true));
            const newCartBtn = document.querySelector('.single-cart-btn');
            
            // Add combo-specific functionality
            newCartBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.addComboToCart(comboOffer.id);
            });
            
            // Initially disable until selections are complete
            newCartBtn.disabled = true;
            newCartBtn.style.opacity = '0.6';
            newCartBtn.style.cursor = 'not-allowed';
        }

        if (buyNowBtn) {
            // Remove existing event listeners
            buyNowBtn.replaceWith(buyNowBtn.cloneNode(true));
            const newBuyNowBtn = document.querySelector('.single-buynow-btn');
            
            // Add combo-specific functionality
            newBuyNowBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.buyComboNow(comboOffer.id);
            });
            
            // Initially disable until selections are complete
            newBuyNowBtn.disabled = true;
            newBuyNowBtn.style.opacity = '0.6';
            newBuyNowBtn.style.cursor = 'not-allowed';
        }

        if (quantityInput) {
            // Update quantity input for combo offers
            quantityInput.addEventListener('change', (e) => {
                this.updateComboQuantity(parseInt(e.target.value) || 1);
            });
        }
    }

    generateSelectionBoxes(count) {
        let boxes = '';
        for (let i = 0; i < count; i++) {
            boxes += `
                <div class="combo-selection-box" data-slot="${i}">
                    <div class="selection-box-content">
                        <span class="selectplus">+</span>
                        <span>Please select a product!</span>
                    </div>
                </div>
            `;
        }
        return boxes;
    }

    generateSelectionSummary(count) {
        let summary = '';
        for (let i = 0; i < count; i++) {
            summary += `
                <div class="selection-summary-item" data-slot="${i}">
                    <i class="fas fa-file-alt"></i>
                    <span>Please select your product!</span>
                </div>
            `;
        }
        return summary;
    }

    openProductSelectionPopup(selectionBox) {
        const comboId = selectionBox.closest('.combo-offer').data('combo-id');
        const slotIndex = selectionBox.data('slot');
        

        
        // Get the combo offer data
        const comboOffer = this.getComboOfferData(comboId);
        if (!comboOffer) {
            return;
        }
        
        // Get available variation combinations
        const availableItems = comboOffer.available_items || [];
        if (availableItems.length === 0) {
            return;
        }
        
        // Show color selection popup
        this.showColorSelectionPopup(availableItems, comboId, slotIndex);
    }

    getComboOfferData(comboId) {
        // Store combo offer data when loading
        if (!this.comboOffersData) {
            this.comboOffersData = [];
        }
        return this.comboOffersData.find(offer => offer.id == comboId);
    }

    showColorSelectionPopup(availableItems, comboId, slotIndex) {
        // Get the combo offer data
        const comboOffer = this.getComboOfferData(comboId);
        
        const popupHtml = `
            <div class="combo-popup-overlay">
                <div class="combo-popup">
                    <div class="combo-popup-header">
                        <h3>Select Your Product</h3>
                        <button class="combo-popup-close">&times;</button>
                    </div>
                    <div class="combo-popup-content">
                        <div class="product-selection-step">
                            <div class="product-grid">
                                ${this.generateProductGrid(availableItems, comboId, slotIndex)}
                            </div>
                        </div>
                        
                        <div class="size-selection-step" style="display: none;">
                            <div class="selected-product-info">
                                <h4>Selected Product: <span class="selected-product-name"></span></h4>
                                <button class="back-to-products">← Back to Products</button>
                            </div>
                            <div class="size-grid">
                                <div class="size-options"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        $('body').append(popupHtml);
        this.currentComboOffer = { id: comboId, slotIndex: slotIndex };
        this.availableItems = availableItems;
        this.currentStep = 'product';
    }

    generateProductGrid(availableItems, comboId, slotIndex) {
        let gridHtml = '';
        
        availableItems.forEach(item => {
            const imageSrc = item.product_image || 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDEwMCAxMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik0zMCAzMEg3MFY3MEgzMFYzMFoiIGZpbGw9IiM5Q0EzQUYiLz4KPHN2ZyB4PSIzNSIgeT0iMzUiIHdpZHRoPSIzMCIgaGVpZ2h0PSIzMCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8cGF0aCBkPSJNMTkgM0g1QzMuOSAzIDMgMy45IDMgNVYxOUMzIDIwLjEgMy45IDIxIDUgMjFIMTlDMjAuMSAyMSAyMSAyMC4xIDIxIDE5VjVDMjEgMy45IDIwLjEgMyAxOSAzWk0xOSAxOUg1VjVIMTlWMTlaIiBmaWxsPSJ3aGl0ZSIvPgo8cGF0aCBkPSJNMTQgMTJIMTBWMTBIMTRWMTJaIiBmaWxsPSJ3aGl0ZSIvPgo8L3N2Zz4KPC9zdmc+';
            gridHtml += `
                <div class="product-item" 
                     data-product-id="${item.product_id}"
                     data-product-title="${item.product_title}"
                     data-combo-id="${comboId}"
                     data-slot-index="${slotIndex}">
                    <div class="product-image">
                        <img src="${imageSrc}" alt="${item.product_title}" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDEwMCAxMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik0zMCAzMEg3MFY3MEgzMFYzMFoiIGZpbGw9IiM5Q0EzQUYiLz4KPHN2ZyB4PSIzNSIgeT0iMzUiIHdpZHRoPSIzMCIgaGVpZ2h0PSIzMCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8cGF0aCBkPSJNMTkgM0g1QzMuOSAzIDMgMy45IDMgNVYxOUMzIDIwLjEgMy45IDIxIDUgMjFIMTlDMjAuMSAyMSAyMSAyMC4xIDIxIDE5VjVDMjEgMy45IDIwLjEgMyAxOSAzWk0xOSAxOUg1VjVIMTlWMTlaIiBmaWxsPSJ3aGl0ZSIvPgo8cGF0aCBkPSJNMTQgMTJIMTBWMTBIMTRWMTJaIiBmaWxsPSJ3aGl0ZSIvPgo8L3N2Zz4KPC9zdmc+';">
                    </div>
                    <div class="product-details">
                        <h4>${item.product_title}</h4>
                        <p class="product-variations">${item.variations.length} sizes available</p>
                    </div>
                </div>
            `;
        });
        
        return gridHtml;
    }

    selectProduct(productItem) {
        const productId = productItem.data('product-id');
        const productTitle = productItem.data('product-title');
        const comboId = productItem.data('combo-id');
        const slotIndex = productItem.data('slot-index');
        
        // Store selected product
        this.selectedProduct = { id: productId, title: productTitle };
        
        // Show size selection for this product
        this.showSizeSelection(productTitle, comboId, slotIndex);
    }

    showSizeSelection(productTitle, comboId, slotIndex) {

        
        // Find the selected product's variations
        const selectedProduct = this.availableItems.find(item => item.product_id == this.selectedProduct.id);
        
        if (!selectedProduct || !selectedProduct.variations) {
            return;
        }
        
        // Update popup to show size selection
        $('.combo-popup-header h3').text('Select Size');
        $('.selected-product-name').text(productTitle);
        $('.product-selection-step').hide();
        $('.size-selection-step').show();
        
        // Generate size grid
        const sizeGridHtml = this.generateSizeGrid(selectedProduct.variations, comboId, slotIndex, selectedProduct.id);
        $('.size-options').html(sizeGridHtml);
        
        this.currentStep = 'size';
    }

    generateSizeGrid(variations, comboId, slotIndex, productId = null) {
        let gridHtml = '';
        
        variations.forEach(variation => {
            const imageSrc = variation.image || '/images/placeholder.jpg';
            gridHtml += `
                <div class="size-item" 
                     data-size-name="${variation.display_name || variation.name || 'Size'}"
                     data-variation-id="${variation.id}"
                     data-combo-id="${comboId}"
                     data-slot-index="${slotIndex}"
                     data-product-id="${productId || ''}">
                    <div class="size-item-content">
                        <div class="size-image">
                            <img src="${imageSrc}" alt="${variation.display_name || variation.name}" onerror="this.src='/images/placeholder.jpg'">
                        </div>
                        <div class="size-details">
                            <div class="size-circle">${variation.display_name || variation.name}</div>
                            <span class="size-name">${variation.display_name || variation.name}</span>
                            <span class="size-price">৳${variation.price || variation.offer_price || variation.regular_price || 0}</span>
                            <span class="size-stock">Stock: ${variation.stock_quantity || 0}</span>
                        </div>
                    </div>
                </div>
            `;
        });
        
        return gridHtml;
    }

    selectSize(sizeItem) {
        const sizeName = sizeItem.data('size-name');
        const variationId = sizeItem.data('variation-id');
        const comboId = sizeItem.data('combo-id');
        const slotIndex = sizeItem.data('slot-index');
        
        // Get the combo offer data
        const comboOffer = this.getComboOfferData(comboId);
        if (!comboOffer) {
            return;
        }
        
        // Find the selected variation from the combo offer data
        let selectedItem = null;
        let variation = null;
        
        // First try to find by variation_combination.id
        selectedItem = comboOffer.available_items.find(item => 
            item.variation_combination && item.variation_combination.id == variationId
        );
        
        if (selectedItem) {
            variation = selectedItem.variation_combination;
        } else {
            // Try to find by variation_combinations array
            selectedItem = comboOffer.available_items.find(item => 
                item.variation_combinations && item.variation_combinations.some(v => v.id == variationId)
            );
            
            if (selectedItem) {
                variation = selectedItem.variation_combinations.find(v => v.id == variationId);
            } else {
                // Try to find by variations array (which is what we see in the data)
                selectedItem = comboOffer.available_items.find(item => 
                    item.variations && item.variations.some(v => v.id == variationId)
                );
                
                if (selectedItem) {
                    variation = selectedItem.variations.find(v => v.id == variationId);
                }
            }
        }
        
        // If still not found, try to find by product_id and then match variation
        if (!selectedItem) {
            const productId = sizeItem.data('product-id');
            if (productId) {
                selectedItem = comboOffer.available_items.find(item => item.product_id == productId);
                if (selectedItem && selectedItem.variation_combinations) {
                    variation = selectedItem.variation_combinations.find(v => v.id == variationId);
                }
            }
        }
        
        if (selectedItem && variation) {
            // Create selection data
            const selectionData = {
                product_id: selectedItem.product_id,
                product_title: selectedItem.product_title || selectedItem.product?.name || 'Product',
                product_image: selectedItem.product_image || selectedItem.product?.thumb_image || '/images/placeholder.jpg',
                variation_id: variationId,
                variation_name: variation.display_name || variation.name || sizeName,
                variation_image: variation.featured_image || variation.image || selectedItem.product_image,
                price: variation.offer_price || variation.regular_price || variation.price || 0
            };
            
            // Update the selection display
            this.updateSelectionDisplay(selectionData, slotIndex);
            
            // Close the popup
            this.closePopup();
            
            // Check if all items are selected
            this.checkComboCompletion(comboId);
        } else {
            // Fallback: Create selection data from the size item data
            const selectedProduct = this.selectedProduct;
            if (selectedProduct) {
                const selectionData = {
                    product_id: selectedProduct.id,
                    product_title: selectedProduct.title || selectedProduct.name || 'Product',
                    product_image: selectedProduct.thumb_image || selectedProduct.product_image || '/images/placeholder.jpg',
                    variation_id: variationId,
                    variation_name: sizeName,
                    variation_image: '/images/placeholder.jpg',
                    price: 0
                };
                
                // Update the selection display
                this.updateSelectionDisplay(selectionData, slotIndex);
                
                // Close the popup
                this.closePopup();
                
                // Check if all items are selected
                this.checkComboCompletion(comboId);
            } else {
    
                alert('Selected variation not found. Please try again.');
            }
        }
    }

    completeSelection(productId, variationId = null) {
        const { id: comboId, slotIndex } = this.currentComboOffer;
        
        $.ajax({
            url: '/combo/selection/add',
            method: 'POST',
            data: {
                combo_offer_id: comboId,
                product_id: productId,
                variation_combination_id: variationId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: (response) => {
                if (response.success) {
                    this.updateSelectionDisplay(response.data.selection, slotIndex);
                    this.closePopup();
                    this.updateComboSummary(response.data.summary);
                } else {
                    alert(response.message || 'Error adding selection. Please try again.');
                }
            },
            error: (xhr) => {
                alert('Error adding selection. Please try again.');
            }
        });
    }

    updateSelectionDisplay(selectionData, slotIndex) {
        // Validate selection data
        if (!selectionData) {
            return;
        }

        // Ensure required fields exist with fallbacks
        const productTitle = selectionData.product_title || 'Product';
        const variationName = selectionData.variation_name || 'Selected';
        const imageSrc = selectionData.variation_image || selectionData.product_image || '/images/placeholder.jpg';
        
        // Update the selection box
        const selectionBox = $(`.combo-selection-box[data-slot="${slotIndex}"]`);
        if (selectionBox.length === 0) {
            return;
        }

        selectionBox.addClass('selected');
        selectionBox.find('.selection-box-content').html(`
            <div class="selected-item">
                <img src="${imageSrc}" alt="${productTitle}" onerror="this.src='/images/placeholder.jpg'">
                <span>${productTitle} - ${variationName}</span>
            </div>
        `);

        // Update the summary if it exists
        const summaryItem = $(`.selection-summary-item[data-slot="${slotIndex}"]`);
        if (summaryItem.length > 0) {
            summaryItem.html(`
                <i class="fas fa-check"></i>
                <span>${productTitle} - ${variationName}</span>
            `);
        }
        
        // Store the selection data
        selectionBox.data('selected-selection', selectionData);
    }

    updateComboSummary(summary) {
        const comboOffer = $(`.combo-offer[data-combo-id="${summary.combo_offer_id}"]`);
        
        // Update validation message
        const validationMsg = comboOffer.find('.combo-validation-message');
        if (summary.is_complete) {
            validationMsg.html('<i class="fas fa-check"></i><span>Selection complete!</span>');
            comboOffer.find('.single-cart-btn, .single-buynow-btn').prop('disabled', false);
            comboOffer.find('.single-cart-btn, .single-buynow-btn').css({
                'opacity': '1',
                'cursor': 'pointer'
            });
        } else {
            const remaining = summary.required_count - summary.selected_count;
            validationMsg.html(`<i class="fas fa-info"></i><span>Please select ${remaining} more item(s).</span>`);
            comboOffer.find('.single-cart-btn, .single-buynow-btn').prop('disabled', true);
            comboOffer.find('.single-cart-btn, .single-buynow-btn').css({
                'opacity': '0.6',
                'cursor': 'not-allowed'
            });
        }
    }

    updateQuantity(quantityInput) {
        const quantity = parseInt(quantityInput.val());
        if (quantity < 1) quantityInput.val(1);
        if (quantity > 10) quantityInput.val(10);
    }

    decreaseQuantity() {
        const quantityInput = $('.combo-quantity');
        let quantity = parseInt(quantityInput.val()) || 1;
        if (quantity > 1) {
            quantityInput.val(quantity - 1);
        }
    }

    increaseQuantity() {
        const quantityInput = $('.combo-quantity');
        let quantity = parseInt(quantityInput.val()) || 1;
        if (quantity < 10) {
            quantityInput.val(quantity + 1);
        }
    }

    addComboToCart(comboId) {

        
        // Prevent duplicate submissions
        if (this.isSubmitting) {
            return;
        }
        
        this.isSubmitting = true;
        
        // Check if combo selection is complete
        if (!this.isComboSelectionComplete(comboId)) {
            this.isSubmitting = false;
            alert('Please select all required products for this combo offer.');
            return;
        }

        // Get combo quantity and selections
        const quantity = parseInt(document.getElementById('sharedQuantity').value) || 1;
        const selections = this.getComboSelections(comboId);
        
        if (!selections || selections.length === 0) {
            this.isSubmitting = false;
            alert('Please select all required products for this combo offer.');
            return;
        }

        // Create form data for combo cart
        const formData = new FormData();
        formData.append('combo_offer_id', comboId);
        formData.append('quantity', quantity);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        // Add selections as JSON
        selections.forEach((selection, index) => {
            formData.append(`selections[${index}][product_id]`, selection.product_id);
            formData.append(`selections[${index}][variation_id]`, selection.variation_id || '');
            formData.append(`selections[${index}][slot_index]`, selection.slot_index);
        });

        // Submit to combo cart route
        fetch('/cart/combo/store', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.status === 200) {
                window.location.reload();
            } else {
                this.isSubmitting = false;
                alert('Error adding combo to cart. Please try again.');
            }
            return response.text();
        })
        .catch(error => {
            this.isSubmitting = false;
            alert('Error adding combo to cart. Please try again.');
        });
    }

    getComboSelections(comboId) {
        const selections = [];
        const comboOffer = $(`.combo-offer[data-combo-id="${comboId}"]`);
        
        comboOffer.find('.combo-selection-box').each(function() {
            const selectionBox = $(this);
            const slotIndex = selectionBox.data('slot');
            const selectedData = selectionBox.data('selected-selection');
            
            if (selectedData) {
                selections.push({
                    product_id: selectedData.product_id,
                    variation_id: selectedData.variation_id,
                    slot_index: slotIndex
                });
            }
        });
        
        return selections;
    }

    buyComboNow(comboId) {
        // Check if combo selection is complete
        if (!this.isComboSelectionComplete(comboId)) {
            alert('Please select all required products for this combo offer.');
            return;
        }

        // Get combo quantity
        const quantity = parseInt(document.getElementById('sharedQuantity').value) || 1;
        
        // Get all selections for this combo
        const selections = this.getComboSelections(comboId);
        
        if (!selections || selections.length === 0) {
            alert('Please select all required products for this combo offer.');
            return;
        }

        // Create form data for combo purchase
        const formData = new FormData();
        formData.append('combo_offer_id', comboId);
        formData.append('quantity', quantity);
        formData.append('price', this.getComboOfferData(comboId).combo_price);
        
        // Add selections as JSON
        selections.forEach((selection, index) => {
            formData.append(`selections[${index}][product_id]`, selection.product_id);
            formData.append(`selections[${index}][variation_id]`, selection.variation_id || '');
            formData.append(`selections[${index}][slot_index]`, selection.slot_index);
        });

        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrfToken) {
            alert('Security token not found. Please refresh the page and try again.');
            return;
        }
        
        // Submit to buy now route using form submission for reliable redirects
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/buy/combo/store';
        
        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);
        
        // Add form data
        for (let [key, value] of formData.entries()) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = value;
            form.appendChild(input);
        }
        
        // Submit the form
        document.body.appendChild(form);
        form.submit();
    }

    updateComboQuantity(quantity) {
        // Update combo price display when quantity changes
        const comboOffer = document.querySelector('.combo-offer');
        if (comboOffer) {
            const comboId = comboOffer.dataset.comboId;
            const comboData = this.getComboOfferData(comboId);
            
            if (comboData) {
                const totalPrice = parseFloat(comboData.combo_price) * quantity;
                const savings = (comboData.original_price - comboData.combo_price) * quantity;
                const discountPercentage = Math.round(((comboData.original_price - comboData.combo_price) / comboData.original_price) * 100);
                
                const priceElement = document.getElementById('updateOfferPrice');
                if (priceElement) {
                    priceElement.innerHTML = `
                        <span style="font-size: 1.5rem; font-weight: bold; color: #059669;">৳${totalPrice.toFixed(2)}</span>
                        <span style="text-decoration: line-through; color: #9ca3af; margin-left: 10px;">৳${(comboData.original_price * quantity).toFixed(2)}</span>
                        <span style="color: #dc2626; font-weight: 600; margin-left: 10px;">Save ৳${savings.toFixed(2)}</span>
                        <span style="background: #dc2626; color: white; padding: 4px 8px; border-radius: 4px; margin-left: 10px; font-size: 12px;">-${discountPercentage}% OFF</span>
                    `;
                }
            }
        }
    }

    isComboSelectionComplete(comboId) {
        const comboOffer = document.querySelector(`[data-combo-id="${comboId}"]`);
        
        if (!comboOffer) {
            return false;
        }
        
        const requiredCount = parseInt(comboOffer.dataset.itemsCount);
        const selectedBoxes = comboOffer.querySelectorAll('.combo-selection-box.selected');
        const isComplete = selectedBoxes.length >= requiredCount;
        
        return isComplete;
    }

    closePopup() {
        $('.combo-popup-overlay').remove();
        this.currentComboOffer = null;
        this.availableProducts = [];
        this.selectedColor = null;
        this.selectedSize = null;
    }

    showColorSelection() {
        $('.combo-popup-header h3').text('Select Color');
        $('.size-selection-step').hide();
        $('.color-selection-step').show();
        this.currentStep = 'color';
        this.selectedColor = null;
    }

    showProductSelection() {
        $('.combo-popup-header h3').text('Select Product');
        $('.product-selection-step').show();
        $('.size-selection-step').hide();
        this.currentStep = 'product';
    }

    selectVariation(variationItem) {
        const variationId = variationItem.data('variation-id');
        const comboId = variationItem.data('combo-id');
        const slotIndex = variationItem.data('slot-index');
        
        // Get the combo offer data
        const comboOffer = this.getComboOfferData(comboId);
        const selectedItem = comboOffer.available_items.find(item => 
            item.variation_combination && item.variation_combination.id == variationId
        );
        
        if (selectedItem) {
            // Update the selection display
            this.updateSelectionDisplay(selectedItem, slotIndex);
            
            // Close the popup
            this.closePopup();
            
            // Check if all items are selected
            this.checkComboCompletion(comboId);
        }
    }

    updateSelectionDisplay(selectedItem, slotIndex) {
        // Validate selectedItem
        if (!selectedItem) {
            return;
        }

        // Handle different data structures
        let displayName, imageSrc;
        
        if (selectedItem.variation_combination) {
            // Standard structure with variation_combination
            const variation = selectedItem.variation_combination;
            displayName = variation.display_name || variation.name || 'Selected Product';
            imageSrc = selectedItem.product_image || '/images/placeholder.jpg';
        } else if (selectedItem.variation_name) {
            // Fallback structure (from selectSize fallback)
            displayName = selectedItem.variation_name;
            imageSrc = selectedItem.product_image || '/images/placeholder.jpg';
        } else {
            // Generic fallback
            displayName = 'Selected Product';
            imageSrc = '/images/placeholder.jpg';
        }
        
        // Update the selection box
        const selectionBox = $(`.combo-selection-box[data-slot="${slotIndex}"]`);
        if (selectionBox.length === 0) {
            return;
        }

        selectionBox.addClass('selected');
        selectionBox.find('.selection-box-content').html(`
            <div class="selected-item">
                <img src="${imageSrc}" alt="${displayName}" onerror="this.src='/images/placeholder.jpg'">
                <span>${displayName}</span>
            </div>
        `);
        
        // Update the summary if it exists
        const summaryItem = $(`.selection-summary-item[data-slot="${slotIndex}"]`);
        if (summaryItem.length > 0) {
            summaryItem.html(`
                <i class="fas fa-check"></i>
                <span>${displayName}</span>
            `);
        }
        
        // Store the selection data
        selectionBox.data('selected-selection', selectedItem);
    }

    checkComboCompletion(comboId) {
        const comboOffer = $(`.combo-offer[data-combo-id="${comboId}"]`);
        const selectionBoxes = comboOffer.find('.combo-selection-box');
        const selectedCount = selectionBoxes.filter(function() {
            return $(this).data('selected-selection') !== undefined;
        }).length;
        
        const totalRequired = comboOffer.data('items-count') || selectionBoxes.length;
        
        if (selectedCount >= totalRequired) {
            // Enable existing add to cart and buy now buttons
            const cartBtn = document.querySelector('.single-cart-btn');
            const buyNowBtn = document.querySelector('.single-buynow-btn');
            
            if (cartBtn) {
                cartBtn.disabled = false;
                cartBtn.style.opacity = '1';
                cartBtn.style.cursor = 'pointer';
            }
            
            if (buyNowBtn) {
                buyNowBtn.disabled = false;
                buyNowBtn.style.opacity = '1';
                buyNowBtn.style.cursor = 'pointer';
            }
            
            // Update validation message
            comboOffer.find('.combo-validation-message').html(`
                <i class="fas fa-check"></i>
                <span>All items selected! Ready to add to cart.</span>
            `);
        }
    }
}

// Initialize combo offer system when document is ready
$(document).ready(function() {
    if ($('.pcontainer').length > 0) {
        window.comboOfferSystem = new ComboOfferSystem();
        
        // Intercept cart form submission for combo offers
        $(document).on('submit', '#cartForm', function(e) {
            const comboOffer = $('.combo-offer').first();
            if (comboOffer.length > 0) {
                e.preventDefault();
                const comboId = comboOffer.data('combo-id');
                window.comboOfferSystem.addComboToCart(comboId);
            }
        });
        
        // Intercept buy now form submission for combo offers
        $(document).on('submit', '#buyNowForm', function(e) {
            const comboOffer = $('.combo-offer').first();
            if (comboOffer.length > 0) {
                e.preventDefault();
                const comboId = comboOffer.data('combo-id');
                window.comboOfferSystem.buyComboNow(comboId);
            }
        });
    }
}); 