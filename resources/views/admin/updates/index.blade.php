@extends('layouts.master')

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
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">System Updates</h3>
            <a href="{{ route('admin.updates.index', ['force_check' => 1]) }}" class="btn btn-outline-primary">
                <i class="fa-solid fa-arrow-rotate-right"></i> Refresh update status
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        @if(!empty($updateAccessMessage))
            <div class="alert alert-warning">
                {{ $updateAccessMessage }}
            </div>
        @endif

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Current Installation</h5>
                        <p class="mb-1">
                            <strong>Version:</strong> {{ $currentVersion }}
                        </p>
                        <p class="mb-1">
                            <strong>Update access:</strong>
                            <span class="badge bg-{{ $badgeColor }}">
                                {{ $statusLabel }}
                            </span>
                        </p>
                        <p class="mb-1">
                            <strong>Remaining days:</strong>
                            {{ $updateStatus['remaining_days'] ?? 0 }}
                        </p>
                        <p class="mb-0">
                            <small class="text-muted">License synced {{ $licenseStatus['last_synced'] ?? 'N/A' }}</small>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                @if($manifest)
                    <div class="card shadow-sm border-primary">
                        <div class="card-body">
                            <h5 class="card-title text-primary">
                                Version {{ $manifest['version'] }} is ready
                            </h5>
                            <p class="small text-muted">
                                Release channel: {{ $manifest['release_channel'] ?? 'stable' }}
                            </p>
                            <p>{{ $manifest['notes'] ?? 'No release notes provided.' }}</p>

                            <div class="mb-2">
                                <strong>Requirements:</strong>
                                <ul class="mb-0">
                                    @foreach($manifest['requirements'] ?? [] as $key => $value)
                                        <li>{{ ucfirst($key) }}: {{ $value }}</li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="mb-3">
                                <strong>Commands:</strong>
                                <ul class="mb-0">
                                    @foreach($manifest['commands'] ?? [] as $command)
                                        <li><code>{{ $command }}</code></li>
                                    @endforeach
                                </ul>
                            </div>

                            @if($canApplyUpdates)
                                <form action="{{ route('admin.updates.apply') }}" method="POST">
                                    @csrf
                                    <button class="btn btn-primary w-100">
                                        <i class="fa-solid fa-cloud-arrow-down"></i> Update Now
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-secondary w-100" type="button" disabled>
                                    Update unavailable
                                </button>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="card shadow-sm border-secondary">
                        <div class="card-body">
                            <h5 class="card-title">No update available</h5>
                            <p class="text-muted">
                                Your installation is up to date or your update window has expired.
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="update-details mb-4">
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
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h5 class="card-title mb-1">Latest Release: Version {{ $latestReleaseSummary['version'] ?? 'N/A' }}</h5>
                                <span class="badge bg-light text-primary border">{{ ucfirst($latestReleaseSummary['release_channel'] ?? 'stable') }}</span>
                                @if(!empty($latestReleaseSummary['published_at']))
                                    <small class="text-muted ms-2">Published {{ \Illuminate\Support\Carbon::parse($latestReleaseSummary['published_at'])->diffForHumans() }}</small>
                                @endif
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.updates.index', ['force_check' => 1]) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-sync-alt me-1"></i> Refresh
                                </a>
                                <a href="{{ route('admin.updates.index') }}#update-history" class="btn btn-sm btn-primary">
                                    <i class="fas fa-clock-rotate-left me-1"></i> History
                                </a>
                            </div>
                        </div>

                        @if($promoSnippet)
                            <p class="text-muted mb-3">{{ $promoSnippet }}</p>
                        @endif

                        @if(!empty($latestReleaseSummary['promo_content']))
                            <div class="mb-3">
                                {!! $latestReleaseSummary['promo_content'] !!}
                            </div>
                        @endif

                        @if(!empty($releaseNotesLines))
                            <div class="mb-0">
                                <strong>Release Notes:</strong>
                                <ul class="mb-0 ps-3">
                                    @foreach($releaseNotesLines as $line)
                                        <li>{{ $line }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <div class="card shadow-sm">
            <div class="card-header" id="update-history">
                <h5 class="mb-0">Update History</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light">
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
                                    <td>{{ $entry->version }}</td>
                                    <td>
                                        <span class="badge bg-{{ $entry->status === 'success' ? 'success' : ($entry->status === 'failed' ? 'danger' : 'secondary') }}">
                                            {{ ucfirst($entry->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $entry->updated_at?->format('Y-m-d H:i') }}</td>
                                    <td>{{ $entry->notes }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        No update history yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
