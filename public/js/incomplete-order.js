// Utility: Validate Bangladeshi phone number (length 14, 13, or 11)
function isValidBDPhone(phone) {
    phone = phone.replace(/\s|-|_/g, '');
    if (phone.length === 14 && /^\+8801\d{9}$/.test(phone)) return true;
    if (phone.length === 13 && /^8801\d{9}$/.test(phone)) return true;
    if (phone.length === 11 && /^01\d{9}$/.test(phone)) return true;
    return false;
}

let lastSentPhone = '';
let lastSentSource = '';
let lastSentData = '';

function getOrderTotal() {
    // Try to get the total from the DOM (should match the displayed total)
    let totalText = $('.order-total').first().text() || '';
    let total = parseFloat(totalText.replace(/[^\d.]/g, '')) || 0;
    return total;
}

function getProductDetails() {
    let products = [];
    
    // Check if this is a combo purchase - handle both buynow and checkout
    const isComboPurchase = $('input[name="is_combo_purchase"]').val() === '1';
    const hasComboItems = $('.cct-table tr[data-combo-id]').length > 0 || $('.combo-selections').length > 0;
    
    // For buynow page with combo purchase
    if (isComboPurchase) {
        // Handle combo purchase
        const comboOfferId = $('input[name="combo_offer_id"]').val() || $('.cct-table tr[data-combo-id]').data('combo-id');
        const comboQuantity = parseInt($('input[name="quantity"]').val()) || 1;
        const comboPrice = parseFloat($('input[name="price"]').val()) || 0;
        
        // Get combo title from the display
        let comboTitle = '';
        if ($('.combo-title strong').length > 0) {
            comboTitle = $('.combo-title strong').text().trim();
        }
        
        // Get combo selections - handle both buynow and checkout structures
        let comboSelections = [];
        
        // First try buynow structure (direct combo-selection-item)
        $('.combo-selection-item').each(function() {
            const selectionText = $(this).text().trim();
            if (selectionText) {
                comboSelections.push(selectionText);
            }
        });
        
        // If no selections found, try checkout structure (within combo-selections container)
        if (comboSelections.length === 0) {
            $('.combo-selections .combo-selection-item').each(function() {
                const productName = $(this).find('.product-name').text().trim();
                const variationName = $(this).find('.variation-name').text().trim();
                
                let selectionText = productName;
                if (variationName) {
                    selectionText += variationName;
                }
                
                if (selectionText) {
                    comboSelections.push(selectionText);
                }
            });
        }
        
        // If still no selections found, try to get from combo quantity display
        if (comboSelections.length === 0) {
            const comboQuantityText = $('.combo-quantity').text().trim();
            if (comboQuantityText) {
                comboSelections.push(comboQuantityText);
            }
        }
        
        products.push({
            product_id: comboOfferId,
            name: comboTitle || 'Combo Offer',
            price: comboPrice,
            quantity: comboQuantity,
            variations: comboSelections,
            is_combo: true,
            combo_offer_id: comboOfferId
        });
        
    } else if ($('.cct-table tr[data-product-id]').length > 0) {
        // For checkout page: process all products including combos and regular products
        $('.cct-table tr[data-product-id]').each(function() {
            let productId = $(this).data('product-id');
            let productName = $(this).find('.cct-title a').text().trim();
            let quantity = 0;
            let variations = [];
            let isCombo = false;
            let comboOfferId = null;
            
            // Check if this is a combo item
            if ($(this).find('.combo-selections').length > 0) {
                isCombo = true;
                comboOfferId = productId; // Use product ID as combo offer ID for checkout
                
                // Get combo quantity from the data attribute
                const comboQuantityFromData = $(this).data('combo-quantity');
                if (comboQuantityFromData) {
                    quantity = parseInt(comboQuantityFromData) || 1;
                } else {
                    // Fallback: try to get quantity from hidden inputs or calculate from price
                    const priceText = $(this).find('.cct-price').text().trim();
                    const price = parseFloat(priceText.replace(/[^\d.]/g, '')) || 0;
                    
                    let comboQuantity = 1;
                    
                    // Check if there are any hidden inputs with quantity info
                    const hiddenQuantity = $('input[name="quantity"]').val();
                    if (hiddenQuantity) {
                        comboQuantity = parseInt(hiddenQuantity) || 1;
                    } else {
                        // Try to calculate quantity from price if we have a base price
                        const basePrice = $('input[name="price"]').val();
                        if (basePrice && price > 0) {
                            const basePriceNum = parseFloat(basePrice);
                            if (basePriceNum > 0) {
                                comboQuantity = Math.round(price / basePriceNum);
                            }
                        }
                    }
                    
                    quantity = comboQuantity;
                }
                
                // Get combo selections
                $(this).find('.combo-selections .combo-selection-item').each(function() {
                    const productName = $(this).find('.product-name').text().trim();
                    const variationName = $(this).find('.variation-name').text().trim();
                    
                    let selectionText = productName;
                    if (variationName) {
                        selectionText += variationName;
                    }
                    
                    if (selectionText) {
                        variations.push(selectionText);
                    }
                });
                
                // If no combo selections found, try to get from combo quantity display
                if (variations.length === 0) {
                    const comboQuantityText = $(this).find('.combo-quantity').text().trim();
                    if (comboQuantityText) {
                        variations.push(comboQuantityText);
                    }
                }
            } else {
                // Regular product - get variations
                // Check for new variation combination structure first
                $(this).find('.variation-item').each(function() {
                    let variationText = $(this).find('.variation-combination').text().trim();
                    let qtyText = $(this).find('.variation-qty').text().trim();
                    let qtyMatch = qtyText.match(/x(\d+)/);
                    let itemQty = qtyMatch ? parseInt(qtyMatch[1]) : 1;
                    quantity += itemQty;
                    
                    // Get price information
                    let priceText = '';
                    let originalPrice = $(this).find('.original-price').text().trim();
                    let offerPrice = $(this).find('.offer-price').text().trim();
                    let regularPrice = $(this).find('.regular-price').text().trim();
                    
                    if (offerPrice) {
                        priceText = `${originalPrice} → ${offerPrice}`;
                    } else if (regularPrice) {
                        priceText = regularPrice;
                    }
                    
                    // Get description if available
                    let description = $(this).find('.combination-description').text().trim();
                    
                    let variationDetail = variationText;
                    if (priceText) variationDetail += ` - ${priceText}`;
                    if (description) variationDetail += ` (${description})`;
                    
                    variations.push(variationDetail);
                });
                
                // Fallback: if no variation items found, try old structure
                if (quantity === 0) {
                    $(this).find('.product-variations small').each(function() {
                        let txt = $(this).text().trim();
                        // Try to extract quantity from text like "Color: Red × 2"
                        let qtyMatch = txt.match(/×\s*(\d+)/);
                        if (qtyMatch) {
                            quantity += parseInt(qtyMatch[1]);
                        }
                        variations.push(txt);
                    });
                }
                
                // Fallback: if no variations, try to get quantity from "Quantity: N"
                if (quantity === 0) {
                    let qtyText = $(this).find('.product-variations small').text();
                    let matchQty = qtyText.match(/Quantity:\s*(\d+)/i);
                    if (matchQty) quantity = parseInt(matchQty[1]);
                }
                
                // Fallback: if still 0, set to 1
                if (quantity === 0) quantity = 1;
            }
            
            let price = parseFloat($(this).find('.cct-price').text().replace(/[^\d.]/g, '')) || 0;
            
            let productData = {
                product_id: productId,
                name: productName,
                price: price,
                quantity: quantity,
                variations: variations
            };
            
            if (isCombo) {
                productData.is_combo = true;
                productData.combo_offer_id = comboOfferId;
            }
            
            products.push(productData);
            
        });
    } else if ($('input[name="product_id"]').length > 0) {
        // For buynow: get from hidden fields and displayed data
        let productId = $('input[name="product_id"]').val();
        let productName = $('#p-title a').text().trim();
        
        // Get price from multiple sources for better accuracy
        let displayedPrice = parseFloat($('#p-price').text().replace(/[^\d.]/g, '')) || 0;
        let hiddenPrice = parseFloat($('input[name="price"]').val()) || 0;
        let subtotalPrice = parseFloat($('#Subtotal').text().replace(/[^\d.]/g, '')) || 0;
        
        // Alternative price extraction methods
        let displayedPriceText = $('#p-price').text().trim();
        let displayedPriceAlt = parseFloat(displayedPriceText.replace(/[৳\s,]/g, '')) || 0;
        
        // Use the better extracted price
        if (displayedPriceAlt > 0) {
            displayedPrice = displayedPriceAlt;
        }
        
        // Debug logging for troubleshooting
        // console.log('Buynow Price Debug:', {
        //     displayedPrice: displayedPrice,
        //     hiddenPrice: hiddenPrice,
        //     subtotalPrice: subtotalPrice,
        //     displayedPriceText: $('#p-price').text(),
        //     hiddenPriceText: $('input[name="price"]').val(),
        //     subtotalText: $('#Subtotal').text(),
        //     hasCombination: $('input[name="combination_id"]').length > 0
        // });
        
        // Use the most accurate price available
        let price = 0;
        if (displayedPrice > 0) {
            price = displayedPrice;
        } else if (subtotalPrice > 0) {
            price = subtotalPrice;
        } else if (hiddenPrice > 0) {
            price = hiddenPrice;
        }
        
        // Ensure price is a valid number
        price = parseFloat(price) || 0;
        
        let quantity = parseInt($('input[name="pqty"]').val()) || 1;
        
        // If price is still 0 but we have subtotal and quantity, calculate price per unit
        if (price === 0 && subtotalPrice > 0 && quantity > 0) {
            price = subtotalPrice / quantity;
        }
        
        // If price is still 0, try to use subtotal directly (for single item orders)
        if (price === 0 && subtotalPrice > 0) {
            price = subtotalPrice;
        }
        
        // Variations (optional) - check for new variation combination structure first
        let variations = [];
        
        // Check for new variation combination structure
        $('.product-variations small').each(function() {
            let variationText = $(this).text().trim();
            if (variationText) {
                variations.push(variationText);
            }
        });
        
        // Fallback: if no variations found in new structure, try old structure
        if (variations.length === 0) {
            $('input[name^="variations["]').each(function() {
                let label = $(this).attr('name');
                let value = $(this).val();
                if (label && value) {
                    variations.push(label + ': ' + value);
                } else if (value) {
                    variations.push(value);
                }
            });
        }

        
        // Ensure price is a valid number before storing
        price = parseFloat(price) || 0;
        
        products.push({
            product_id: productId,
            name: productName,
            price: price,
            quantity: quantity,
            variations: variations
        });
    }
    return products;
}

