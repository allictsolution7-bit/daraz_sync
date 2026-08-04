@extends('frontend.app')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('new/user.styles.css') }}">
    <style>
        .wishlist-container {
            margin: 30px auto;
            padding: 0 15px;
            max-width: 1200px;
        }

        .wishlist-layout {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 30px;
        }

        .wishlist-card {
            background-color: #ffffff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            border-radius: 16px;
            border: 1px solid rgba(226, 232, 240, 0.8);
            overflow: hidden;
        }

        .wishlist-header {
            padding: 24px 30px;
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            background-color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .wishlist-header h4 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .wishlist-header h4 i {
            color: #ef4444;
        }

        .wishlist-body {
            padding: 30px;
        }

        .wishlist-items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
        }

        .wishlist-item-card {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 12px;
            padding: 15px;
            text-align: center;
            position: relative;
            transition: all 0.3s ease;
        }

        .wishlist-item-card:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06);
            transform: translateY(-3px);
        }

        .wishlist-item-img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 12px;
        }

        .wishlist-item-title {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 6px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 42px;
        }

        .wishlist-item-price {
            font-size: 16px;
            font-weight: 800;
            color: #ff6a00;
            margin-bottom: 12px;
        }

        .wishlist-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            justify-content: center;
        }

        .btn-view-product {
            background: #ff6a00;
            color: #ffffff !important;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            flex-grow: 1;
        }

        .btn-remove-wishlist {
            background: #fee2e2;
            color: #ef4444;
            border: none;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .btn-remove-wishlist:hover {
            background: #fca5a5;
        }

        .empty-wishlist-box {
            text-align: center;
            padding: 40px 20px;
        }

        .empty-wishlist-icon {
            width: 70px;
            height: 70px;
            background-color: #fef2f2;
            color: #ef4444;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            margin-bottom: 15px;
        }

        @media (max-width: 768px) {
            .wishlist-layout {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
<div class="base-container wishlist-container">
    <div class="wishlist-layout">
        <!-- Sidebar Menu -->
        @include('frontend.user.partials.sidebar')

        <!-- Main Content Card -->
        <div class="wishlist-card">
            <div class="wishlist-header">
                <h4><i class="fa-solid fa-heart"></i> My Saved Wishlist</h4>
                <span id="wishlist-count-badge" style="background: #fee2e2; color: #ef4444; font-weight: 700; padding: 4px 12px; border-radius: 20px; font-size: 13px;">0 Items</span>
            </div>

            <div class="wishlist-body">
                <!-- Wishlist Items Grid -->
                <div id="wishlist-grid" class="wishlist-items-grid" style="display: none;">
                    <!-- Dynamically rendered via JS -->
                </div>

                <!-- Empty Wishlist Placeholder -->
                <div id="empty-wishlist-box" class="empty-wishlist-box">
                    <div class="empty-wishlist-icon">
                        <i class="fa-solid fa-heart-crack"></i>
                    </div>
                    <h3 style="font-size: 20px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">Your Wishlist is Empty</h3>
                    <p style="color: #64748b; font-size: 14px; margin-bottom: 20px;">
                        You haven't saved any items yet. Browse our store products and click "Add to Wishlist" to save them!
                    </p>
                    <a href="{{ url('/shop') }}" style="background: linear-gradient(135deg, #ff6a00 0%, #ee0979 100%); color: white !important; padding: 10px 24px; border-radius: 25px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-bag-shopping"></i> Browse Shop Catalog
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function renderWishlistItems() {
        let wishlist = JSON.parse(localStorage.getItem('user_wishlist') || '[]');
        let grid = document.getElementById('wishlist-grid');
        let emptyBox = document.getElementById('empty-wishlist-box');
        let countBadge = document.getElementById('wishlist-count-badge');

        if (countBadge) {
            countBadge.textContent = wishlist.length + (wishlist.length === 1 ? ' Item' : ' Items');
        }

        if (wishlist.length === 0) {
            if (grid) grid.style.display = 'none';
            if (emptyBox) emptyBox.style.display = 'block';
            return;
        }

        if (emptyBox) emptyBox.style.display = 'none';
        if (grid) {
            grid.style.display = 'grid';
            grid.innerHTML = wishlist.map(item => `
                <div class="wishlist-item-card">
                    <img src="${item.image}" alt="${item.title}" class="wishlist-item-img" onerror="this.src='/clientside/images/profile.png'">
                    <div class="wishlist-item-title">${item.title}</div>
                    <div class="wishlist-item-price">৳${item.price}</div>
                    <div class="wishlist-actions">
                        <a href="${item.url}" class="btn-view-product">View Product</a>
                        <button type="button" class="btn-remove-wishlist" onclick="removeWishlistItem(${item.id})" title="Remove item">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
            `).join('');
        }
    }

    function removeWishlistItem(id) {
        let wishlist = JSON.parse(localStorage.getItem('user_wishlist') || '[]');
        wishlist = wishlist.filter(item => item.id != id);
        localStorage.setItem('user_wishlist', JSON.stringify(wishlist));
        renderWishlistItems();
    }

    document.addEventListener('DOMContentLoaded', renderWishlistItems);
</script>
@endsection
