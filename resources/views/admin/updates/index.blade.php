@extends('layouts.master')

@section('styles')
<style>
    :root {
        --primary: #197A94;
        --primary-gradient: linear-gradient(135deg, #197A94 0%, #0d5c70 100%);
        --primary-hover: #135d71;
        --primary-light: rgba(25, 122, 148, 0.08);
        --success: #10b981;
        --info: #06b6d4;
        --warning: #f59e0b;
        --danger: #ef4444;
        --dark-slate: #1e293b;
        --text-main: #334155;
        --text-muted: #64748b;
        --bg-glass: rgba(255, 255, 255, 0.95);
        --border-glass: rgba(226, 232, 240, 0.9);
        --shadow-premium: 0 10px 30px -5px rgba(0, 0, 0, 0.05), 0 4px 12px -2px rgba(0, 0, 0, 0.03);
        --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .container-fluid {
        padding: 30px;
        background-color: #f8fafc;
        min-height: 100vh;
    }

    .page-title {
        color: var(--dark-slate);
        font-weight: 800;
        font-size: 24px;
        letter-spacing: -0.5px;
    }

    /* Premium Cards */
    .card {
        background: var(--bg-glass);
        border: 1px solid var(--border-glass);
        border-radius: 20px;
        box-shadow: var(--shadow-premium);
        transition: var(--transition-smooth);
        margin-bottom: 30px;
        overflow: hidden;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.08);
    }

    .card-header {
        background: transparent !important;
        border-bottom: 1.5px solid #f1f5f9 !important;
        padding: 20px 25px !important;
    }

    .card-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--dark-slate);
    }

    .card-body {
        padding: 25px 30px;
    }

    /* Form Customization */
    .btn-action-primary {
        background: var(--primary-gradient);
        color: white;
        border: none;
        font-weight: 700;
        border-radius: 12px;
        padding: 12px 24px;
        transition: var(--transition-smooth);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
    }
    .btn-action-primary:hover {
        background: var(--primary-hover);
        transform: translateY(-1px);
        box-shadow: 0 6px 15px rgba(25, 122, 148, 0.25);
        color: white;
    }

    .btn-outline-custom {
        border: 1.5px solid #e2e8f0;
        background: white;
        color: var(--text-main);
        font-weight: 600;
        border-radius: 12px;
        padding: 10px 20px;
        transition: var(--transition-smooth);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-outline-custom:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: var(--primary-light);
    }

    /* List custom requirements */
    .req-list {
        list-style: none;
        padding-left: 0;
        margin-bottom: 0;
    }
    .req-list li {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 0;
        font-size: 14px;
        color: var(--text-main);
    }
    .req-list li i {
        color: var(--success);
    }

    /* History table styles */
    .table {
        margin-bottom: 0;
    }
    .table th {
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        font-size: 12px;
        border-bottom: 1.5px solid #f1f5f9;
        padding: 16px 20px;
    }
    .table td {
        padding: 16px 20px;
        font-size: 14px;
        color: var(--text-main);
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
</style>
@endsection

@section('content')
    <div class="container-fluid">
        @php
            $updateStatus = $licenseStatus['update_status'] ?? [];
            $supportStatusInfo = $licenseStatus['support_status'] ?? [];
            $updateStatusKey = $updateStatus['status'] ?? null;
            $supportStatusKey = $supportStatusInfo['status'] ?? null;
            $badgeColor = match ($updateStatusKey ?? 'unknown') {
                'active' => 'success',
                'pending' => 'warning',
                'expired' => 'danger',
                default => 'secondary',
            };
            $statusLabel = ucfirst($updateStatusKey ?? 'unknown');
        @endphp

        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="page-title mb-0">System Updates</h5>
                </div>
                <div>
                    <a href="{{ route('admin.updates.index', ['force_check' => 1]) }}" class="btn-outline-custom">
                        <i class="fa-solid fa-arrow-rotate-right"></i> Refresh status
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 rounded-4 py-3 mb-4">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger border-0 rounded-4 py-3 mb-4">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            </div>
        @endif
        @if(!empty($updateAccessMessage))
            <div class="alert alert-warning border-0 rounded-4 py-3 mb-4">
                <i class="fas fa-exclamation-triangle me-2"></i> {{ $updateAccessMessage }}
            </div>
        @endif

        <div class="row">
            <!-- Current version info -->
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="fas fa-desktop text-primary me-2"></i>Current Installation</h5>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                <span class="text-muted font-weight-medium">Product Version</span>
                                <strong class="fs-5 text-dark">v{{ $currentVersion }}</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                <span class="text-muted font-weight-medium">Update Access</span>
                                <span class="badge bg-{{ $badgeColor }} py-2 px-3 rounded-pill">
                                    {{ $statusLabel }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted font-weight-medium">Remaining Days</span>
                                <strong class="text-dark">{{ $updateStatus['remaining_days'] ?? 0 }} Days</strong>
                            </div>
                        </div>
                        <div class="pt-4 border-top mt-4">
                            <span class="text-muted"><i class="far fa-clock me-1"></i> License synchronized: {{ $licenseStatus['last_synced'] ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ready update section -->
            <div class="col-lg-6">
                @if($manifest)
                    <div class="card h-100 border-primary">
                        <div class="card-header">
                            <h5 class="card-title mb-0 text-primary">
                                <i class="fas fa-cloud-arrow-down me-2"></i>Version {{ $manifest['version'] }} Available
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <span class="badge bg-light text-primary border py-2 px-3 rounded-pill">{{ ucfirst($manifest['release_channel'] ?? 'stable') }}</span>
                            </div>
                            <p class="text-muted mb-4">{{ $manifest['notes'] ?? 'No release notes provided.' }}</p>

                            @if(!empty($manifest['requirements']))
                                <div class="mb-4">
                                    <h6 class="font-weight-bold text-dark mb-2">Requirements:</h6>
                                    <ul class="req-list">
                                        @foreach($manifest['requirements'] as $key => $value)
                                            <li><i class="fas fa-check-circle"></i> {{ ucfirst($key) }}: <strong>{{ $value }}</strong></li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if($canApplyUpdates)
                                <form action="{{ route('admin.updates.apply') }}" method="POST">
                                    @csrf
                                    <button class="btn-action-primary w-100 py-3">
                                        <i class="fa-solid fa-cloud-arrow-down"></i> Apply Update Now
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-secondary w-100 py-3 rounded-3" type="button" disabled>
                                    Update Window Suspended / Expired
                                </button>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="fas fa-cloud me-2"></i>Update Check</h5>
                        </div>
                        <div class="card-body d-flex flex-column align-items-center justify-content-center py-5">
                            <div class="bg-light p-4 rounded-circle mb-3">
                                <i class="fas fa-circle-check text-success fs-2"></i>
                            </div>
                            <h6 class="font-weight-bold text-dark mb-1">Your system is up-to-date</h6>
                            <p class="text-muted text-center mb-0">No updates are currently pending verification.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Latest Release Summary -->
        <div class="row">
            <div class="col-12 mt-4">
                @if(!empty($latestReleaseSummary))
                    @php
                        $promoSnippet = null;
                        if (!empty($latestReleaseSummary['promo_content'])) {
                            $promoSnippet = \Illuminate\Support\Str::limit(strip_tags($latestReleaseSummary['promo_content']), 200);
                        } elseif (!empty($latestReleaseSummary['notes'])) {
                            $promoSnippet = \Illuminate\Support\Str::limit(strip_tags($latestReleaseSummary['notes']), 200);
                        }
                        $releaseNotesLines = [];
                        if (!empty($latestReleaseSummary['notes'])) {
                            $releaseNotesLines = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $latestReleaseSummary['notes'])));
                        }
                    @endphp
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-bullhorn text-warning me-2"></i>Latest Release Summary (v{{ $latestReleaseSummary['version'] ?? 'N/A' }})
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="badge bg-light text-primary border py-2 px-3 rounded-pill">{{ ucfirst($latestReleaseSummary['release_channel'] ?? 'stable') }}</span>
                                @if(!empty($latestReleaseSummary['published_at']))
                                    <small class="text-muted">Published {{ \Illuminate\Support\Carbon::parse($latestReleaseSummary['published_at'])->diffForHumans() }}</small>
                                @endif
                            </div>

                            @if($promoSnippet)
                                <p class="text-muted mb-4">{{ $promoSnippet }}</p>
                            @endif

                            @if(!empty($latestReleaseSummary['promo_content']))
                                <div class="mb-4 bg-light p-4 rounded-4">
                                    {!! $latestReleaseSummary['promo_content'] !!}
                                </div>
                            @endif

                            @if(!empty($releaseNotesLines))
                                <div>
                                    <h6 class="font-weight-bold text-dark mb-2">Release Notes:</h6>
                                    <ul class="req-list">
                                        @foreach($releaseNotesLines as $line)
                                            <li><i class="fas fa-info-circle text-primary"></i> {{ $line }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- History table -->
        <div class="row">
            <div class="col-12 mt-4">
                <div class="card" id="update-history">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="fas fa-history text-secondary me-2"></i>Update History Log</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Version</th>
                                        <th>Status</th>
                                        <th>Applied At</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($history as $entry)
                                        <tr>
                                            <td class="font-weight-bold">v{{ $entry->version }}</td>
                                            <td>
                                                <span class="badge bg-{{ $entry->status === 'success' ? 'success' : ($entry->status === 'failed' ? 'danger' : 'secondary') }} py-1 px-3 rounded-pill">
                                                    {{ ucfirst($entry->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $entry->updated_at?->format('Y-m-d H:i') }}</td>
                                            <td class="text-muted">{{ $entry->notes }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5 text-muted">
                                                <i class="fas fa-box-open fs-3 mb-2 d-block"></i> No update history logged yet.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
