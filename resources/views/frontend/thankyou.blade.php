@extends('frontend.app')

@section('styles')
    <style>
        :root {
            --success-color: #10b981;
            --success-bg: #ecfdf5;
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            --card-border-radius: 16px;
            --text-muted: #6b7280;
        }

        .thankyou-wrapper {
            background-color: #f9fafb;
            padding: 40px 0;
            font-family: 'Outfit', 'Inter', sans-serif;
        }

        .thankyou-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Top Action Buttons */
        .thankyou-top {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 24px;
        }

        .download-pdf-btn {
            background: var(--primary-gradient);
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(59, 130, 246, 0.3);
            transition: all 0.3s ease;
        }

        .download-pdf-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
            color: white;
        }

        /* Hero Success Card */
        .success-hero-card {
            background: white;
            border-radius: var(--card-border-radius);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            padding: 40px 30px;
            text-align: center;
            margin-bottom: 30px;
            border: 1px solid #f3f4f6;
            position: relative;
            overflow: hidden;
        }

        .success-hero-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: var(--primary-gradient);
        }

        .checkmark-wrapper {
            width: 80px;
            height: 80px;
            background-color: var(--success-bg);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            color: var(--success-color);
            font-size: 36px;
            animation: pulse-success 2s infinite;
        }

        @keyframes pulse-success {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
            70% { box-shadow: 0 0 0 15px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        .thankyou-message-details {
            color: #374151;
            font-size: 16px;
            line-height: 1.8;
            max-width: 650px;
            margin: 0 auto;
        }

        .thankyou-message-details p {
            margin-bottom: 12px;
        }

        .thankyou-message-details a {
            color: #3b82f6;
            font-weight: 600;
            text-decoration: none;
            border-bottom: 2px dashed rgba(59, 130, 246, 0.3);
            transition: all 0.2s ease;
            padding-bottom: 2px;
        }

        .thankyou-message-details a:hover {
            color: #2563eb;
            border-bottom-color: #2563eb;
        }

        /* Modern Stepper / Timeline */
        .order-stepper {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin: 40px auto 10px;
            max-width: 700px;
        }

        .order-stepper::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 5%;
            right: 5%;
            height: 4px;
            background-color: #e5e7eb;
            z-index: 1;
        }

        .step-progress-bar {
            position: absolute;
            top: 20px;
            left: 5%;
            width: 25%; /* Step 1 complete */
            height: 4px;
            background: var(--primary-gradient);
            z-index: 2;
            transition: width 0.4s ease;
        }

        .step-item {
            position: relative;
            z-index: 3;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 25%;
        }

        .step-badge {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background-color: #e5e7eb;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 4px solid white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        .step-item.active .step-badge {
            background: var(--primary-gradient);
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .step-item.completed .step-badge {
            background-color: var(--success-color);
            color: white;
        }

        .step-title {
            margin-top: 10px;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-muted);
            text-align: center;
        }

        .step-item.active .step-title {
            color: #1f2937;
        }

        /* Two-Column Grid Info */
        .thankyou-grid {
            display: grid;
            grid-template-columns: 1.6fr 1fr;
            gap: 30px;
            margin-top: 30px;
        }

        .info-card {
            background: white;
            border-radius: var(--card-border-radius);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            border: 1px solid #f3f4f6;
            padding: 24px;
            margin-bottom: 24px;
        }

        .info-card-title {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid #f3f4f6;
            padding-bottom: 12px;
        }

        .info-card-title i {
            color: #4f46e5;
        }

        /* Order Summary Items List */
        .item-list-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 0;
            border-bottom: 1px dashed #f3f4f6;
        }

        .item-list-row:last-child {
            border-bottom: none;
        }

        .item-detail-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .item-info-text .product-name {
            font-size: 15px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .item-info-text .product-meta {
            font-size: 13px;
            color: var(--text-muted);
        }

        .item-price-right {
            font-size: 15px;
            font-weight: 700;
            color: #111827;
            text-align: right;
        }

        /* Payment Summary Box */
        .summary-price-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 14px;
            color: #4b5563;
        }

        .summary-price-row.total-row {
            border-top: 1px solid #f3f4f6;
            padding-top: 16px;
            margin-top: 16px;
            font-size: 18px;
            font-weight: 800;
            color: #111827;
        }

        .summary-price-row.total-row .total-amount {
            color: #e11d48;
        }

        /* Quick Info Metadata Grid */
        .metadata-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .metadata-item {
            background-color: #f9fafb;
            padding: 16px;
            border-radius: 12px;
            border: 1px solid #f3f4f6;
        }

        .metadata-label {
            font-size: 12px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 4px;
        }

        .metadata-value {
            font-size: 14px;
            font-weight: 700;
            color: #1f2937;
        }

        /* Contact & Help Section */
        .support-links-box {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-top: 16px;
        }

        .support-btn-card {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 16px;
            border-radius: 12px;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
        }

        .support-btn-card:hover {
            background-color: white;
            border-color: #3b82f6;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.05);
        }

        .support-btn-card i {
            font-size: 20px;
            color: #3b82f6;
        }

        .support-btn-card span {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
        }

        /* Responsive Layout */
        @media (max-width: 868px) {
            .thankyou-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .thankyou-wrapper {
                padding: 20px 0;
            }
            .success-hero-card {
                padding: 30px 16px;
            }
            .metadata-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            .support-links-box {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
<div class="thankyou-wrapper">
    <div class="thankyou-container" data-order-id="{{ $order->id }}" data-order-name="{{ $order->name }}"
        data-order-upazila="{{ $order->upazila }}" data-order-city="{{ $order->city }}"
        data-order-address="{{ $order->address }}" data-order-phone="{{ $order->phone }}"
        data-order-email="{{ $order->email ?? '' }}" data-payment-method="{{ $order->payment_method }}"
        data-purchase-event-pending="{{ isset($firePurchaseEvent) && !$firePurchaseEvent ? 'true' : 'false' }}">
        
        <!-- Right Side Action Buttons -->
        <div class="thankyou-top">
            <a href="{{ route('order.download-invoice', $order->id) }}" class="download-pdf-btn">
                <i class="fas fa-file-invoice"></i> Download Invoice
            </a>
        </div>

        @php
            $siteName = setting('general', 'site_name', 'Sylbaba.com');
            $supportPhone = setting('general', 'phone_number', '01308-26309');
            $supportPhoneTel = preg_replace('/[^0-9+]/', '', $supportPhone);
            $orderPhone = $order->phone ?? '';
            $trackLinkText = setting('general', 'thankyou_track_link_text', 'Order Track');
            $thankyouMessage = setting('general', 'thankyou_message_text', "ধন্যবাদ!\nআপনার অর্ডারটি সফলভাবে গ্রহণ করা হয়েছে।\n\n:site_name-এ কেনাকাটার জন্য আপনাকে আন্তরিক ধন্যবাদ। আমরা দ্রুততম সময়ে আপনার পণ্য প্রস্তুত ও ডেলিভারি করবো, ইনশাআল্লাহ।\n\nঅর্ডার সংক্রান্ত যেকোনো প্রয়োজনে কল বা ম্যাসেজ করুন :phone_link নাম্বারে।\n\nআপনার অর্ডারটি ট্র্যাক করতে এখানে ক্লিক করুন: :track_link");
            $phoneLinkHtml = $supportPhoneTel !== ''
                ? '<a href="tel:' . e($supportPhoneTel) . '">' . e($supportPhone) . '</a>'
                : e($supportPhone);
            $trackLinkUrl = $orderPhone !== ''
                ? route('order.track', ['phone' => $orderPhone, 'auto' => '1'])
                : route('order.track');
            $trackLinkHtml = '<a href="' . e($trackLinkUrl) . '">' . e($trackLinkText) . '</a>';
            $thankyouReplacements = [
                ':site_name' => e($siteName),
                ':phone' => e($supportPhone),
                ':phone_link' => $phoneLinkHtml,
                ':track_link' => $trackLinkHtml,
            ];
            $thankyouMessageParagraphs = array_values(array_filter(
                preg_split("/\\R\\R+/", trim($thankyouMessage)),
                function ($paragraph) {
                    return trim($paragraph) !== '';
                }
            ));
        @endphp

        <!-- Redesigned Hero Card -->
        <div class="success-hero-card">
            <div class="checkmark-wrapper">
                <i class="fas fa-check-circle"></i>
            </div>
            
            <div class="thankyou-message-details">
                @foreach ($thankyouMessageParagraphs as $paragraph)
                    @php
                        $paragraphHtml = nl2br(e($paragraph));
                        $paragraphHtml = strtr($paragraphHtml, $thankyouReplacements);
                    @endphp
                    <p>{!! $paragraphHtml !!}</p>
                @endforeach
            </div>

            <!-- Modern Delivery Stepper Tracker -->
            <div class="order-stepper">
                <div class="step-progress-bar"></div>
                <div class="step-item active">
                    <div class="step-badge">1</div>
                    <div class="step-title">Order Placed</div>
                </div>
                <div class="step-item">
                    <div class="step-badge">2</div>
                    <div class="step-title">Processing</div>
                </div>
                <div class="step-item">
                    <div class="step-badge">3</div>
                    <div class="step-title">On The Way</div>
                </div>
                <div class="step-item">
                    <div class="step-badge">4</div>
                    <div class="step-title">Delivered</div>
                </div>
            </div>
        </div>

        @if (isset($order))
            <!-- Two Column Section -->
            <div class="thankyou-grid">
                
                <!-- Left Column: Order Items Details -->
                <div class="left-section">
                    <div class="info-card">
                        <div class="info-card-title">
                            <i class="fas fa-shopping-bag"></i>
                            <span>Items In Your Order</span>
                        </div>
                        
                        <div class="items-container">
                            @if(isset($isComboOrder) && $isComboOrder)
                                {{-- Display combo order information --}}
                                @php
                                    $subtotal = $order->sub_total ?? ($comboOffer->combo_price * $orderItems->first()->quantity);
                                @endphp
                                <div class="item-list-row" data-combo-id="{{ $comboOffer->id }}" data-category="Combo Offer">
                                    <div class="item-detail-left">
                                        <div class="item-info-text">
                                            <div class="product-name" style="color: #10b981; font-weight: 700;">
                                                {{ $comboOffer->title }} [COMBO OFFER]
                                            </div>
                                            <div class="product-meta">
                                                @php
                                                    $selections = $comboSelections;
                                                    if (is_string($selections)) {
                                                        $selections = json_decode($selections, true);
                                                    }
                                                    if (!is_array($selections)) {
                                                        $selections = [];
                                                    }
                                                @endphp
                                                @if(count($selections) > 0)
                                                    <ul style="margin: 5px 0 0; padding-left: 15px; font-size: 12px; color: #6b7280;">
                                                        @foreach($selections as $selection)
                                                            @php
                                                                $product = \App\Models\Product::find($selection['product_id']);
                                                                $variation = null;
                                                                if (isset($selection['variation_id']) && $selection['variation_id']) {
                                                                    $variation = \App\Models\VariationCombination::find($selection['variation_id']);
                                                                }
                                                            @endphp
                                                            @if($product)
                                                                <li>
                                                                    {{ $product->title }}
                                                                    @if($variation)
                                                                        ({{ $variation->display_name }})
                                                                    @endif
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                @endif
                                                <div style="margin-top: 6px; font-weight: 500;">
                                                    Qty: {{ $orderItems->first()->quantity }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item-price-right">
                                        ৳ {{ number_format($subtotal, 2) }}
                                    </div>
                                </div>
                            @else
                                {{-- Display regular order items --}}
                                @php
                                    $subtotal = $orderItems->sum(function ($items) {
                                        return $items->sum(function ($item) {
                                            return $item->sub_total;
                                        });
                                    });
                                @endphp
                                @foreach ($orderItems as $productId => $items)
                                    @foreach ($items as $item)
                                        <div class="item-list-row" data-product-id="{{ $item->product->id }}" data-category="{{ $item->product->category->name ?? '' }}">
                                            <div class="item-detail-left">
                                                <div class="item-info-text">
                                                    <div class="product-name">{{ $item->product->title }}</div>
                                                    <div class="product-meta">
                                                        @if($item->product->product_type === 'variable' && $item->variationCombination)
                                                            <span>{{ $item->variationCombination->display_name }}</span>
                                                            @if($item->variationCombination->short_description)
                                                                <br><small>{{ $item->variationCombination->short_description }}</small>
                                                            @endif
                                                            <div style="margin-top: 4px;">
                                                                Qty: {{ $item->quantity }} × 
                                                                @if($item->variationCombination->has_offer)
                                                                    <span style="font-weight:600; color:#111827;">{{ $item->variationCombination->formatted_offer_price }}</span>
                                                                @else
                                                                    <span style="font-weight:600; color:#111827;">{{ $item->variationCombination->formatted_regular_price }}</span>
                                                                @endif
                                                            </div>
                                                        @else
                                                            <span>Qty: {{ $item->quantity }} × ৳{{ number_format($item->price, 2) }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="item-price-right">
                                                ৳ {{ number_format($item->sub_total, 2) }}
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <!-- Customer Delivery Details -->
                    <div class="info-card">
                        <div class="info-card-title">
                            <i class="fas fa-shipping-fast"></i>
                            <span>Delivery Address</span>
                        </div>
                        <div style="line-height: 1.8; color: #4b5563; font-size: 14px;">
                            <p style="font-weight: 700; color: #1f2937; margin-bottom: 6px;">{{ $order->name }}</p>
                            <p style="margin-bottom: 4px;"><i class="fas fa-phone-alt" style="width: 20px; color: #9ca3af;"></i> {{ $order->phone }}</p>
                            @if($order->email)
                                <p style="margin-bottom: 4px;"><i class="fas fa-envelope" style="width: 20px; color: #9ca3af;"></i> {{ $order->email }}</p>
                            @endif
                            <p style="margin-bottom: 4px;"><i class="fas fa-map-marker-alt" style="width: 20px; color: #9ca3af;"></i> {{ $order->address }}</p>
                            <p style="margin-bottom: 4px; padding-left: 20px;">{{ $order->upazila ? $order->upazila . ', ' : '' }}{{ $order->city }}</p>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Order Summary Metadata & Billing -->
                <div class="right-section">
                    <div class="info-card">
                        <div class="info-card-title">
                            <i class="fas fa-receipt"></i>
                            <span>Order Summary</span>
                        </div>

                        <!-- Basic Metadata -->
                        <div class="metadata-grid" style="margin-bottom: 24px;">
                            <div class="metadata-item">
                                <div class="metadata-label">Order ID</div>
                                <div class="metadata-value order-id">#{{ $order->id }}</div>
                            </div>
                            <div class="metadata-item">
                                <div class="metadata-label">Date</div>
                                <div class="metadata-value">{{ date('M d, Y', strtotime($order->created_at)) }}</div>
                            </div>
                        </div>

                        <!-- Billing Details -->
                        <div class="summary-price-row">
                            <span>Subtotal</span>
                            <span>৳ {{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="summary-price-row">
                            <span>Shipping</span>
                            <span>৳ {{ number_format($order->shipping, 2) }}</span>
                        </div>

                        @if ($order->bkash_charge > 0)
                            <div class="summary-price-row">
                                <span>bKash Charge (1.8%)</span>
                                <span>৳ {{ number_format($order->bkash_charge, 2) }}</span>
                            </div>
                        @endif
                        @if ($order->nagad_charge > 0)
                            <div class="summary-price-row">
                                <span>Nagad Charge (1.8%)</span>
                                <span>৳ {{ number_format($order->nagad_charge, 2) }}</span>
                            </div>
                        @endif
                        @if ($order->rocket_charge > 0)
                            <div class="summary-price-row">
                                <span>Rocket Charge (1.8%)</span>
                                <span>৳ {{ number_format($order->rocket_charge, 2) }}</span>
                            </div>
                        @endif

                        <div class="summary-price-row" style="margin-top: 10px; font-size: 13px;">
                            <span>Payment Method</span>
                            <span style="font-weight: 700; color: #4b5563;">{{ strtoupper($order->payment_method) }}</span>
                        </div>

                        <div class="summary-price-row total-row">
                            <span>Total Amount</span>
                            <span class="total-amount order-total">৳ {{ number_format($order->total_with_charge, 2) }}</span>
                        </div>
                    </div>

                    <!-- Get Help & Contact Section -->
                    <div class="info-card">
                        <div class="info-card-title">
                            <i class="fas fa-headset"></i>
                            <span>Need Assistance?</span>
                        </div>
                        <p style="font-size: 13px; color: var(--text-muted); line-height: 1.6; margin-bottom: 16px;">
                            If you have any questions regarding your order, feel free to connect with our support team.
                        </p>
                        
                        <div class="support-links-box">
                            @if($supportPhoneTel !== '')
                                <a href="tel:{{ $supportPhoneTel }}" class="support-btn-card">
                                    <i class="fas fa-phone-alt"></i>
                                    <span>Call Support</span>
                                </a>
                            @endif
                            @php
                                $supportEmail = setting('general', 'contact_email', 'support@thikana.shop');
                            @endphp
                            <a href="mailto:{{ $supportEmail }}" class="support-btn-card">
                                    <i class="fas fa-envelope"></i>
                                    <span>Email Us</span>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        @else
            <!-- Fallback Message -->
            <div class="info-card text-center" style="padding: 50px 20px;">
                <p style="color: var(--text-muted); font-size: 16px;">No order details found.</p>
            </div>
        @endif

    </div>
</div>
@endsection

@section('scripts')
    @if(isset($firePurchaseEvent) && $firePurchaseEvent)
    {{-- Fire purchase event via GTM dataLayer when delayed purchase events system is OFF --}}
    <script>
        window.dataLayer = window.dataLayer || [];

        @php
            // Build items array for dataLayer
            $dataLayerItems = [];

            if (isset($isComboOrder) && $isComboOrder && isset($comboOffer)) {
                // Combo order - single item
                $dataLayerItems[] = [
                    'item_id' => (string) $comboOffer->id,
                    'item_name' => $comboOffer->title,
                    'item_category' => 'Combo Offer',
                    'price' => (float) $comboOffer->combo_price,
                    'quantity' => (int) ($orderItems->first()->quantity ?? 1),
                ];
            } else {
                // Regular order - multiple items
                foreach ($orderItems as $productId => $items) {
                    foreach ($items as $item) {
                        $dataLayerItems[] = [
                            'item_id' => (string) $item->product_id,
                            'item_name' => $item->product->title ?? 'Unknown',
                            'item_category' => $item->product->category->name ?? '',
                            'item_variant' => $item->variationCombination->display_name ?? '',
                            'price' => (float) $item->price,
                            'quantity' => (int) $item->quantity,
                        ];
                    }
                }
            }

            // Parse name for user data
            $nameParts = explode(' ', $order->name, 2);
            $firstName = $nameParts[0] ?? '';
            $lastName = $nameParts[1] ?? '';

            // Normalize phone
            $phone = $order->phone;
            if (str_starts_with($phone, '+88')) {
                $phone = substr($phone, 3);
            } elseif (str_starts_with($phone, '88')) {
                $phone = substr($phone, 2);
            }
        @endphp

        // Push purchase event to dataLayer for GTM
        dataLayer.push({
            'event': 'purchase',
            'ecommerce': {
                'transaction_id': '{{ $order->id }}',
                'value': {{ (float) $order->sub_total }},
                'currency': 'BDT',
                'shipping': {{ (float) ($order->shipping ?? 0) }},
                'items': @json($dataLayerItems)
            },
            'user_data': {
                'email': '{{ strtolower(trim($order->email ?? '')) }}',
                'phone_number': '{{ $phone }}',
                'first_name': '{{ $firstName }}',
                'last_name': '{{ $lastName }}',
                'city': '{{ $order->city ?? '' }}',
                'region': '{{ $order->upazila ?? '' }}',
                'country': 'BD'
            }
        });

        console.log('[Thikana] Purchase event pushed to dataLayer for GTM');
    </script>
    @endif

    <script>
        // Send Telegram notification asynchronously (non-blocking)
        document.addEventListener('DOMContentLoaded', function() {
            const orderId = {{ $order->id }};

            // Send notification in background
            fetch('{{ route('send.order.notification') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    order_id: orderId
                })
            }).catch(error => {
                // Silent fail - user doesn't need to know if notification fails
                console.log('Notification sent in background');
            });
        });
    </script>
@endsection
