/**
 * Fraud Protection System - Frontend Validation
 * Works with: checkout.blade.php, buynow.blade.php, show.blade.php (landing pages)
 */

class FraudProtection {
    constructor(settings = {}) {
        this.settings = settings;
        this.init();
    }

    init() {
        if (!this.settings.enabled) {
            return;
        }

        // Attach validation to all order forms
        this.attachFormValidation();
    }

    attachFormValidation() {
        const forms = [
            document.getElementById('order-form'),         // checkout.blade.php
            document.getElementById('buynow-order'),       // buynow.blade.php
            document.getElementById('landing-order-form')  // show.blade.php (landing)
        ];

        forms.forEach(form => {
            if (form) {
                form.addEventListener('submit', (e) => this.validateForm(e, form));
            }
        });

        // Real-time validation on input
        this.attachRealTimeValidation();
    }

    validateForm(event, form) {
        const errors = [];
        
        // Get form data
        const phoneInput = form.querySelector('input[name="phone"]');
        const nameInput = form.querySelector('input[name="name"]');
        const addressInput = form.querySelector('input[name="address"]');
        
        if (!phoneInput || !nameInput) {
            return true; // Allow if inputs not found
        }

        const phone = phoneInput.value.trim();
        const name = nameInput.value.trim();
        const address = addressInput ? addressInput.value.trim() : '';

        // Clear previous errors
        this.clearErrors(form);

        // Phone validation
        if (this.settings.phone_validation?.enabled) {
            const phoneError = this.validatePhone(phone);
            if (phoneError) {
                errors.push(phoneError);
                this.showFieldError(phoneInput, phoneError);
            }
        }

        // Block sequential numbers
        if (this.settings.block_sequential) {
            if (this.hasSequentialDigits(phone)) {
                const error = 'অবৈধ ফোন নম্বর সনাক্ত করা হয়েছে। অনুগ্রহ করে সঠিক নম্বর দিন।';
                errors.push(error);
                this.showFieldError(phoneInput, error);
            }
        }

        // Name validation
        if (this.settings.name_validation?.enabled) {
            const nameError = this.validateName(name);
            if (nameError) {
                errors.push(nameError);
                this.showFieldError(nameInput, nameError);
            }
        }

        // Block repeated names
        if (this.settings.block_repeated_names) {
            if (this.hasRepeatedCharacters(name)) {
                const error = 'অবৈধ নাম সনাক্ত করা হয়েছে। অনুগ্রহ করে সঠিক নাম দিন।';
                errors.push(error);
                this.showFieldError(nameInput, error);
            }
        }

        // Block gibberish names
        if (this.settings.block_gibberish) {
            if (this.isGibberishName(name)) {
                const error = 'অনুগ্রহ করে আপনার প্রকৃত নাম প্রদান করুন।';
                errors.push(error);
                this.showFieldError(nameInput, error);
            }
        }

        // Address validation
        if (this.settings.address_validation?.enabled && addressInput) {
            const min = this.settings.address_validation?.min_length || 10;
            if ((address || '').trim().length < min) {
                const error = `ঠিকানাটি কমপক্ষে ${min} অক্ষরের হতে হবে।`;
                errors.push(error);
                this.showFieldError(addressInput, error);
            }
        }

        // If there are errors, prevent submission
        if (errors.length > 0) {
            event.preventDefault();
            // Ensure jQuery submit handlers don't also run (prevents duplicate server popup)
            if (typeof event.stopImmediatePropagation === 'function') {
                event.stopImmediatePropagation();
            }
            if (typeof event.stopPropagation === 'function') {
                event.stopPropagation();
            }
            // Show all unique errors in one popup (normalize whitespace to avoid duplicates)
            const uniqueMap = new Map();
            errors.filter(Boolean).forEach(msg => {
                const key = String(msg).replace(/\s+/g, ' ').trim();
                if (!uniqueMap.has(key)) uniqueMap.set(key, msg);
            });
            const uniqueErrors = Array.from(uniqueMap.values());
            this.showNotification(uniqueErrors, 'error');
            
            // Scroll to first error
            const firstError = form.querySelector('.fraud-error');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            
            return false;
        }

        return true;
    }

