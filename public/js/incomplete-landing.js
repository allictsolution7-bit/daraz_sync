// public/js/incomplete-landing.js

// Utility: Validate Bangladeshi phone number (length 14, 13, or 11)
function isValidBDPhone(phone) {
    phone = phone.replace(/\s|-|_/g, '');
    if (phone.length === 14 && /^\+8801\d{9}$/.test(phone)) return true;
    if (phone.length === 13 && /^8801\d{9}$/.test(phone)) return true;
    if (phone.length === 11 && /^01\d{9}$/.test(phone)) return true;
    return false;
}

let lastSentPhone = '';
let lastSentData = '';

function getLandingProductDetails() {
    let productName = $('.item-name').first().text().trim();
    let productId = $('input[name="product_id"]').val() || '';
    let price = parseFloat($('input[name="price"]').val()) || 0;
    let quantity = parseInt($('input[name="quantity"]').val()) || 1;
    
    // Get selected combination ID from the new variation system
    let combinationId = '';
    let selectedVariations = [];
    
    // Check if any variation radio is selected
    let $checkedRadio = $('.variation-radio:checked');
    if ($checkedRadio.length > 0) {
        combinationId = $checkedRadio.attr('data-combination-id') || '';
        
        // Get all selected variation names
        $('.variation-radio:checked').each(function() {
            let $label = $('label[for="' + $(this).attr('id') + '"]');
            let variationName = $label.find('.variation-name').text().trim();
            if (variationName) {
                selectedVariations.push(variationName);
            }
        });
    }
    
    // Always use variations array for consistency
    return [{
        product_id: productId,
        name: productName,
        price: price,
        quantity: quantity,
        combination_id: combinationId, // Use new combination_id instead of variation_option_id
        variations: selectedVariations
    }];
}

function getLandingOrderTotal() {
    // Get total from #subtotal (removes currency symbol)
    let totalText = $('#subtotal').text() || '';
    let total = parseFloat(totalText.replace(/[^\d.]/g, '')) || 0;
    return total;
}

function getLandingShippingCost() {
    let val = $('input[name="shipping"]').val();
    let cost = parseFloat(val) || 0;
    return cost;
}

function getLandingIncompleteOrderData() {
    return {
        phone: $('input[name="phone"]').val() || '',
        name: $('input[name="name"]').val() || '',
        address: $('#address').val() || '',
        // upazila and city are not present in the form, so leave blank
        upazila: '',
        city: '',
        // message is commented out, so will always be empty
        message: $('textarea[name="message"]').val() || '',
        source: 'landing',
        product_details: JSON.stringify(getLandingProductDetails()),
        total: getLandingOrderTotal(),
        payment_method: $('input[name="payment_method"]:checked').val() || '',
        shipping_method: $('input[name="shipping_area"]:checked').val() || '',
        shipping_cost: getLandingShippingCost()
    };
}

function sendLandingIncompleteOrder() {
    let data = getLandingIncompleteOrderData();
    let phone = data.phone;
    if (!isValidBDPhone(phone)) return;
    let dataString = JSON.stringify(data);
    if (lastSentPhone === phone && lastSentData === dataString) return;
    lastSentPhone = phone;
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

function triggerLandingIncompleteOrderAjax() {
    let phone = $('input[name="phone"]').val();
    if (isValidBDPhone(phone)) {
        sendLandingIncompleteOrder();
    }
}

$(document).ready(function () {
    // On phone input: send instantly when length is 11, 13, or 14 and valid
    $('input[name="phone"]').on('input', function () {
        let phone = $(this).val();
        let len = phone.replace(/\s|-|_/g, '').length;
        if ((len === 11 || len === 13 || len === 14) && isValidBDPhone(phone)) {
            sendLandingIncompleteOrder();
        }
    });
    // On phone blur: fallback for users who paste or finish editing
    $('input[name="phone"]').on('blur', function () {
        let phone = $(this).val();
        if (isValidBDPhone(phone)) {
            sendLandingIncompleteOrder();
        }
    });
    // On other field blur/change, if phone is valid, update
    $('input[name="name"], #address').on('blur change', function () {
        let phone = $('input[name="phone"]').val();
        if (isValidBDPhone(phone)) {
            sendLandingIncompleteOrder();
        }
    });

    // Listen for changes to shipping method, payment gateway, quantity, variation, and total
    // Updated to listen for variation-radio changes instead of variation_option_id
    $(document).on('change', 'input[name="shipping_area"], input[name="payment_method"], input[name="quantity"], .variation-radio', function () {
        triggerLandingIncompleteOrderAjax();
    });

    // If total can change dynamically (e.g., via JS), listen for DOM changes
    const totalObserver = new MutationObserver(function () {
        triggerLandingIncompleteOrderAjax();
    });
    if ($('#subtotal').length) {
        totalObserver.observe($('#subtotal')[0], { childList: true, subtree: true, characterData: true });
    }
});