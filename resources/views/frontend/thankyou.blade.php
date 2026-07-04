@extends('frontend.app')

@section('styles')
    <style>
        .thankyou-container {
            max-width: 800px;
            margin: 15px auto;
            padding: 0 20px;
        }

        .thankyou-top {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .print-btn,
        .download-pdf-btn {
            padding: 8px 15px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 13px;
            transition: all 0.3s;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            white-space: nowrap;
        }

        .print-btn {
            background-color: var(--light-color);
            color: var(--secondary-color);
            border: 1px solid var(--border-color);
        }

        .download-pdf-btn {
            background-color: var(--secondary-color);
            color: white;
            border: none;
        }

        .print-btn:hover,
        .download-pdf-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-sm);
        }

        .thankyou-message-area {
            text-align: center;
            margin-bottom: 40px;
        }

        .thankyou-message {
            font-size: 24px;
            color: var(--secondary-color);
            font-weight: 600;
        }

        .thankyou-message-details {
            margin-top: 10px;
            color: var(--text-color);
            font-size: 17px;
            line-height: 1.7;
        }

        .thankyou-message-details p {
            margin: 6px 0;
        }

        .thankyou-message-details a {
            color: var(--primary-color);
            font-weight: 600;
            text-decoration: none;
        }

        .thankyou-message-details a:hover {
            text-decoration: underline;
        }

        .thankyou-message-details p:first-child {
            font-size: 25px;
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 6px;
        }

        .order-summary {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            padding: 25px;
            background-color: var(--light-color);
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .order-summary div p:first-child {
            color: var(--text-color);
            font-size: 14px;
            margin-bottom: 8px;
        }

        .order-summary div p:last-child {
            color: var(--secondary-color);
            font-weight: 600;
            font-size: 16px;
        }

        .payment-note {
            padding: 15px;
            background-color: #fff3e0;
            border-left: 4px solid var(--primary-color);
            color: var(--text-color);
            margin-bottom: 30px;
            border-radius: 4px;
        }

        .order-details {
            background-color: white;
            border-radius: 8px;
            box-shadow: var(--shadow-sm);
            padding: 25px;
            margin-bottom: 30px;
        }

        .order-details h2 {
            font-size: 18px;
            color: var(--secondary-color);
            margin-bottom: 20px;
        }

        /* Combo order styles */
        .combo-order-item {
            padding: 10px 0;
        }

        .combo-title {
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 8px;
        }

        .combo-badge {
            background-color: #10b981;
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 12px;
            margin-left: 8px;
        }

        .combo-selections {
            margin: 8px 0;
            padding-left: 15px;
        }

        .combo-selection-item {
            margin: 4px 0;
            color: var(--text-color);
        }

        .combo-selection-item small {
            font-size: 13px;
            font-weight: 600;
        }

        .order-details table {
            width: 100%;
            border-collapse: collapse;
        }

        .order-details th,
        .order-details td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        .order-details th {
            font-weight: 600;
            color: var(--secondary-color);
            background-color: var(--light-color);
        }

        .order-details tr:last-child td {
            border-bottom: none;
            font-weight: 600;
            color: var(--secondary-color);
        }

        .footer {
            text-align: center;
            color: var(--text-color);
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid var(--border-color);
        }

        .footer a {
            text-decoration: none;
            font-weight: 500;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        .product-variation {
            margin-top: 5px;
            font-size: 0.9em;
            color: var(--text-color);
        }

        .variation-price {
            color: var(--secondary-color);
            font-weight: 600;
        }

        .variation-original-price {
            text-decoration: line-through;
            color: #999;
            margin-right: 5px;
        }

        @media (max-width: 768px) {
            .order-summary {
                grid-template-columns: repeat(2, 1fr);
            }

            .thankyou-message {
                font-size: 20px;
            }

            .order-details {
                padding: 15px;
            }

            .order-details th,
            .order-details td {
                padding: 10px;
            }
        }

        @media (max-width: 576px) {
            .thankyou-top {
                flex-direction: column;
                gap: 8px;
            }

            .print-btn,
            .download-pdf-btn {
                width: 100%;
                text-align: center;
                justify-content: center;
            }

            .order-summary {
                grid-template-columns: 1fr;
                gap: 15px;
            }
        }

        @media print {
            .thankyou-top,
            .footer a {
                display: none;
            }

            .thankyou-container {
                margin: 0;
                padding: 0;
            }
        }
    </style>
@endsection

@section('content')
<div class="thankyou-container" data-order-id="{{ $order->id }}" data-order-name="{{ $order->name }}"
    data-order-upazila="{{ $order->upazila }}" data-order-city="{{ $order->city }}"
    data-order-address="{{ $order->address }}" data-order-phone="{{ $order->phone }}"
    data-order-email="{{ $order->email ?? '' }}" data-payment-method="{{ $order->payment_method }}"
    data-purchase-event-pending="{{ isset($firePurchaseEvent) && !$firePurchaseEvent ? 'true' : 'false' }}">
    <!-- Right Side Buttons -->
    <div class="thankyou-top">
        <!-- Download Invoice Button -->
        <a href="{{ route('order.download-invoice', $order->id) }}" class="download-pdf-btn">
            <i class="fas fa-download me-1"></i> Download Invoice
        </a>
    </div>

    <!-- Thank You Message -->
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
    <div class="thankyou-message-area">
        <!-- <h2 class="thankyou-message">Thank You {{ $order->name }}. Your Order Has Been Received.</h2> -->
        <div class="thankyou-message-details">
            @foreach ($thankyouMessageParagraphs as $paragraph)
                @php
                    $paragraphHtml = nl2br(e($paragraph));
                    $paragraphHtml = strtr($paragraphHtml, $thankyouReplacements);
                @endphp
                <p>{!! $paragraphHtml !!}</p>
            @endforeach
        </div>
    </div>

    <!-- Order Summary Section -->
    @if (isset($order))
        <div class="order-summary">
            <div class="">
                <p class="">Order ID:</p>
                <p class="order-id">{{ $order->id }}</p>
            </div>
            <div class="">
                <p class="">Date:</p>
                <p class="">{{ date('F j, Y', strtotime($order->created_at)) }}</p>
            </div>
            <div class="">
                <p class="">Total:</p>
                <p class="">৳ {{ number_format($order->total_with_charge, 2) }}</p>
            </div>
            <div class="">
                <p class="">Payment method:</p>
                <p class="">{{ strtoupper($order->payment_method) }}</p>
            </div>
        </div>

        <!-- Payment Notes -->
        <p class="payment-note">Pay with {{ strtoupper($order->payment_method) }}.</p>

        <!-- Order Details -->
        <div class="order-details">
            <h2 class="">ORDER DETAILS</h2>
            <table class="">
                <thead>
                    <tr class="">
                        <th class="">PRODUCT</th>
                        <th class="">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($isComboOrder) && $isComboOrder)
                        {{-- Display combo order information --}}
                        @php
                            $subtotal = $order->sub_total ?? ($comboOffer->combo_price * $orderItems->first()->quantity);
                        @endphp
                        <tr data-combo-id="{{ $comboOffer->id }}" data-category="Combo Offer">
                            <td>
                                <div class="combo-order-item">
                                    <div class="combo-title">
                                        {{ $comboOffer->title }}
                                        <span class="combo-badge">COMBO OFFER</span>
                                    </div>
                                    <div class="combo-selections">
                                        @php
                                            // Ensure combo selections is properly decoded
                                            $selections = $comboSelections;
                                            if (is_string($selections)) {
                                                $selections = json_decode($selections, true);
                                            }
                                            if (!is_array($selections)) {
                                                $selections = [];
                                            }
                                        @endphp
                                        
                                        @if(count($selections) > 0)
                                            @foreach($selections as $selection)
                                            @php
                                                $product = \App\Models\Product::find($selection['product_id']);
                                                $variation = null;
                                                if (isset($selection['variation_id']) && $selection['variation_id']) {
                                                    $variation = \App\Models\VariationCombination::find($selection['variation_id']);
                                                }
                                            @endphp
                                            @if($product)
                                                <div class="combo-selection-item">
                                                    <small>
                                                        {{ $product->title }}
                                                        @if($variation)
                                                            ({{ $variation->display_name }})
                                                        @endif
                                                    </small>
                                                </div>
                                            @endif
                                        @endforeach
                                        @else
                                            <div class="combo-selection-item">
                                                <small>No selections available</small>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="product-variation">
                                        <small>Quantity: {{ $orderItems->first()->quantity }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>৳ {{ number_format($subtotal, 2) }}</td>
                        </tr>
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
                            <tr data-product-id="{{ $item->product->id }}" data-category="{{ $item->product->category->name ?? '' }}">
                                <td>
                                    {{ $item->product->title }}
                                    @if($item->product->product_type === 'variable' && $item->variationCombination)
                                        <div class="product-variation">
                                            <small>{{ $item->variationCombination->display_name }}</small>
                                            @if($item->variationCombination->short_description)
                                                <br>
                                                <small>{{ $item->variationCombination->short_description }}</small>
                                            @endif
                                            <div>
                                                <small>Quantity: {{ $item->quantity }}</small>
                                                @if($item->variationCombination->has_offer)
                                                    <span class="variation-price">
                                                        <span class="variation-original-price">{{ $item->variationCombination->formatted_regular_price }}</span>
                                                        {{ $item->variationCombination->formatted_offer_price }}
                                                    </span>
                                                @else
                                                    <span class="variation-price">{{ $item->variationCombination->formatted_regular_price }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <div class="product-variation">
                                            <small>Quantity: {{ $item->quantity }} × ৳{{ number_format($item->price, 2) }}</small>
                                        </div>
                                    @endif
                                </td>
                                <td>৳ {{ number_format($item->sub_total, 2) }}</td>
                            </tr>
                        @endforeach
                        @endforeach
                    @endif
                    <tr>
                        <td class="">Subtotal:</td>
                        <td class="">৳ {{ number_format($subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="">Shipping:</td>
                        <td class="">৳ {{ number_format($order->shipping, 2) }}</td>
                    </tr>
                    @if ($order->bkash_charge > 0)
                        <tr>
                            <td>Bkash Charge (1.8%)</td>
                            <td>৳ {{ number_format($order->bkash_charge, 2) }}</td>
                        </tr>
                    @endif
                    @if ($order->nagad_charge > 0)
                        <tr>
                            <td>Nagad Charge (1.8%)</td>
                            <td>৳ {{ number_format($order->nagad_charge, 2) }}</td>
                        </tr>
                    @endif
                    @if ($order->rocket_charge > 0)
                        <tr>
                            <td>Rocket Charge (1.8%)</td>
                            <td>৳ {{ number_format($order->rocket_charge, 2) }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td class="">TOTAL:</td>
                        <td class="order-total">৳ {{ number_format($order->total_with_charge, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    @else
        <!-- Fallback Message -->
        <div class="">
            <p class="">No order details found.</p>
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p class="">
            If you have any questions, please contact us at
            @php
                $supportEmail = setting('general', 'contact_email', 'support@thikana.shop');
            @endphp
            <a href="mailto:{{ $supportEmail }}" style="color:var(--primary-color)">{{ $supportEmail }}</a>
        </p>
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
