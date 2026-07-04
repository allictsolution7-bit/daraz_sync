// Generate unique event ID for perfect deduplication
function generateEventId() {
    const timestamp = Date.now();
    const random = Math.random().toString(36).substring(2, 9);
    return `event_${timestamp}_${random}`;
}

// --- Advanced matching helpers (Meta) ---
function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) {
        return decodeURIComponent(parts.pop().split(';').shift());
    }
    return '';
}

function getFacebookIds() {
    // Priority: Browser cookie (set by FB Pixel) > localStorage fallback
    let fbp = getCookie('_fbp') || getStored('tdl_fbp') || '';
    let fbc = getCookie('_fbc') || '';
    const fbclid = new URLSearchParams(window.location.search).get('fbclid');

    // Only use localStorage or generate new fbc if cookie doesn't exist
    if (!fbc) {
        fbc = getStored('tdl_fbc') || '';
    }

    // Generate fbc from fbclid only if we still don't have one
    if (fbclid && !fbc) {
        fbc = 'fb.1.' + Date.now() + '.' + fbclid;
        document.cookie = `_fbc=${fbc}; path=/; max-age=7776000; samesite=lax`;
    }

    // Sync to localStorage as backup
    if (fbp && !getCookie('_fbp')) {
        document.cookie = `_fbp=${fbp}; path=/; max-age=7776000; samesite=lax`;
    }
    if (fbp) setStored('tdl_fbp', fbp);
    if (fbc) setStored('tdl_fbc', fbc);
    return { fbp, fbc };
}

function normalize(value) {
    return (value || '').toString().trim().toLowerCase();
}

function normalizePhone(value) {
    return (value || '').replace(/\D/g, '');
}

function normalizeEmail(value) {
    return normalize(value);
}

function normalizeAddress(value) {
    return (value || '').toString().trim();
}

function getFirstFieldValue(selectors, contexts) {
    const contextList = (contexts && contexts.length) ? contexts : [document];
    for (const context of contextList) {
        if (!context) {
            continue;
        }
        for (const selector of selectors) {
            const el = context.querySelector(selector);
            if (el && typeof el.value === 'string' && el.value.trim() !== '') {
                return el.value;
            }
        }
    }
    return '';
}

function getStored(key) {
    try {
        return localStorage.getItem(key) || '';
    } catch (e) {
        return '';
    }
}

function setStored(key, value) {
    try {
        localStorage.setItem(key, value || '');
    } catch (e) {
        // ignore storage issues
    }
}

// Get GA4 client_id from _ga cookie
// Format: GA1.1.1234567890.1234567890 -> extracts "1234567890.1234567890"
function getClientId() {
    const gaCookie = getCookie('_ga');
    if (gaCookie) {
        // GA4 format: GA1.1.XXXXXXXXXX.XXXXXXXXXX or GA1.2.XXXXXXXXXX.XXXXXXXXXX
        const parts = gaCookie.split('.');
        if (parts.length >= 4) {
            // Return the last two parts (client ID)
            return parts.slice(2).join('.');
        }
    }
    // Fallback: generate our own if GA4 not loaded yet
    let clientId = getStored('tdl_client_id');
    if (!clientId) {
        clientId = Date.now() + '.' + Math.floor(Math.random() * 1000000000);
        setStored('tdl_client_id', clientId);
    }
    return clientId;
}

// Get GA4 session_id from _ga_<container> cookie or generate compatible one
function getSessionId() {
    // Try to find GA4 session cookie (_ga_XXXXXXX)
    const cookies = document.cookie.split(';');
    for (let i = 0; i < cookies.length; i++) {
        const cookie = cookies[i].trim();
        // Check if this is a GA4 session cookie (starts with _ga_ followed by container ID)
        if (cookie.indexOf('_ga_') === 0) {
            const eqIndex = cookie.indexOf('=');
            if (eqIndex > 0) {
                const value = cookie.substring(eqIndex + 1);
                if (value) {
                    // New GA4 format: s1767418380$o54$g1$t1767418386$j54$l0$h0
                    // Session ID is the number after 's' prefix
                    if (value.charAt(0) === 's' && value.indexOf('$') > 0) {
                        const dollarIndex = value.indexOf('$');
                        const sessionId = value.substring(1, dollarIndex);
                        if (/^\d+$/.test(sessionId)) {
                            return sessionId;
                        }
                    }
                    // Old GA4 format: GS1.1.1234567890.1.1.1234567890.0.0.0
                    const parts = value.split('.');
                    if (parts.length >= 3 && /^\d+$/.test(parts[2])) {
                        return parts[2];
                    }
                }
            }
        }
    }
    // Fallback: generate session ID with 30-minute timeout
    const SESSION_TIMEOUT = 30 * 60 * 1000;
    let sessionData = getStored('tdl_session');
    let sessionId = '';
    let lastActivity = 0;

    if (sessionData) {
        try {
            const parsed = JSON.parse(sessionData);
            sessionId = parsed.id || '';
            lastActivity = parsed.lastActivity || 0;
        } catch (e) {
            sessionId = '';
            lastActivity = 0;
        }
    }

    const now = Date.now();
    if (!sessionId || (now - lastActivity) > SESSION_TIMEOUT) {
        sessionId = Math.floor(now / 1000).toString(); // Unix timestamp like GA4
    }

    setStored('tdl_session', JSON.stringify({ id: sessionId, lastActivity: now }));
    return sessionId;
}