    validatePhone(phone) {
        // Clean phone number: remove all non-digit characters except + at the beginning
        const cleanPhone = this.cleanPhoneNumber(phone);
        
        // Check phone whitelist first if enabled
        if (this.settings.phone_validation?.whitelist_enabled) {
            const whitelistCheck = this.validatePhoneWhitelist(phone);
            if (whitelistCheck) {
                return whitelistCheck;
            }
        }
        
        // Always enforce allowed length rules (whitelist is an additional restriction)
        const allowedLengths = this.settings.phone_validation?.allowed_lengths || [11, 12, 14];

        if (!allowedLengths.includes(cleanPhone.length)) {
            return `ফোন নম্বর ${allowedLengths.join(', ')} ডিজিটের হতে হবে।`;
        }

        return null;
    }

    cleanPhoneNumber(phone) {
        if (!phone) return '';
        
        // Convert Bengali digits to English digits first
        let cleaned = this.convertBengaliToEnglish(phone.toString().trim());
        
        // Remove all non-digit characters except + at the beginning
        // This handles: spaces, hyphens, dots, parentheses, etc.
        const hasPlus = cleaned.startsWith('+');
        if (hasPlus) {
            cleaned = '+' + cleaned.substring(1).replace(/\D/g, '');
        } else {
            cleaned = cleaned.replace(/\D/g, '');
        }
        
        return cleaned;
    }

    convertBengaliToEnglish(text) {
        if (!text) return '';
        
        // Bengali to English digit mapping
        const bengaliToEnglish = {
            '০': '0', '১': '1', '২': '2', '৩': '3', '৪': '4',
            '৫': '5', '৬': '6', '৭': '7', '৮': '8', '৯': '9'
        };
        
        return text.replace(/[০-৯]/g, (match) => bengaliToEnglish[match] || match);
    }

    validatePhoneWhitelist(phone) {
        const cleanPhone = this.cleanPhoneNumber(phone);
        
        // Define allowed patterns
        const allowedPatterns = [
            '^014',      // 014xxxxxxxxx
            '^018',      // 018xxxxxxxxx
            '^88014',    // 88014xxxxxxxxx
            '^88018',    // 88018xxxxxxxxx
            '^013',      // 013xxxxxxxxx
            '^017',      // 017xxxxxxxxx
            '^88013',    // 88013xxxxxxxxx
            '^88017',    // 88017xxxxxxxxx
            '^019',      // 019xxxxxxxxx
            '^88019',    // 88019xxxxxxxxx
            '^015',      // 015xxxxxxxxx
            '^88015',    // 88015xxxxxxxxx
            '^016',      // 016xxxxxxxxx
            '^88016',    // 88016xxxxxxxxx
            '^011',      // 011xxxxxxxxx
            '^88011',    // 88011xxxxxxxxx
        ];
        
        // Check if phone matches any allowed pattern
        for (const pattern of allowedPatterns) {
            if (new RegExp(pattern).test(cleanPhone)) {
                return null; // Valid pattern
            }
        }
        
        // Also check for +880 patterns (with + sign)
        if (cleanPhone.startsWith('+880')) {
            const plus880Patterns = [
                '^\\+88014',  // +88014xxxxxxxxx
                '^\\+88018',  // +88018xxxxxxxxx
                '^\\+88013',  // +88013xxxxxxxxx
                '^\\+88017',  // +88017xxxxxxxxx
                '^\\+88019',  // +88019xxxxxxxxx
                '^\\+88015',  // +88015xxxxxxxxx
                '^\\+88016',  // +88016xxxxxxxxx
                '^\\+88011',  // +88011xxxxxxxxx
            ];
            
            for (const pattern of plus880Patterns) {
                if (new RegExp(pattern).test(cleanPhone)) {
                    return null; // Valid pattern
                }
            }
        }
        
        // If no pattern matches, phone is not allowed
        return 'এই ফোন নম্বরটি গ্রহণযোগ্য নয়। শুধুমাত্র নির্দিষ্ট অপারেটরের নম্বর গ্রহণ করা হয়।';
    }

    validateName(name) {
        const min = this.settings.name_validation?.min_length || 2;
        const max = this.settings.name_validation?.max_length || 100;
        const disallowNumeric = this.settings.name_validation?.disallow_numeric !== false;
        const len = (name || '').trim().length;
        if (len < min || len > max) {
            return `নামটি ${min}-${max} অক্ষরের মধ্যে হতে হবে।`;
        }
        if (disallowNumeric && /\d/.test(name)) {
            return 'নামে সংখ্যা ব্যবহার করা যাবে না।';
        }
        return null;
    }