function getShippingCost() {
    let val = $('input[name="shipping"]').val();
    let cost = parseFloat(val) || 0;
    return cost;
}

function getIncompleteOrderData(source) {
    return {
        phone: $('input[name="phone"]').val(),
        name: $('input[name="name"]').val(),
        address: $('input[name="address"]').val(),
        upazila: $('input[name="upazila"]').val() || '',
        city: $('select[name="city"]').val() || '',
        message: $('textarea[name="message"]').val() || '',
        source: source,
        product_details: JSON.stringify(getProductDetails()),
        total: getOrderTotal(),
        payment_method: $('input[name="payment_method"]:checked').val() || '',
        shipping_method: $('input[name="shipping_area"]:checked').val() || '',
        shipping_cost: getShippingCost()
    };
}

function sendIncompleteOrder(source) {
    let data = getIncompleteOrderData(source);
    let phone = data.phone;
    if (!isValidBDPhone(phone)) return;
    let dataString = JSON.stringify(data);
    if (lastSentPhone === phone && lastSentSource === source && lastSentData === dataString) return;
    lastSentPhone = phone;
    lastSentSource = source;
    lastSentData = dataString;
    $.ajax({
        url: '/incomplete-order',
        type: 'POST',
        data: {
            ...data,
            _token: $('meta[name="csrf-token"]').attr('content') || (typeof csrf_token !== 'undefined' ? csrf_token : '')
        },
        success: function (res) {
            // Optionally handle success
        }
    });
}