function collectUserData() {
    const formContexts = [
        document.querySelector('#landing-order-form')
    ].filter(Boolean);
    const nameInput = getFirstFieldValue(['#name', 'input[name="name"]'], formContexts) ||
        getStored('tdl_name') ||
        '';
    const phoneInput = getFirstFieldValue(['#phone', 'input[name="phone"]'], formContexts) ||
        getStored('tdl_phone') ||
        '';
    const emailInput = getFirstFieldValue(['#email', 'input[name="email"]'], formContexts) ||
        getStored('tdl_email') ||
        '';
    const addressInput = getFirstFieldValue(
        ['#address', 'textarea[name="address"]', 'input[name="address"]'],
        formContexts
    ) ||
        getStored('tdl_address') ||
        '';
    const cityInput = getFirstFieldValue(
        ['#city', 'select[name="city"]', 'input[name="city"]'],
        formContexts
    ) ||
        getStored('tdl_city') ||
        '';

    const fullName = normalize(nameInput);
    const parts = fullName.split(/\s+/).filter(Boolean);
    const firstName = parts[0] || '';
    const lastName = parts.slice(1).join(' ');
    const phone = normalizePhone(phoneInput);
    const email = normalizeEmail(emailInput);
    const address = normalizeAddress(addressInput);
    const fbIds = getFacebookIds();
    const city = normalize(cityInput);

    return {
        name: fullName,
        fn: firstName,
        ln: lastName,
        ph: phone,
        phone: phone,
        em: email,
        email: email,
        ct: city,
        city: city,
        address: address,
        country: 'bangladesh',
        external_id: phone || email,
        fbp: fbIds.fbp,
        fbc: fbIds.fbc,
        client_id: getClientId(),
        session_id: getSessionId()
    };
}

function attachInputPersistence() {
    const fields = [
        { selectors: ['#name', 'input[name="name"]'], key: 'tdl_name' },
        { selectors: ['#phone', 'input[name="phone"]'], key: 'tdl_phone' },
        { selectors: ['#email', 'input[name="email"]'], key: 'tdl_email' },
        { selectors: ['#address', 'textarea[name="address"]', 'input[name="address"]'], key: 'tdl_address' },
        { selectors: ['#city', 'select[name="city"]', 'input[name="city"]'], key: 'tdl_city' }
    ];
    fields.forEach(field => {
        const elements = new Set();
        field.selectors.forEach(selector => {
            document.querySelectorAll(selector).forEach(el => elements.add(el));
        });
        elements.forEach(el => {
            const eventType = el.tagName === 'SELECT' ? 'change' : 'input';
            el.addEventListener(eventType, function() {
                setStored(field.key, this.value || '');
            });
        });
    });
}

