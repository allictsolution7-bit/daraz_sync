@extends('frontend.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('new/user.styles.css') }}">
    <style>
        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
        }

        .profile-image {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-right: 20px;
            object-fit: cover;
            border: 3px solid #e2e8f0;
        }

        .profile-name {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 5px;
        }

        .profile-email {
            color: #64748b;
        }

        .profile-actions {
            margin-top: 25px;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .edit-profile-btn {
            display: inline-flex;
            align-items: center;
            background-color: #ff6a00;
            color: white !important;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .edit-profile-btn:hover {
            background-color: #e05d00;
            color: white !important;
        }

        .portal-btn {
            display: inline-flex;
            align-items: center;
            background-color: #4f46e5;
            color: white !important;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .portal-btn:hover {
            background-color: #4338ca;
            color: white !important;
        }

        .settings-title {
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e2e8f0;
        }

        .settings-options {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .settings-link {
            color: #334155;
            text-decoration: none;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            font-weight: 500;
        }

        .settings-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .settings-link:hover {
            color: #ff6a00;
        }
    </style>
@endsection

@section('content')
    <div class="base-container profile-container">
        @php
        $profileImageUrl = $user->profile_photo_path
            ? \Illuminate\Support\Facades\Storage::disk('public')->url($user->profile_photo_path)
            : asset('clientside/images/profile.png');
        @endphp
        <div class="profile-layout">
            <!-- Sidebar Menu -->
            @include('frontend.user.partials.sidebar')

            <!-- Main Content Card -->
            <div class="profile-card">
                <!-- Profile Section -->
                <div class="profile-info">
                    <div class="profile-header">
                        <img src="{{ $profileImageUrl }}" alt="{{ $user->name }}" class="profile-image">
                        <div class="profile-details">
                            <h1 class="profile-name">{{ $user->name }}</h1>
                            <p class="profile-email">{{ $user->email }}</p>
                        </div>
                    </div>
                    <div class="profile-data">
                        <div class="profile-item">
                            <strong class="profile-label">Phone:</strong>
                            <p class="profile-value">{{ $user->phone ?? 'Not provided' }}</p>
                        </div>
                        <div class="profile-item">
                            <strong class="profile-label">Address:</strong>
                            <p class="profile-value">{{ $user->address ?? 'Not provided' }}</p>
                        </div>

                        <!-- Delivery Locations Section -->
                        <div class="profile-item mt-4 pt-3 border-top" style="border-top: 1px solid #e2e8f0;">
                            <div class="d-flex justify-content-between align-items-center mb-3" style="display: flex; justify-content: space-between; align-items: center;">
                                <strong class="profile-label" style="font-size: 16px; color: #1e293b;"><i class="fa-solid fa-location-dot text-danger me-2"></i> Saved Delivery Locations:</strong>
                                <button type="button" class="btn btn-sm btn-outline-primary" style="background: #f8fafc; border: 1px solid #cbd5e1; color: #334155; padding: 6px 14px; border-radius: 6px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;" onclick="openLocationModal()">
                                    <i class="fa-solid fa-plus"></i> Add Location
                                </button>
                            </div>

                            @if(isset($locations) && $locations->count() > 0)
                                <div class="delivery-locations-grid" style="display: flex; flex-direction: column; gap: 10px; margin-top: 10px;">
                                    @foreach($locations as $loc)
                                        <div class="location-card" style="background: {{ $loc->is_default ? '#fff7ed' : '#f8fafc' }}; border: 1px solid {{ $loc->is_default ? '#fdba74' : '#e2e8f0' }}; padding: 12px 15px; border-radius: 8px; display: flex; justify-content: space-between; align-items: flex-start;">
                                            <div>
                                                <div style="display: flex; align-items: center; gap: 8px;">
                                                    <span style="font-weight: 700; color: #1e293b; font-size: 14px;">{{ $loc->title }}</span>
                                                    @if($loc->is_default)
                                                        <span style="background: #ff6a00; color: #fff; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 10px;">DEFAULT</span>
                                                    @endif
                                                </div>
                                                <p style="margin: 4px 0 0 0; color: #475569; font-size: 13px; line-height: 1.4;">{{ $loc->address }}</p>
                                                @if($loc->district || $loc->division || $loc->upazila || $loc->post_code)
                                                    <div style="display: flex; flex-wrap: wrap; gap: 4px; margin-top: 4px;">
                                                        @if($loc->upazila)<span style="background: #e2e8f0; color: #334155; font-size: 11px; padding: 1px 6px; border-radius: 4px;">{{ $loc->upazila }}</span>@endif
                                                        @if($loc->district)<span style="background: #e2e8f0; color: #334155; font-size: 11px; padding: 1px 6px; border-radius: 4px;">{{ $loc->district }}</span>@endif
                                                        @if($loc->division)<span style="background: #e2e8f0; color: #334155; font-size: 11px; padding: 1px 6px; border-radius: 4px;">{{ $loc->division }}</span>@endif
                                                        @if($loc->post_code)<span style="background: #e2e8f0; color: #334155; font-size: 11px; padding: 1px 6px; border-radius: 4px;">Post: {{ $loc->post_code }}</span>@endif
                                                    </div>
                                                @endif
                                                @if($loc->latitude && $loc->longitude)
                                                    <small style="color: #64748b; font-size: 11px; display: block; margin-top: 2px;">
                                                        <i class="fa-solid fa-crosshairs text-success me-1"></i> GPS: {{ $loc->latitude }}, {{ $loc->longitude }}
                                                    </small>
                                                @endif
                                            </div>
                                            <div style="display: flex; gap: 6px;">
                                                @if(!$loc->is_default)
                                                    <form action="{{ route('account.locations.default', $loc->id) }}" method="POST" style="margin:0;">
                                                        @csrf
                                                        <button type="submit" title="Set Default" style="background: none; border: none; color: #64748b; cursor: pointer; padding: 4px;"><i class="fa-regular fa-star"></i></button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('account.locations.delete', $loc->id) }}" method="POST" style="margin:0;" onsubmit="return confirm('Delete this location?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" title="Delete Location" style="background: none; border: none; color: #ef4444; cursor: pointer; padding: 4px;"><i class="fa-solid fa-trash-can"></i></button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted" style="color: #94a3b8; font-size: 13px; margin-top: 6px;">No saved delivery locations yet. Add one to speed up checkout!</p>
                            @endif
                        </div>
                        <div class="profile-actions">
                            @auth
                                @php
                                    $u = auth()->user();
                                    $isVendorUser = ($u->role === 'vendor' || (method_exists($u, 'isVendor') && $u->isVendor()) || (method_exists($u, 'hasRole') && $u->hasRole('vendor')) || (isset($u->user_type) && $u->user_type === 'vendor') || (isset($u->type) && $u->type === 'vendor'));
                                    $isAdminUser = !$isVendorUser && ($u->isAdmin() || $u->hasRole('admin') || $u->hasRole('super_admin') || $u->hasRole('super admin') || $u->hasRole('manager') || $u->can('access admin') || $u->id == 1 || (isset($u->role) && in_array($u->role, ['admin', 'super_admin', 'manager'])));
                                @endphp

                                @if($isVendorUser)
                                    <a href="{{ Route::has('vendor.dashboard') ? route('vendor.dashboard') : url('/vendor/dashboard') }}" class="portal-btn" style="background: linear-gradient(135deg, #4f46e5, #3730a3); color: #fff; text-decoration: none; padding: 10px 18px; border-radius: 8px; font-weight: 600; display: inline-flex; align-items: center;">
                                        <i class="fa-solid fa-store" style="margin-right: 8px;"></i> Vendor Dashboard
                                    </a>
                                @elseif($isAdminUser)
                                    <a href="{{ Route::has('admin.dashboard') ? route('admin.dashboard') : url('/admin') }}" class="portal-btn" style="background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; text-decoration: none; padding: 10px 18px; border-radius: 8px; font-weight: 600; display: inline-flex; align-items: center;">
                                        <i class="fa-solid fa-gauge-high" style="margin-right: 8px;"></i> Admin Dashboard (/admin)
                                    </a>
                                @endif
                            @endauth
                            <a href="{{ route('account.edit') }}" class="edit-profile-btn">
                                <i class="fa-solid fa-pen-to-square" style="margin-right: 8px;"></i> Edit Profile
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Settings Section -->
                <div class="profile-settings">
                    <h2 class="settings-title">Account Quick Actions</h2>
                    <div class="settings-options">
                        <div class="settings-item">
                            <a href="{{ route('account.edit') }}" class="settings-link">
                                <i class="fa-solid fa-lock text-warning"></i> Change Security Password
                            </a>
                        </div>
                        <div class="settings-item">
                            <a href="{{ route('account.orders') }}" class="settings-link">
                                <i class="fa-solid fa-bag-shopping text-primary"></i> View Order History
                            </a>
                        </div>
                        <div class="settings-item">
                            <a href="{{ route('order.track') }}" class="settings-link">
                                <i class="fa-solid fa-truck-fast text-success"></i> Track Live Shipment
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Delivery Location Modal -->
    <div id="locationModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center;">
        <div style="background: #ffffff; width: 100%; max-width: 480px; border-radius: 12px; padding: 24px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); margin: 15px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; padding-bottom: 10px; border-bottom: 1px solid #e2e8f0;">
                <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #0f172a;"><i class="fa-solid fa-map-location-dot text-danger me-2"></i> Add Delivery Location</h3>
                <button type="button" onclick="closeLocationModal()" style="background: none; border: none; font-size: 20px; color: #64748b; cursor: pointer;">&times;</button>
            </div>

            <form action="{{ route('account.locations.store') }}" method="POST" id="locationForm">
                @csrf
                <div style="margin-bottom: 14px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 6px;">Location Type / Title</label>
                    <select name="title_select" id="locationTitleSelect" onchange="toggleOtherTitleInput(this)" required style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; background: #ffffff;">
                        <option value="Home">Home</option>
                        <option value="Office">Office</option>
                        <option value="Warehouse">Warehouse</option>
                        <option value="Other">Other</option>
                    </select>
                    <input type="text" name="custom_title" id="customTitleInput" placeholder="Enter custom location name (e.g. Warehouse)" style="display: none; width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; margin-top: 8px;">
                    <input type="hidden" name="title" id="finalLocationTitle" value="Home">
                </div>

                <div style="margin-bottom: 14px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <label style="font-size: 13px; font-weight: 600; color: #334155;">Detailed Address</label>
                        <button type="button" onclick="useCurrentLiveLocation()" id="detectGeoBtn" style="background: #eff6ff; border: 1px solid #93c5fd; color: #2563eb; font-size: 12px; font-weight: 600; padding: 4px 10px; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                            <i class="fa-solid fa-location-crosshairs"></i> Use Current Live Location
                        </button>
                    </div>
                    <textarea name="address" id="locationAddress" rows="3" placeholder="Enter complete address, house, road, area" required style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; resize: vertical;"></textarea>
                    <small id="geoStatus" style="color: #059669; font-size: 11px; display: none; margin-top: 4px;"><i class="fa-solid fa-check-circle"></i> Live location detected!</small>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 14px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 4px;">Division</label>
                        <input type="text" name="division" id="locationDivision" placeholder="e.g. Dhaka Division" style="width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 4px;">District / City</label>
                        <input type="text" name="district" id="locationDistrict" placeholder="e.g. Dhaka District" style="width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 14px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 4px;">Upazila / Thana / Sub-district</label>
                        <input type="text" name="upazila" id="locationUpazila" placeholder="e.g. Mohammadpur" style="width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 4px;">Post Code</label>
                        <input type="text" name="post_code" id="locationPostCode" placeholder="e.g. 1207" style="width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
                    </div>
                </div>

                <input type="hidden" name="city" id="locationCity">
                <input type="hidden" name="latitude" id="locationLat">
                <input type="hidden" name="longitude" id="locationLng">

                <div style="margin-bottom: 18px; display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" name="is_default" value="1" id="is_default_check">
                    <label for="is_default_check" style="font-size: 13px; color: #475569; cursor: pointer;">Set as primary default delivery location</label>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" onclick="closeLocationModal()" style="background: #f1f5f9; border: 1px solid #cbd5e1; color: #475569; padding: 8px 16px; border-radius: 6px; font-weight: 600; cursor: pointer;">Cancel</button>
                    <button type="submit" style="background: #ff6a00; border: none; color: #ffffff; padding: 8px 20px; border-radius: 6px; font-weight: 600; cursor: pointer;">Save Location</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleOtherTitleInput(selectEl) {
            const customInput = document.getElementById('customTitleInput');
            const finalTitle = document.getElementById('finalLocationTitle');
            if (selectEl.value === 'Other') {
                customInput.style.display = 'block';
                customInput.required = true;
                finalTitle.value = customInput.value || 'Other';
            } else {
                customInput.style.display = 'none';
                customInput.required = false;
                finalTitle.value = selectEl.value;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const customInput = document.getElementById('customTitleInput');
            if (customInput) {
                customInput.addEventListener('input', function() {
                    document.getElementById('finalLocationTitle').value = this.value || 'Other';
                });
            }
            const form = document.getElementById('locationForm');
            if (form) {
                form.addEventListener('submit', function() {
                    const select = document.getElementById('locationTitleSelect');
                    const custom = document.getElementById('customTitleInput');
                    const finalTitle = document.getElementById('finalLocationTitle');
                    if (select.value === 'Other') {
                        finalTitle.value = custom.value.trim() || 'Other';
                    } else {
                        finalTitle.value = select.value;
                    }
                });
            }
        });

        function openLocationModal() {
            document.getElementById('locationModal').style.display = 'flex';
        }

        function closeLocationModal() {
            document.getElementById('locationModal').style.display = 'none';
        }

        function useCurrentLiveLocation() {
            const btn = document.getElementById('detectGeoBtn');
            const status = document.getElementById('geoStatus');
            
            if (!navigator.geolocation) {
                alert('Geolocation is not supported by your browser.');
                return;
            }

            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Detecting...';
            btn.disabled = true;

            const handleSuccess = function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                document.getElementById('locationLat').value = lat;
                document.getElementById('locationLng').value = lng;

                const controller = new AbortController();
                const timeoutId = setTimeout(() => controller.abort(), 7000);

                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`, { signal: controller.signal })
                    .then(res => res.json())
                    .then(data => {
                        clearTimeout(timeoutId);
                        btn.innerHTML = '<i class="fa-solid fa-location-crosshairs"></i> Use Current Live Location';
                        btn.disabled = false;
                        
                        if (data && data.display_name) {
                            document.getElementById('locationAddress').value = data.display_name;
                            if (data.address) {
                                const addr = data.address;
                                document.getElementById('locationDivision').value = addr.state || addr.region || '';
                                document.getElementById('locationDistrict').value = addr.state_district || addr.district || addr.city || addr.county || '';
                                document.getElementById('locationUpazila').value = addr.suburb || addr.subdistrict || addr.neighbourhood || addr.city_district || addr.town || '';
                                document.getElementById('locationPostCode').value = addr.postcode || '';
                                document.getElementById('locationCity').value = addr.city || addr.town || addr.district || '';
                            }
                        } else {
                            document.getElementById('locationAddress').value = `GPS Location (${lat.toFixed(5)}, ${lng.toFixed(5)})`;
                        }

                        status.style.display = 'block';
                        status.innerHTML = `<i class="fa-solid fa-check-circle"></i> Live exact location detected (${lat.toFixed(5)}, ${lng.toFixed(5)})`;
                    })
                    .catch(() => {
                        clearTimeout(timeoutId);
                        btn.innerHTML = '<i class="fa-solid fa-location-crosshairs"></i> Use Current Live Location';
                        btn.disabled = false;
                        document.getElementById('locationAddress').value = `GPS Location (${lat.toFixed(5)}, ${lng.toFixed(5)})`;
                        status.style.display = 'block';
                        status.innerHTML = `<i class="fa-solid fa-check-circle"></i> Live location coordinates detected (${lat.toFixed(5)}, ${lng.toFixed(5)})`;
                    });
            };

            const handleError = function(err) {
                btn.innerHTML = '<i class="fa-solid fa-location-crosshairs"></i> Use Current Live Location';
                btn.disabled = false;
                alert('Unable to retrieve exact location: ' + err.message);
            };

            navigator.geolocation.getCurrentPosition(
                handleSuccess,
                handleError,
                { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
            );
        }
    </script>
@endsection
