@php
    $currentUser = $user ?? auth()->user();
    $sidebarName = $currentUser->name ?? 'User Account';
    $sidebarEmail = $currentUser->email ?? 'Account details';
    $sidebarProfileImage = ($currentUser && !empty($currentUser->profile_photo_path))
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($currentUser->profile_photo_path)
        : asset('clientside/images/profile.png');
@endphp

<div class="profile-sidebar">
    <div class="sidebar-header">
        <img src="{{ $sidebarProfileImage }}" alt="Profile" class="sidebar-avatar">
        <div>
            <h3 class="sidebar-user-name">{{ $sidebarName }}</h3>
            <p class="sidebar-user-email">{{ $sidebarEmail }}</p>
        </div>
    </div>

    <ul class="sidebar-menu">
        @auth
            @if(auth()->user()->isVendor() || auth()->user()->hasRole('vendor'))
                <li class="sidebar-menu-item">
                    <a href="{{ Route::has('vendor.dashboard') ? route('vendor.dashboard') : url('/vendor/dashboard') }}" 
                       class="sidebar-menu-link" 
                       style="background-color: #4f46e5; color: #ffffff; font-weight: 600; margin-bottom: 12px; border-radius: 6px;">
                        <i class="fa-solid fa-store sidebar-menu-icon" style="color: #ffffff;"></i>
                        <span>Vendor Dashboard</span>
                    </a>
                </li>
            @elseif(auth()->user()->isAdmin() || auth()->user()->hasRole('admin') || auth()->user()->hasRole('super_admin') || auth()->user()->hasRole('super admin'))
                <li class="sidebar-menu-item">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="sidebar-menu-link" 
                       style="background-color: #4f46e5; color: #ffffff; font-weight: 600; margin-bottom: 12px; border-radius: 6px;">
                        <i class="fa-solid fa-gauge sidebar-menu-icon" style="color: #ffffff;"></i>
                        <span>Admin Dashboard</span>
                    </a>
                </li>
            @endif
        @endauth

        <li class="sidebar-menu-item">
            <a href="{{ route('account.show') }}" class="sidebar-menu-link {{ Route::currentRouteName() == 'account.show' ? 'active' : '' }}">
                <i class="fa-solid fa-user sidebar-menu-icon"></i>
                <span>Account</span>
            </a>
        </li>
        <li class="sidebar-menu-item">
            <a href="{{ route('account.orders') }}" class="sidebar-menu-link {{ request()->is('account/orders*') ? 'active' : '' }}">
                <i class="fa-solid fa-bag-shopping sidebar-menu-icon"></i>
                <span>Orders</span>
            </a>
        </li>
        <li class="sidebar-menu-item">
            <a href="{{ Route::has('cart.index') ? route('cart.index') : url('/cart') }}" class="sidebar-menu-link {{ request()->is('cart*') ? 'active' : '' }}">
                <i class="fa-solid fa-cart-shopping sidebar-menu-icon"></i>
                <span>Cart</span>
            </a>
        </li>
        <li class="sidebar-menu-item">
            <a href="{{ route('wishlist.index') }}" class="sidebar-menu-link {{ request()->is('wishlist*') ? 'active' : '' }}">
                <i class="fa-solid fa-heart sidebar-menu-icon"></i>
                <span>Wishlist</span>
            </a>
        </li>
        <li class="sidebar-menu-item">
            <a href="{{ route('order.track') }}" class="sidebar-menu-link {{ Route::currentRouteName() == 'order.track' ? 'active' : '' }}">
                <i class="fa-solid fa-truck-fast sidebar-menu-icon"></i>
                <span>Track Order</span>
            </a>
        </li>
        <li class="sidebar-menu-item">
            <a href="{{ route('account.edit') }}" class="sidebar-menu-link {{ Route::currentRouteName() == 'account.edit' ? 'active' : '' }}">
                <i class="fa-solid fa-gear sidebar-menu-icon"></i>
                <span>Settings</span>
            </a>
        </li>
    </ul>

    @auth
        <a class="logout-link" href="{{ route('logout') }}"
            onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();" style="margin-top: 15px; display: flex; align-items: center; color: #dc3545; text-decoration: none; font-weight: 600; padding: 10px 15px;">
            <i class="fa-solid fa-arrow-right-from-bracket sidebar-menu-icon"></i>
            <span>{{ __('Logout') }}</span>
        </a>

        <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    @endauth
</div>
