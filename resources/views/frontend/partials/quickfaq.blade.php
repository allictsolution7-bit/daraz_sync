<section class="faq-container">
    <h2 class="faq-title">FREQUENTLY ASKED QUESTIONS</h2>

    <div class="faq-categories">
        <div class="faq-category" onclick="openPopup('shipping')">
            <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path
                    d="M19.15 8h-1.65V5c0-1.1-.9-2-2-2H3c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h1c0 1.1.9 2 2 2s2-.9 2-2h6c0 1.1.9 2 2 2s2-.9 2-2h2c1.1 0 2-.9 2-2v-5.5L19.15 8zM6 18c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm6-1H8.24c-.24-.4-.52-.76-.88-1.05-.3-.25-.65-.45-1.03-.55-.08-.02-.16-.04-.24-.05H3V5h12v12h-3v-1zm3 1c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm5-3h-1.93c-.13-.28-.3-.53-.5-.75-.27-.3-.6-.53-.97-.7l-.23-.1H18V9h2.12L21 11.5V15z" />
            </svg>
            <h3 class="faq-category-title">Shipping & Delivery</h3>
        </div>

        <div class="faq-category" onclick="openPopup('warranty')">
            <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path
                    d="M12 5V1L7 6l5 5V7c3.31 0 6 2.69 6 6s-2.69 6-6 6-6-2.69-6-6H4c0 4.42 3.58 8 8 8s8-3.58 8-8-3.58-8-8-8z" />
            </svg>
            <h3 class="faq-category-title">Warranty & Returns</h3>
        </div>

        <div class="faq-category" onclick="openPopup('payments')">
            <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path
                    d="M21 18v1c0 1.1-.9 2-2 2H5c-1.11 0-2-.9-2-2V5c0-1.1.89-2 2-2h14c1.1 0 2 .9 2 2v1h-9c-1.11 0-2 .9-2 2v8c0 1.1.89 2 2 2h9zm-9-2h10V8H12v8zm4-2.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z" />
            </svg>
            <h3 class="faq-category-title">Payments</h3>
        </div>
    </div>
</section>

<!-- Popup Overlay -->
<div class="popup-overlay" id="popupOverlay">
    <div class="popup-content">
        <button type="button" class="popup-close" onclick="faqclosePopup()">&times;</button>
        <h3 class="popup-title" id="popupTitle">FAQ Title</h3>
        <div id="popupContent">
            <!-- Dynamic content will be inserted here -->
        </div>
    </div>
</div>

<style>
    .faq-container {
        max-width: 1200px;
        margin: 10px auto;
        padding: 0px 0px;
        text-align: center;
        font-family: 'Poppins';
        padding-top: 20px;
        background: #f5f5f5;
        padding-bottom: 10px;
    }


    .faq-title {
        font-size: 14px;
        font-weight: bold;
        margin-bottom: 10px;
        margin-top: 10px;
    }

    .faq-categories {
        display: flex;
        justify-content: space-around;
        flex-wrap: nowrap;
        gap: 0px;
    }

    .faq-category {
        width: auto;
        padding: 10px;
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .faq-category:hover {
        transform: translateY(-10px);
    }

    .faq-icon {
        width: 30px;
        height: 30px;
        fill: #ED1A25;
        margin-bottom: 0px;
    }

    .faq-category-title {
        font-size: 14px;
        font-weight: bold;
        text-decoration: underline;
    }

    /* Popup styles */
    .popup-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease;
    }

    .popup-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .popup-content {
        background-color: white;
        padding: 30px;
        font-size: 15px;
        font-family: 'Poppins';
        border-radius: 8px;
        max-width: 600px;
        width: 90%;
        max-height: 80vh;
        overflow-y: auto;
        position: relative;
        transform: scale(0.8);
        transition: transform 0.3s ease;
    }

    .popup-overlay.active .popup-content {
        transform: scale(1);
    }

    .popup-close {
        position: absolute;
        top: 15px;
        right: 15px;
        font-size: 24px;
        cursor: pointer;
        background: none;
        border: none;
        color: #333;
    }

    .popup-title {
        font-size: 22px;
        margin-bottom: 20px;
        color: #ED1A25;
        border-bottom: 2px solid #ED1A25;
        padding-bottom: 10px;
    }

    .faq-item {
        margin-bottom: 20px;
        text-align: left;
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
    }

    .faq-question {
        font-weight: bold;
        margin-bottom: 8px;
        font-size: 16px;
    }

    .faq-answer {
        line-height: 1.6;
        color: #555;
    }
