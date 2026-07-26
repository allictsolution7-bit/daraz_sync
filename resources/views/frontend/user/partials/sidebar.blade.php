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
            @php
                $u = auth()->user();
                $isVendorUser = ($u->role === 'vendor' || (method_exists($u, 'isVendor') && $u->isVendor()) || (method_exists($u, 'hasRole') && $u->hasRole('vendor')) || (isset($u->user_type) && $u->user_type === 'vendor') || (isset($u->type) && $u->type === 'vendor'));
                $isAdminUser = !$isVendorUser && ($u->isAdmin() || $u->hasRole('admin') || $u->hasRole('super_admin') || $u->hasRole('super admin') || $u->hasRole('manager') || $u->can('access admin') || $u->id == 1 || (isset($u->role) && in_array($u->role, ['admin', 'super_admin', 'manager'])));
            @endphp

            @if($isVendorUser)
                <li class="sidebar-menu-item" style="margin-bottom: 12px;">
                    <a href="{{ Route::has('vendor.dashboard') ? route('vendor.dashboard') : url('/vendor/dashboard') }}" 
                       class="sidebar-menu-link text-white shadow-sm" 
                       style="background: linear-gradient(135deg, #4f46e5, #3730a3) !important; color: #ffffff !important; font-weight: 700; border-radius: 8px; padding: 10px 14px; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                        <i class="fa-solid fa-store text-white" style="font-size: 16px; width: 20px; text-align: center;"></i>
                        <span>Vendor Dashboard</span>
                    </a>
                </li>
            @elseif($isAdminUser)
                <li class="sidebar-menu-item" style="margin-bottom: 12px;">
                    <a href="{{ url('/admin') }}" 
                       class="sidebar-menu-link text-white shadow-sm" 
                       style="background: linear-gradient(135deg, #2563eb, #1d4ed8) !important; color: #ffffff !important; font-weight: 700; border-radius: 8px; padding: 10px 14px; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                        <i class="fa-solid fa-gauge-high text-white" style="font-size: 16px; width: 20px; text-align: center;"></i>
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