    hasSequentialDigits(phone) {
        const cleanPhone = this.cleanPhoneNumber(phone);

        // Check for all same digits (0000000000, 1111111111)
        if (/^(\d)\1+$/.test(cleanPhone)) {
            return true;
        }

        // Check for 6+ consecutive same digits
        if (/(\d)\1{5,}/.test(cleanPhone)) {
            return true;
        }

        return false;
    }

    hasRepeatedCharacters(name) {
        const cleanName = name.toLowerCase().trim();

        // Single character repeated
        if (/^(.)\1+$/.test(cleanName)) {
            return true;
        }

        // 5+ consecutive same characters
        if (/(.)\1{4,}/.test(cleanName)) {
            return true;
        }

        return false;
    }

    isGibberishName(name) {
        const cleanName = name.toLowerCase().trim();

        // Keyboard patterns
        const keyboardPatterns = ['asdf', 'qwer', 'zxcv', 'hjkl', '1234', 'abcd', 'asdfgh', 'qwerty'];
        for (const pattern of keyboardPatterns) {
            if (cleanName.includes(pattern) && cleanName.length < 10) {
                return true;
            }
        }

        // Skip vowel check if name contains Bangla/Unicode characters
        // Bangla Unicode range: \u0980-\u09FF
        // Also check for other non-ASCII characters
        if (/[\u0980-\u09FF]/.test(cleanName) || /[^\x00-\x7F]/.test(cleanName)) {
            return false; // Allow Bangla and other Unicode names
        }

        // Check for lack of vowels (only for English/ASCII names)
        const vowelCount = (cleanName.match(/[aeiou]/gi) || []).length;
        if (vowelCount === 0 && cleanName.length > 3) {
            return true;
        }

        return false;
    }

    attachRealTimeValidation() {
        const forms = [
            document.getElementById('order-form'),
            document.getElementById('buynow-order'),
            document.getElementById('landing-order-form')
        ];

        forms.forEach(form => {
            if (!form) return;

            const phoneInput = form.querySelector('input[name="phone"]');
            const nameInput = form.querySelector('input[name="name"]');

            if (phoneInput) {
                phoneInput.addEventListener('blur', () => {
                    this.validateFieldRealTime(phoneInput, 'phone');
                });
                
                // Remove error on input
                phoneInput.addEventListener('input', () => {
                    this.clearFieldError(phoneInput);
                });
            }

            if (nameInput) {
                nameInput.addEventListener('blur', () => {
                    this.validateFieldRealTime(nameInput, 'name');
                });
                
                // Remove error on input
                nameInput.addEventListener('input', () => {
                    this.clearFieldError(nameInput);
                });
            }
        });
    }

    validateFieldRealTime(input, type) {
        const value = input.value.trim();
        let error = null;

        if (type === 'phone') {
            if (this.settings.phone_validation?.enabled) {
                error = this.validatePhone(value);
            }
            if (!error && this.settings.block_sequential) {
                if (this.hasSequentialDigits(value)) {
                    error = 'অবৈধ ফোন নম্বর সনাক্ত করা হয়েছে।';
                }
            }
        } else if (type === 'name') {
            if (this.settings.block_repeated_names && this.hasRepeatedCharacters(value)) {
                error = 'অবৈধ নাম সনাক্ত করা হয়েছে।';
            }
            if (!error && this.settings.block_gibberish && this.isGibberishName(value)) {
                error = 'অনুগ্রহ করে আপনার প্রকৃত নাম দিন।';
            }
        }

        if (error) {
            this.showFieldError(input, error);
        } else {
            this.clearFieldError(input);
        }
    }

    showFieldError(input, message) {
        // Add error class to input
        input.classList.add('fraud-error');
        input.style.borderColor = '#dc3545';

        // Remove existing error message
        const existingError = input.parentElement.querySelector('.fraud-error-message');
        if (existingError) {
            existingError.remove();
        }

        // Add error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'fraud-error-message';
        errorDiv.style.color = '#dc3545';
        errorDiv.style.fontSize = '13px';
        errorDiv.style.marginTop = '5px';
        errorDiv.textContent = message;
        input.parentElement.appendChild(errorDiv);
    }