document.addEventListener('DOMContentLoaded', function() {
    attachInputPersistence();
    // --- Get product info from .order-item ---
    var orderItem = document.querySelector('.order-item');
    if (!orderItem) return;

    var productId = orderItem.getAttribute('data-product-id') || '';
    var categoryName = orderItem.getAttribute('data-category') || '';
    var productName = document.querySelector('.item-name') ? document.querySelector('.item-name')
        .textContent.trim() : 'Unknown';
    
    // FIX: Better price extraction that handles Bangla numerals and currency symbols
    var rawPrice = '0';
    var itemTotalElement = document.querySelector('.item-total');
    if (itemTotalElement) {
        var priceText = itemTotalElement.textContent.trim();
        // Remove all non-numeric characters except dots, handle Bangla numerals
        var cleanPrice = priceText
            .replace(/[৳,]/g, '') // Remove currency symbol and commas
            .replace(/[০-৯]/g, function(match) {
                // Convert Bangla numerals to English
                var banglaToEnglish = {'০': '0', '১': '1', '২': '2', '৩': '3', '৪': '4', '৫': '5', '৬': '6', '৭': '7', '৮': '8', '৯': '9'};
                return banglaToEnglish[match] || match;
            })
            .replace(/[^0-9.]/g, ''); // Remove any remaining non-numeric characters
        
        rawPrice = cleanPrice || '0';
    }

    // Get selected variation (if any)
    var variationId = '';
    var variationName = '';
    var checkedVariations = document.querySelectorAll('input[name^="variation_option_id_"]:checked');
    if (checkedVariations.length > 0) {
        variationId = checkedVariations[0].value;
        var label = document.querySelector('label[for="variation_option_' + variationId +
            '"] .variation-name');
        variationName = label ? label.textContent.trim() : '';
    } else {
        // Check for button-based variation selection
        var selectedVariationButtons = document.querySelectorAll('.variation-option-btn.selected');
        if (selectedVariationButtons.length > 0) {
            variationName = Array.from(selectedVariationButtons).map(btn => btn.textContent.trim()).join(
                ', ');
        }
    }

    // Get quantity
    var quantity = 1;
    var quantityInput = document.getElementById('quantity');
    if (quantityInput) {
        quantity = parseInt(quantityInput.value) || 1;
    }

    // Get current page info for enhanced tracking
    var currentPage = {
        title: document.title || '',
        location: window.location.href || '',
        path: window.location.pathname || '',
        referrer: document.referrer || ''
    };

    // Get user agent and language info
    var userInfo = {
        user_agent: navigator.userAgent || '',
        language: navigator.language || 'en-US',
        screen_resolution: screen.width + 'x' + screen.height,
        viewport_size: window.innerWidth + 'x' + window.innerHeight
    };

    // Debug log to see what we're getting
    console.log('Landing Page Product Data:', {
        productId: productId,
        productName: productName,
        rawPrice: rawPrice,
        cleanPrice: rawPrice,
        categoryName: categoryName,
        variationName: variationName,
        quantity: quantity,
        currentPage: currentPage
    });

    // --- view_item event (GA4 Enhanced Ecommerce) ---
    window.dataLayer = window.dataLayer || [];
    var fbIds = getFacebookIds();
    var userData = collectUserData();
    var eventId = generateEventId();
    dataLayer.push({
        'event': 'view_item',
        'eventId': eventId,
        'event_id_variable': eventId,
        'ecommerce': {
            'currency': 'BDT',
            'value': parseFloat(rawPrice) || 0,
            'items': [{
                'item_id': productId,
                'item_name': productName,
                'price': (parseFloat(rawPrice) || 0) / quantity, // Unit price for GA4 compliance
                'item_category': categoryName,
                'item_category2': '', // Secondary category if available
                'item_category3': '', // Tertiary category if available
                'item_category4': '', // Quaternary category if available
                'item_category5': '', // Quinary category if available
                'item_variant': variationName,
                'item_brand': '', // Brand name if available
                'quantity': quantity,
                'currency': 'BDT',
                'index': 1
            }]
        },
        // Enhanced event parameters (GA4 recommended)
        'user_data': userData,
        'fbp': fbIds.fbp,
        'fbc': fbIds.fbc,
        'content_ids': [productId],
        'content_type': 'product',
        'content_name': productName,
        'timestamp': new Date().toISOString(),
        'event_time': new Date().toISOString(),
        'event_source_url': currentPage.location,
        'user_agent': userInfo.user_agent,
        'language': userInfo.language,
        'page_title': currentPage.title,
        'page_location': currentPage.location,
        'page_referrer': currentPage.referrer,
        'screen_resolution': userInfo.screen_resolution,
        'viewport_size': userInfo.viewport_size,
        'client_id': getClientId(),
        'session_id': getSessionId()
    });

    console.log('View item event fired with enhanced data');

    // --- begin_checkout event (GA4 Enhanced Ecommerce) ---
    var checkoutForm = document.getElementById('landing-order-form');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function() {
            // Re-fetch variation and quantity in case user changed them
            var checkedVariations = document.querySelectorAll(
                'input[name^="variation_option_id_"]:checked');
            var variationId = '';
            var variationName = '';
            if (checkedVariations.length > 0) {
                variationId = checkedVariations[0].value;
                var label = document.querySelector('label[for="variation_option_' + variationId +
                    '"] .variation-name');
                variationName = label ? label.textContent.trim() : '';
            } else {
                // Check for button-based variation selection
                var selectedVariationButtons = document.querySelectorAll(
                    '.variation-option-btn.selected');
                if (selectedVariationButtons.length > 0) {
                    variationName = Array.from(selectedVariationButtons).map(btn => btn.textContent
                        .trim()).join(', ');
                }
            }
            var quantity = 1;
            var quantityInput = document.getElementById('quantity');
            if (quantityInput) {
                quantity = parseInt(quantityInput.value) || 1;
            }
            
            // Use the same improved price extraction method
            var rawPrice = '0';
            var itemTotalElement = document.querySelector('.item-total');
            if (itemTotalElement) {
                var priceText = itemTotalElement.textContent.trim();
                var cleanPrice = priceText
                    .replace(/[৳,]/g, '')
                    .replace(/[০-৯]/g, function(match) {
                        var banglaToEnglish = {'০': '0', '১': '1', '২': '2', '৩': '3', '৪': '4', '৫': '5', '৬': '6', '৭': '7', '৮': '8', '৯': '9'};
                        return banglaToEnglish[match] || match;
                    })
                    .replace(/[^0-9.]/g, '');
                rawPrice = cleanPrice || '0';
            }

            // Calculate unit price for GA4 compliance
            var totalPrice = parseFloat(rawPrice) || 0;
            var unitPrice = totalPrice / quantity;
            var fbIdsSubmit = getFacebookIds();
            var userDataSubmit = collectUserData();
            var eventId = generateEventId();

            dataLayer.push({
                'event': 'begin_checkout',
                'eventId': eventId,
                'event_id_variable': eventId,
                'ecommerce': {
                    'currency': 'BDT',
                    'value': totalPrice, // Total transaction value
                    'items': [{
                        'item_id': productId,
                        'item_name': productName,
                        'price': unitPrice, // Unit price for GA4 compliance
                        'item_category': categoryName,
                        'item_category2': '', // Secondary category if available
                        'item_category3': '', // Tertiary category if available
                        'item_category4': '', // Quaternary category if available
                        'item_category5': '', // Quinary category if available
                        'item_variant': variationName,
                        'item_brand': '', // Brand name if available
                        'quantity': quantity,
                        'currency': 'BDT',
                        'index': 1
                    }]
                },
                // Enhanced event parameters (GA4 recommended)
                'user_data': userDataSubmit,
                'fbp': fbIdsSubmit.fbp,
                'fbc': fbIdsSubmit.fbc,
                'content_ids': [productId],
                'content_type': 'product',
                'content_name': productName,
                'timestamp': new Date().toISOString(),
                'event_time': new Date().toISOString(),
                'event_source_url': currentPage.location,
                'user_agent': userInfo.user_agent,
                'language': userInfo.language,
                'page_title': currentPage.title,
                'page_location': currentPage.location,
                'page_referrer': currentPage.referrer,
                'screen_resolution': userInfo.screen_resolution,
                'viewport_size': userInfo.viewport_size,
                'client_id': getClientId(),
                'session_id': getSessionId()
            });

            console.log('Begin checkout event fired with enhanced data:', {
                totalPrice: totalPrice,
                unitPrice: unitPrice,
                quantity: quantity
            });
        });
    }

    // --- Page view event (GA4 recommended) ---
    var pageFbIds = getFacebookIds();
    var pageUserData = collectUserData();
    var pageEventId = generateEventId();
    dataLayer.push({
        'event': 'page_view',
        'eventId': pageEventId,
        'event_id_variable': pageEventId,
        'page': {
            'title': currentPage.title,
            'location': currentPage.location,
            'path': currentPage.path,
            'referrer': currentPage.referrer
        },
        'user_data': pageUserData,
        'fbp': pageFbIds.fbp,
        'fbc': pageFbIds.fbc,
        'user_agent': userInfo.user_agent,
        'language': userInfo.language,
        'screen_resolution': userInfo.screen_resolution,
        'viewport_size': userInfo.viewport_size,
        'timestamp': new Date().toISOString(),
        'client_id': getClientId(),
        'session_id': getSessionId()
    });

    console.log('Page view event fired with enhanced data');
});