function triggerIncompleteOrderAjax() {
    let source = window.location.pathname.includes('/buy/') ? 'buynow' : 'checkout';
    let phone = $('input[name="phone"]').val();
    if (isValidBDPhone(phone)) {
        sendIncompleteOrder(source);
    }
}

$(document).ready(function () {
    let source = window.location.pathname.includes('/buy/') ? 'buynow' : 'checkout';
    // On phone input: send instantly when length is 11, 13, or 14 and valid
    $('input[name="phone"]').on('input', function () {
        let phone = $(this).val();
        let len = phone.replace(/\s|-|_/g, '').length;
        if ((len === 11 || len === 13 || len === 14) && isValidBDPhone(phone)) {
            sendIncompleteOrder(source);
        }
    });
    // On phone blur: fallback for users who paste or finish editing
    $('input[name="phone"]').on('blur', function () {
        let phone = $(this).val();
        if (isValidBDPhone(phone)) {
            sendIncompleteOrder(source);
        }
    });
    // On other field blur/change, if phone is valid, update
    $('input[name="name"], input[name="address"], input[name="upazila"], select[name="city"], textarea[name="message"]').on('blur change', function () {
        let phone = $('input[name="phone"]').val();
        if (isValidBDPhone(phone)) {
            sendIncompleteOrder(source);
        }
    });

    // Listen for changes to shipping method, payment gateway, and total
    $(document).on('change', 'input[name="shipping_area"], input[name="payment"]', function() {
        triggerIncompleteOrderAjax();
    });

    // If total can change dynamically (e.g., via JS), listen for DOM changes
    const totalObserver = new MutationObserver(function() {
        triggerIncompleteOrderAjax();
    });
    if ($('.order-total').length) {
        totalObserver.observe($('.order-total')[0], { childList: true, subtree: true, characterData: true });
    }
}); 