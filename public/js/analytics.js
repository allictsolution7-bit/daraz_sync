        // Initialize dataLayer properly before any push operations
        window.dataLayer = window.dataLayer || [];

        // Generate unique event ID for perfect deduplication
        function generateEventId() {
            const timestamp = Date.now();
            const random = Math.random().toString(36).substring(2, 9);
            return `event_${timestamp}_${random}`;
        }

        // --- Advanced matching helpers ---
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
            const thankYou = document.querySelector('.thankyou-container');
            const formContexts = [
                document.querySelector('#order-form'),
                document.querySelector('#buynow-order'),
                document.querySelector('#landing-order-form')
            ].filter(Boolean);
            const nameInput = getFirstFieldValue(['#name', 'input[name="name"]'], formContexts) ||
                thankYou?.dataset.orderName ||
                getStored('tdl_name') ||
                '';
            const phoneInput = getFirstFieldValue(['#phone', 'input[name="phone"]'], formContexts) ||
                thankYou?.dataset.orderPhone ||
                getStored('tdl_phone') ||
                '';
            const emailInput = getFirstFieldValue(['#email', 'input[name="email"]'], formContexts) ||
                thankYou?.dataset.orderEmail ||
                getStored('tdl_email') ||
                '';
            const addressInput = getFirstFieldValue(
                ['#address', 'textarea[name="address"]', 'input[name="address"]'],
                formContexts
            ) ||
                thankYou?.dataset.orderAddress ||
                getStored('tdl_address') ||
                '';
            const cityInput = getFirstFieldValue(
                ['#city', 'select[name="city"]', 'input[name="city"]'],
                formContexts
            ) ||
                thankYou?.dataset.orderCity ||
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

        // Initialize with a basic pageview event (includes fb ids/user data for advanced matching)
        (function() {
            const fbIds = getFacebookIds();
            const userData = collectUserData();
            const eventId = generateEventId();
            dataLayer.push({
                'event': 'page_view',
                'eventId': eventId,
                'event_id_variable': eventId,
                'page': {
                    'title': document.title,
                    'location': window.location.href,
                    'path': window.location.pathname
                },
                'user_data': userData,
                'fbp': fbIds.fbp,
                'fbc': fbIds.fbc,
                'client_id': getClientId(),
                'session_id': getSessionId()
            });
        })();

        document.addEventListener('DOMContentLoaded', function() {
            attachInputPersistence();

            // Track Product Clicks - GA4 schema compliant
            document.querySelectorAll('.product-card, .category-product').forEach(function(productLink) {
                productLink.addEventListener('click', function(e) {
                    let productTitle = this.querySelector('.product-title') ?
                        this.querySelector('.product-title').textContent.trim() : 'Unknown';
                    let productPrice = this.querySelector('.current-price, .price') ?
                        this.querySelector('.current-price, .price').textContent.trim().replace(
                            /[^0-9.]/g, '') :
                        '0';
                    let productUrl = this.getAttribute('href') || '';
                    let productId = this.getAttribute('data-product-id') || '';
                    let categoryName = this.getAttribute('data-category') || '';
                    let index = Array.from(this.parentNode.children).indexOf(this) + 1;
                    const fbIds = getFacebookIds();
                    const userData = collectUserData();
                    const eventId = generateEventId();

                    dataLayer.push({
                        'event': 'select_item',
                        'eventId': eventId,
                        'event_id_variable': eventId,
                        'ecommerce': {
                            'item_list_id': window.location.pathname,
                            'item_list_name': document.title,
                            'items': [{
                                'item_id': productId,
                                'item_name': productTitle,
                                'price': parseFloat(productPrice),
                                'item_category': categoryName,
                                'index': index,
                                'quantity': 1
                            }]
                        },
                        'user_data': userData,
                        'fbp': fbIds.fbp,
                        'fbc': fbIds.fbc,
                        'timestamp': new Date().toISOString()
                    });
                });
            });

            // Track Product View Events
            if (window.location.pathname.includes('/product')) {
                // Get product container
                let productContainer = document.querySelector('.pcontainer');
                if (productContainer) {
                    // Extract product information
                    let productId = productContainer.getAttribute('data-product-id') || '';
                    let productName = productContainer.querySelector('.product-title') ?
                        productContainer.querySelector('.product-title').textContent.trim() : 'Unknown';
                    let categoryName = productContainer.getAttribute('data-category') || '';

                    // Check if this is a combo offer product
                    let comboOffer = document.querySelector('.combo-offer');
                    let productPrice = 0;
                    let variationName = '';

                    if (comboOffer) {
                        // This is a combo offer, use combo pricing
                        const comboId = comboOffer.dataset.comboId;
                        const comboData = window.comboOfferData ? window.comboOfferData[comboId] : null;
                        if (comboData) {
                            productPrice = parseFloat(comboData.offer_price || comboData.regular_price || comboData
                                .price) || 0;
                        }

                        // Get combo selections for variation name
                        let comboSelections = document.querySelectorAll('.combo-selection-item');
                        if (comboSelections.length > 0) {
                            variationName = Array.from(comboSelections).map(item => {
                                let variationText = item.querySelector('.variation-name');
                                return variationText ? variationText.textContent.trim() : '';
                            }).filter(v => v).join(', ');
                        }
                    } else {
                        // Regular product pricing
                        let rawPrice = productContainer.querySelector('#updateOfferPrice') ?
                            productContainer.querySelector('#updateOfferPrice').textContent.trim() : '0';

                        // Normalize price
                        let prices = rawPrice.match(/[\d.]+/g); // Extract all numbers
                        if (prices && prices.length > 0) {
                            productPrice = Math.min(...prices.map(p => parseFloat(p))); // Use minimum price
                        }

                        // Get variation information for regular products
                        let selectedVariations = document.querySelectorAll(
                            'input[name^="variation_option_id_"]:checked');
                        if (selectedVariations.length > 0) {
                            variationName = Array.from(selectedVariations).map(v => v.value).join(', ');
                        } else {
                            // Check for button-based variation selection
                            let selectedVariationButtons = document.querySelectorAll(
                                '.variation-option-btn.selected');
                            if (selectedVariationButtons.length > 0) {
                                variationName = Array.from(selectedVariationButtons).map(btn => btn.textContent
                                    .trim()).join(', ');
                            }
                        }
                    }

                    const fbIds = getFacebookIds();
                    const userData = collectUserData();
                    const eventId = generateEventId();

                    // Push view_item event to dataLayer
                    dataLayer.push({
                        'event': 'view_item',
                        'eventId': eventId,
                        'event_id_variable': eventId,
                        'ecommerce': {
                            'currency': 'BDT',
                            'value': productPrice,
                            'items': [{
                                'item_id': productId,
                                'item_name': productName,
                                'price': productPrice,
                                'item_category': categoryName,
                                'item_variant': variationName,
                                'quantity': 1
                            }]
                        },
                        'fb': {
                            'content_name': productName,
                            'content_category': categoryName,
                            'content_ids': [productId],
                            'content_type': 'product'
                        },
                        'user_data': userData,
                        'fbp': fbIds.fbp,
                        'fbc': fbIds.fbc,
                        'timestamp': new Date().toISOString()
                    });
                }
            }

            // Track Search Interactions - GA4 schema compliant
            const searchInput = document.getElementById('search-input');
            if (searchInput) {
                // Track search on Enter key press
                searchInput.addEventListener('keyup', function(e) {
                    if (e.key === 'Enter' && this.value.trim().length > 0) {
                        const eventId = generateEventId();
                        dataLayer.push({
                            'event': 'search',
                            'eventId': eventId,
                            'event_id_variable': eventId,
                            'search_term': this.value.trim(),
                            'timestamp': new Date().toISOString()
                        });
                    }
                });

                // Track search when clicking outside the search box
                searchInput.addEventListener('blur', function() {
                    if (this.value.trim().length > 0) {
                        const eventId = generateEventId();
                        dataLayer.push({
                            'event': 'search',
                            'eventId': eventId,
                            'event_id_variable': eventId,
                            'search_term': this.value.trim(),
                            'timestamp': new Date().toISOString()
                        });
                    }
                });
            }

            // Track Add to Cart Events
            document.querySelectorAll('.single-cart-btn').forEach(function(button) {
                button.addEventListener('click', function(e) {
                    // Get product container (parent element that contains product info)
                    let productContainer = this.closest('.product-card') || this.closest(
                        '.pcontainer');
                    if (!productContainer) return;

                    // Extract product information
                    let productId = productContainer.getAttribute('data-product-id') || '';
                    let productName = productContainer.querySelector('.product-title') ?
                        productContainer.querySelector('.product-title').textContent.trim() :
                        'Unknown';
                    let categoryName = productContainer.getAttribute('data-category') || '';
                    let quantity = productContainer.getAttribute('#sharedQuantity') || 1;

                    // Check if there's a quantity input
                    let quantityInput = document.querySelector('#sharedQuantity');
                    if (quantityInput) {
                        quantity = parseInt(quantityInput.value) || 1;
                    }

                    // Check if this is a combo offer
                    let comboOffer = document.querySelector('.combo-offer');
                    let productPrice = 0;
                    let variationName = '';

                    if (comboOffer) {
                        // This is a combo offer, use combo pricing
                        const comboId = comboOffer.dataset.comboId;
                        const comboData = window.comboOfferData ? window.comboOfferData[comboId] :
                            null;
                        if (comboData) {
                            productPrice = parseFloat(comboData.offer_price || comboData
                                .regular_price || comboData.price) || 0;
                        }

                        // Get combo selections for variation name
                        let comboSelections = document.querySelectorAll('.combo-selection-item');
                        if (comboSelections.length > 0) {
                            variationName = Array.from(comboSelections).map(item => {
                                let variationText = item.querySelector('.variation-name');
                                return variationText ? variationText.textContent.trim() :
                                    '';
                            }).filter(v => v).join(', ');
                        }
                    } else {
                        // Regular product pricing
                        productPrice = productContainer.querySelector('#updateOfferPrice') ?
                            productContainer.querySelector('#updateOfferPrice').textContent.trim()
                            .replace(/[^0-9.]/g, '') : '0';

                        // Get variation information for regular products
                        let selectedVariations = document.querySelectorAll(
                            'input[name^="variation_option_id_"]:checked');
                        if (selectedVariations.length > 0) {
                            variationName = Array.from(selectedVariations).map(v => v.value).join(
                                ', ');
                        } else {
                            // Check for button-based variation selection
                            let selectedVariationButtons = document.querySelectorAll(
                                '.variation-option-btn.selected');
                            if (selectedVariationButtons.length > 0) {
                                variationName = Array.from(selectedVariationButtons).map(btn => btn
                                    .textContent.trim()).join(', ');
                            }
                        }
                    }

                    const fbIds = getFacebookIds();
                    const userData = collectUserData();
                    const eventId = generateEventId();

                    dataLayer.push({
                        'event': 'add_to_cart',
                        'eventId': eventId,
                        'event_id_variable': eventId,
                        'ecommerce': {
                            'currency': 'BDT',
                            'value': parseFloat(productPrice) * quantity,
                            'items': [{
                                'item_id': productId,
                                'item_name': productName,
                                'price': parseFloat(productPrice),
                                'item_category': categoryName,
                                'item_variant': variationName,
                                'quantity': quantity
                            }]
                        },
                        'fb': {
                            'content_name': productName,
                            'content_category': categoryName,
                            'content_ids': [productId],
                            'content_type': 'product'
                        },
                        'user_data': userData,
                        'fbp': fbIds.fbp,
                        'fbc': fbIds.fbc,
                        'button_type': this.classList.contains('buy-now-btn') ? 'buy_now' :
                            'add_to_cart',
                        'timestamp': new Date().toISOString()
                    });
                });
            });

            // View Cart Page Events
            if (window.location.pathname.includes('/cart')) {
                // Check if cart items exist
                if (document.querySelectorAll('.cart-item').length > 0) {
                    // Initialize items array and total value
                    let cartItems = [];
                    let cartTotal = 0;

                    // Loop through each cart item (both regular and combo items)
                    document.querySelectorAll('.cart-item, .cart-item.combo-item').forEach(function(item) {
                        // Extract product information
                        let productId = item.closest('tr').getAttribute('data-product-id') ||
                            item.closest('tr').getAttribute('data-combo-id') || '';
                        let productName = item.querySelector('.product-link') ?
                            item.querySelector('.product-link').textContent.trim() :
                            item.querySelector('.combo-title strong') ?
                            item.querySelector('.combo-title strong').textContent.trim() : 'Unknown';
                        let productPrice = item.querySelector('.price-text') ?
                            parseFloat(item.querySelector('.price-text').textContent.replace(/[^0-9.]/g,
                                '')) : 0;
                        let quantity = parseInt(item.querySelector('.cart-quantity').textContent.trim()) ||
                            1;
                        let itemTotal = parseFloat(item.querySelector('.total-price').textContent.replace(
                            /[^0-9.]/g, '')) || 0;

                        // Get category if available
                        let categoryName = '';

                        // Get variation information if available
                        let variationName = '';

                        // Check if this is a combo item
                        if (item.classList.contains('combo-item')) {
                            // Get combo selections for variation name
                            let comboSelections = item.querySelectorAll('.combo-selection-item');
                            if (comboSelections.length > 0) {
                                variationName = Array.from(comboSelections).map(selection => {
                                    let productName = selection.querySelector('.product-name');
                                    let variationText = selection.querySelector('.variation-name');
                                    let text = productName ? productName.textContent.trim() : '';
                                    if (variationText) {
                                        text += ' (' + variationText.textContent.trim() + ')';
                                    }
                                    return text;
                                }).filter(v => v).join(', ');
                            }
                        } else {
                            // Regular item variation detection
                            let variationItems = item.querySelectorAll('.variation-item');
                            if (variationItems.length > 0) {
                                // If there are variations, use the first one for the main item
                                let variationOption = variationItems[0].querySelector('.variation-option');
                                if (variationOption) {
                                    variationName = variationOption.textContent.trim();
                                }
                            }
                        }

                        // Note: productPrice contains the unit price, which is correct for GA4
                        // Add to cart items array
                        cartItems.push({
                            'item_id': productId,
                            'item_name': productName,
                            'price': productPrice, // This is already unit price
                            'item_category': categoryName,
                            'item_variant': variationName,
                            'quantity': quantity
                        });

                        // Add to cart total
                        cartTotal += itemTotal;
                    });

                    const fbIds = getFacebookIds();
                    const userData = collectUserData();
                    const eventId = generateEventId();

                    // Push view_cart event to dataLayer
                    dataLayer.push({
                        'event': 'view_cart',
                        'eventId': eventId,
                        'event_id_variable': eventId,
                        'ecommerce': {
                            'currency': 'BDT',
                            'value': cartTotal,
                            'items': cartItems
                        },
                        'user_data': userData,
                        'fbp': fbIds.fbp,
                        'fbc': fbIds.fbc,
                        'timestamp': new Date().toISOString()
                    });
                }
            }

            // Add event listeners to all remove buttons in the cart
            document.querySelectorAll('.remove-btn').forEach(function(removeBtn) {
                removeBtn.addEventListener('click', function(e) {
                    // Don't prevent default - let the normal removal process happen

                    // Find the parent cart item row
                    const cartItem = this.closest('.cart-item');
                    if (!cartItem) return;

                    // Extract product information before it's removed
                    const productId = cartItem.getAttribute('data-product-id') ||
                        cartItem.getAttribute('data-combo-id') || '';
                    const productName = cartItem.querySelector('.product-link')
                        ?.textContent.trim() ||
                        cartItem.querySelector('.combo-title strong')
                        ?.textContent.trim() || 'Unknown';
                    const productPrice = cartItem.querySelector('.price-text')
                        ?.textContent.trim().replace(/[^0-9.]/g, '') || '0';
                    const quantity = parseInt(cartItem.querySelector('.cart-quantity')
                        ?.textContent.trim()) || 1;
                    const totalPrice = parseFloat(cartItem.querySelector('.total-price')
                        ?.textContent.trim().replace(/[^0-9.]/g, '')) || 0;

                    // Get variation information if available
                    let variationName = '';

                    // Check if this is a combo item
                    if (cartItem.classList.contains('combo-item')) {
                        // Get combo selections for variation name
                        const comboSelections = cartItem.querySelectorAll('.combo-selection-item');
                        if (comboSelections.length > 0) {
                            variationName = Array.from(comboSelections).map(selection => {
                                const productName = selection.querySelector(
                                    '.product-name');
                                const variationText = selection.querySelector(
                                    '.variation-name');
                                let text = productName ? productName.textContent.trim() :
                                    '';
                                if (variationText) {
                                    text += ' (' + variationText.textContent.trim() + ')';
                                }
                                return text;
                            }).filter(v => v).join(', ');
                        }
                    } else {
                        // Regular item variation detection
                        const variationItems = cartItem.querySelectorAll('.variation-item');
                        if (variationItems.length > 0) {
                            // If there are variations, use the first one for the main item
                            const variationOption = variationItems[0].querySelector(
                                '.variation-option');
                            if (variationOption) {
                                variationName = variationOption.textContent.trim();
                            }
                        }
                    }

                    // Get category if available
                    const categoryName = cartItem.getAttribute('data-category') || '';

                    // Note: productPrice contains the unit price, which is correct for GA4
                    // Push remove_from_cart event to dataLayer
                    const fbIds = getFacebookIds();
                    const userData = collectUserData();
                    const eventId = generateEventId();
                    dataLayer.push({
                        'event': 'remove_from_cart',
                        'eventId': eventId,
                        'event_id_variable': eventId,
                        'ecommerce': {
                            'currency': 'BDT',
                            'value': totalPrice,
                            'items': [{
                                'item_id': productId,
                                'item_name': productName,
                                'price': parseFloat(productPrice), // This is already unit price
                                'item_category': categoryName,
                                'item_variant': variationName,
                                'quantity': quantity
                            }]
                        },
                        'user_data': userData,
                        'fbp': fbIds.fbp,
                        'fbc': fbIds.fbc,
                        'timestamp': new Date().toISOString()
                    });
                });
            });

            // Track Checkout Steps
            if (window.location.pathname.includes('/checkout') || window.location.pathname.includes('/buy/store')) {
                // Collect all items first
                let checkoutItems = [];

                // Populate items array from cart items
                document.querySelectorAll('.cart-item, .checkout-product, .cct-table tr:not(.cct-table-head)')
                    .forEach(function(item, index) {
                        // Skip non-product rows (subtotal, shipping, total)
                        if (!item.querySelector('.cct-title') ||
                            item.querySelector('.cct-title')?.textContent.trim().includes('Subtotal') ||
                            item.querySelector('.cct-shipping-head') ||
                            item.classList.contains('cart-subtotal') ||
                            item.classList.contains('cart-shipping') ||
                            item.classList.contains('cart-total')) {
                            return;
                        }

                        let productId = item.getAttribute('data-product-id') ||
                            item.getAttribute('data-combo-id') || '';
                        let productName = item.querySelector('.cct-title a')?.textContent.trim() ||
                            item.querySelector('.product-name')?.textContent.trim() ||
                            item.querySelector('.cct-title')?.textContent.trim() || 'Unknown';
                        // Note: cct-price contains the total price (price × quantity) from backend
                        let productPrice = item.querySelector('.cct-price')?.textContent.trim().replace(
                                /[^0-9.]/g, '') ||
                            item.querySelector('.product-price')?.textContent.trim().replace(/[^0-9.]/g, '') ||
                            '0';

                        // Extract quantity from product name if it contains 'X' (like in buynow page)
                        let quantity = 1;
                        if (productName.includes(' X ')) {
                            const parts = productName.split(' X ');
                            productName = parts[0].trim();
                            quantity = parseInt(parts[1].trim()) || 1;
                        } else {
                            quantity = parseInt(item.querySelector('.quantity')?.textContent.trim() ||
                                item.querySelector('.cct-quantity')?.textContent.trim() || '1');
                        }

                        let categoryName = item.getAttribute('data-category') || '';
                        let variationName = '';

                        // Check if this is a combo item
                        if (item.querySelector('.combo-selections')) {
                            // This is a combo item, extract combo information
                            const comboSelections = item.querySelectorAll('.combo-selection-item');
                            if (comboSelections.length > 0) {
                                variationName = Array.from(comboSelections).map(selection => {
                                    const productName = selection.querySelector('.product-name');
                                    const variationText = selection.querySelector('.variation-name');
                                    let text = productName ? productName.textContent.trim() : '';
                                    if (variationText) {
                                        text += ' (' + variationText.textContent.trim() + ')';
                                    }
                                    return text;
                                }).filter(v => v).join(', ');
                            }
                        } else {
                            // Regular item variation detection
                            // Get variation from product-variations div
                            const variationsDiv = item.querySelector('.product-variations');
                            if (variationsDiv) {
                                const variationText = variationsDiv.textContent.trim();
                                if (variationText) {
                                    variationName = variationText;
                                }
                            } else {
                                variationName = item.querySelector('.variation-name')?.textContent.trim() || '';
                            }
                        }

                        // Note: productPrice contains the total price (price × quantity) from backend
                        // We need to calculate the unit price for GA4 compliance
                        const unitPrice = parseFloat(productPrice) / quantity;
                        
                        checkoutItems.push({
                            'item_id': productId,
                            'item_name': productName,
                            'price': unitPrice, // Unit price for GA4 compliance
                            'item_category': categoryName,
                            'item_variant': variationName,
                            'quantity': quantity,
                            'index': index + 1
                        });
                });

                // Push begin_checkout event with all items
                const fbIds = getFacebookIds();
                const userData = collectUserData();
                const eventId = generateEventId();
                dataLayer.push({
                    'event': 'begin_checkout',
                    'eventId': eventId,
                    'event_id_variable': eventId,
                    'ecommerce': {
                        'currency': 'BDT',
                        'value': parseFloat(document.querySelector('.order-total, .cart-total-amount')
                            ?.textContent.trim()
                            .replace(/[^0-9.]/g, '') || '0'),
                        'coupon': document.querySelector('input[name="coupon_code"]')?.value || '',
                        'items': checkoutItems
                        },
                        'user_data': userData,
                        'fbp': fbIds.fbp,
                        'fbc': fbIds.fbc,
                        'timestamp': new Date().toISOString()
                });

                // Track shipping method selection
                document.querySelectorAll('input[name="shipping_method"]').forEach(function(radio) {
                    radio.addEventListener('change', function() {
                        const fbIdsChange = getFacebookIds();
                        const userDataChange = collectUserData();
                        const eventId = generateEventId();
                        dataLayer.push({
                            'event': 'add_shipping_info',
                            'eventId': eventId,
                            'event_id_variable': eventId,
                            'ecommerce': {
                                'shipping_tier': this.value,
                                'currency': 'BDT',
                                'value': parseFloat(document.querySelector(
                                        '.cart-total-amount, .order-total')?.textContent
                                    .trim()
                                    .replace(/[^0-9.]/g, '') || '0'),
                                'items': checkoutItems
                            },
                            'user_data': userDataChange,
                            'fbp': fbIdsChange.fbp,
                            'fbc': fbIdsChange.fbc,
                            'timestamp': new Date().toISOString()
                        });
                    });
                });

                // Track payment method selection
                document.querySelectorAll('input[name="payment_method"]').forEach(function(radio) {
                    radio.addEventListener('change', function() {
                        const fbIdsChange = getFacebookIds();
                        const userDataChange = collectUserData();
                        const eventId = generateEventId();
                        dataLayer.push({
                            'event': 'add_payment_info',
                            'eventId': eventId,
                            'event_id_variable': eventId,
                            'ecommerce': {
                                'payment_type': this.value,
                                'currency': 'BDT',
                                'value': parseFloat(document.querySelector(
                                        '.cart-total-amount, .order-total')?.textContent
                                    .trim()
                                    .replace(/[^0-9.]/g, '') || '0'),
                                'items': checkoutItems
                            },
                            'user_data': userDataChange,
                            'fbp': fbIdsChange.fbp,
                            'fbc': fbIdsChange.fbc,
                            'timestamp': new Date().toISOString()
                        });
                    });
                });

                // Track form submission (purchase)
                const checkoutForm = document.querySelector('#order-form');
                if (checkoutForm) {
                    checkoutForm.addEventListener('submit', function() {
                        const fbIdsSubmit = getFacebookIds();
                        const userDataSubmit = collectUserData();
                        const eventId = generateEventId();

                        // Store the event ID for use on thank you page
                        sessionStorage.setItem('purchase_event_id', eventId);

                        console.log('FORM SUBMIT - Event ID stored:', eventId, 'timestamp:', Date.now());

                        dataLayer.push({
                            'event': 'submit_purchase',
                            'eventId': eventId,
                            'event_id_variable': eventId,
                            'ecommerce': {
                                'transaction_id': 'PENDING-' + Date.now(),
                                'value': parseFloat(document.querySelector(
                                        '.cart-total-amount, .order-total')
                                    ?.textContent.trim().replace(/[^0-9.]/g, '') || '0'),
                                'tax': parseFloat(document.querySelector('.tax-amount')?.textContent
                                    .trim().replace(/[^0-9.]/g, '') || '0'),
                                'shipping': parseFloat(document.querySelector('.shipping-amount')
                                    ?.textContent.trim().replace(/[^0-9.]/g, '') || '0'),
                                'currency': 'BDT',
                                'items': checkoutItems
                            },
                            'user_data': userDataSubmit,
                            'fbp': fbIdsSubmit.fbp,
                            'fbc': fbIdsSubmit.fbc,
                            'timestamp': new Date().toISOString()
                        });
                    });
                }

            }

            // Thank you page - Purchase events are handled by blade template (thankyou.blade.php)
            // The blade template fires purchase event when $firePurchaseEvent is true,
            // or stores it as pending for admin when delayed events are enabled.
            // We skip here to prevent duplicate events.
            if (window.location.pathname.includes('/thank-you')) {
                sessionStorage.removeItem('purchase_event_id');
                console.log('[Analytics] Purchase event handled by server-side blade template');
            }
        });