    clearFieldError(input) {
        input.classList.remove('fraud-error');
        input.style.borderColor = '';

        const errorMsg = input.parentElement.querySelector('.fraud-error-message');
        if (errorMsg) {
            errorMsg.remove();
        }
    }

    clearErrors(form) {
        const errorInputs = form.querySelectorAll('.fraud-error');
        errorInputs.forEach(input => {
            input.classList.remove('fraud-error');
            input.style.borderColor = '';
        });

        const errorMessages = form.querySelectorAll('.fraud-error-message');
        errorMessages.forEach(msg => msg.remove());
    }

    showNotification(message, type = 'error') {
        // Check if notification container exists
        let notificationContainer = document.getElementById('fraud-notification');
        
        if (!notificationContainer) {
            notificationContainer = document.createElement('div');
            notificationContainer.id = 'fraud-notification';
            notificationContainer.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                min-width: 300px;
                max-width: 500px;
            `;
            document.body.appendChild(notificationContainer);
        }

        const notification = document.createElement('div');
        notification.className = `fraud-notification-item ${type}`;
        notification.style.cssText = `
            background: ${type === 'error' ? '#dc3545' : '#28a745'};
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            animation: slideInRight 0.3s ease;
        `;
        if (Array.isArray(message)) {
            const html = message.map(m => `<div>• ${String(m)}</div>`).join('');
            notification.innerHTML = html;
        } else if (typeof message === 'string' && message.indexOf('<br') !== -1) {
            notification.innerHTML = message;
        } else {
            notification.textContent = message;
        }

        notificationContainer.appendChild(notification);

        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }
}

// CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    .fraud-error {
        border-color: #dc3545 !important;
    }
`;
document.head.appendChild(style);

// Export for use
window.FraudProtection = FraudProtection;

// Global helper to show push notifications, reusing instance if available
window.pushFraudNotification = function(message, type = 'error') {
    try {
        if (window.fraudProtectionInstance && typeof window.fraudProtectionInstance.showNotification === 'function') {
            window.fraudProtectionInstance.showNotification(message, type);
            return;
        }
        // Fallback: render minimal notification if instance not ready
        let notificationContainer = document.getElementById('fraud-notification');
        if (!notificationContainer) {
            notificationContainer = document.createElement('div');
            notificationContainer.id = 'fraud-notification';
            notificationContainer.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                min-width: 300px;
                max-width: 500px;
            `;
            document.body.appendChild(notificationContainer);
        }

        const notification = document.createElement('div');
        notification.className = `fraud-notification-item ${type}`;
        notification.style.cssText = `
            background: ${type === 'error' ? '#dc3545' : '#28a745'};
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            animation: slideInRight 0.3s ease;
        `;
        notification.textContent = message;
        notificationContainer.appendChild(notification);

        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    } catch (e) {
        // Silent fail
    }
}

// Initialize phone input pre-fetching when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    initializePhonePrefetching();
});

/**
 * Initialize phone input pre-fetching for fraud data
 */
function initializePhonePrefetching() {
    const phoneInputs = document.querySelectorAll('input[name="phone"], input[name="billing_phone"]');
    
    phoneInputs.forEach(input => {
        let timeoutId;
        
        input.addEventListener('input', function() {
            clearTimeout(timeoutId);
            
            // Debounce: wait 1 second after user stops typing
            timeoutId = setTimeout(() => {
                const phone = this.value.trim();
                const cleanPhone = phone.replace(/\D/g, '');
                
                // Check if phone is complete (11, 12, or 14 digits)
                if ([11, 12, 14].includes(cleanPhone.length)) {
                    prefetchFraudData(phone);
                }
            }, 1000);
        });
    });
}

/**
 * Pre-fetch fraud data for a phone number
 */
function prefetchFraudData(phone) {
    // Check if we already have data for this phone
    const cacheKey = 'fraud_data_' + phone.replace(/\D/g, '');
    if (sessionStorage.getItem(cacheKey)) {
        return; // Already fetched
    }
    
    fetch('/api/prefetch-fraud-data', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({ phone: phone })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Cache the data in session storage
            sessionStorage.setItem(cacheKey, JSON.stringify(data.data));
        }
    })
    .catch(error => {
        // Silently fail - validation will continue without pre-fetched data
    });
}