</style>

<script>
    // FAQ data
    const faqData = {
        shipping: {
            title: "Shipping & Delivery FAQ",
            questions: [{
                    question: "How long does shipping take?",
                    answer: "Standard shipping typically takes 3-5 business days within the continental US. Express shipping options are available at checkout for 1-2 day delivery."
                },
                {
                    question: "Do you ship internationally?",
                    answer: "Yes, we ship to most countries worldwide. International shipping typically takes 7-14 business days depending on the destination and customs processing."
                },
                {
                    question: "Is free shipping available?",
                    answer: "We offer free standard shipping on all orders over $50 within the United States."
                },
                {
                    question: "How can I track my order?",
                    answer: "Once your order ships, you'll receive a confirmation email with a tracking number and link to monitor your package's progress."
                }
            ]
        },
        warranty: {
            title: "Warranty & Returns FAQ",
            questions: [{
                    question: "What is your return policy?",
                    answer: "We accept returns within 30 days of purchase. Items must be in their original condition with all packaging and tags attached."
                },
                {
                    question: "How do I initiate a return?",
                    answer: "To start a return, log into your account and select the order you wish to return. Follow the prompts to generate a return shipping label."
                },
                {
                    question: "What is covered under warranty?",
                    answer: "Our products come with a 1-year limited warranty that covers manufacturing defects and material failures under normal use conditions."
                },
                {
                    question: "How long does the refund process take?",
                    answer: "Once we receive your return, it typically takes 3-5 business days to process. The refund will appear on your original payment method within 5-10 business days."
                }
            ]
        },
        payments: {
            title: "Payments FAQ",
            questions: [{
                    question: "What payment methods do you accept?",
                    answer: "We accept all major credit cards (Visa, Mastercard, American Express, Discover), PayPal, Apple Pay, and Google Pay."
                },
                {
                    question: "Is it safe to use my credit card on your website?",
                    answer: "Yes, our website uses industry-standard SSL encryption to protect your payment information. We never store your full credit card details on our servers."
                },
                {
                    question: "Do you offer financing options?",
                    answer: "Yes, we offer several financing options through Affirm and Klarna. You can select these payment methods at checkout to see if you qualify."
                },
                {
                    question: "When will my card be charged?",
                    answer: "Your card will be authorized when you place your order but will only be charged when your order ships."
                }
            ]
        }
    };

    // Function to open popup
    function openPopup(category) {
        const data = faqData[category];
        if (!data) return;

        // Set popup title
        document.getElementById('popupTitle').textContent = data.title;

        // Generate content
        let contentHTML = '';
        data.questions.forEach(item => {
            contentHTML += `
                     <div class="faq-item">
                         <div class="faq-question">${item.question}</div>
                         <div class="faq-answer">${item.answer}</div>
                     </div>
                 `;
        });

        // Set content
        document.getElementById('popupContent').innerHTML = contentHTML;

        // Show popup
        document.getElementById('popupOverlay').classList.add('active');

        // Prevent body scrolling
        document.body.style.overflow = 'hidden';
    }

    Function to close popup

    function faqclosePopup() {
        document.getElementById('popupOverlay').classList.remove('active');
        document.body.style.overflow = '';
    }

    // Close popup when clicking outside content
    document.getElementById('popupOverlay').addEventListener('click', function(event) {
        if (event.target === this) {
            faqclosePopup();
        }
    });
</script>
