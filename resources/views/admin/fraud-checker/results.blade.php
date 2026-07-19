@extends('layouts.master')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    #fraud-results-page {
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        color: #1e293b;
        background-color: #f8fafc;
    }

    .premium-panel-header {
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 24px;
        margin-bottom: 32px;
    }

    .premium-panel-header h1 {
        font-size: 1.75rem;
        font-weight: 800;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .premium-panel-header h1 i {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .premium-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    /* Table */
    .table-premium {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 0;
    }

    .table-premium th {
        font-weight: 700;
        color: #475569;
        background-color: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
        padding: 16px 20px;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        white-space: nowrap;
    }

    .table-premium td {
        padding: 16px 20px;
        border-bottom: 1px solid #e2e8f0;
        color: #0f172a;
        font-size: 0.95rem;
        vertical-align: middle;
    }

    .table-premium tbody tr {
        transition: background 0.2s ease;
    }

    .table-premium tbody tr:hover {
        background-color: #f1f5f9;
    }

    .table-premium tbody tr:last-child td {
        border-bottom: none;
    }

    /* Risk score bar */
    .risk-bar-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .risk-bar {
        flex: 1;
        height: 6px;
        border-radius: 9999px;
        background: #e2e8f0;
        overflow: hidden;
        min-width: 60px;
    }

    .risk-bar-fill {
        height: 100%;
        border-radius: 9999px;
        transition: width 0.4s ease;
    }

    .risk-fill-high { background: linear-gradient(to right, #ef4444, #dc2626); }
    .risk-fill-medium { background: linear-gradient(to right, #f59e0b, #d97706); }
    .risk-fill-low { background: linear-gradient(to right, #3b82f6, #2563eb); }
    .risk-fill-very-low { background: linear-gradient(to right, #10b981, #059669); }
    .risk-fill-unknown { background: #94a3b8; }

    .risk-score-num {
        font-weight: 800;
        font-size: 0.9rem;
        min-width: 42px;
        text-align: right;
    }

    /* Badges */
    .badge-glow {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 11px;
        border-radius: 9999px;
        font-size: 0.74rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        white-space: nowrap;
    }

    .badge-glow-high    { background-color: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
    .badge-glow-medium  { background-color: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
    .badge-glow-low     { background-color: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .badge-glow-very-low{ background-color: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
    .badge-glow-inactive{ background-color: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }

    /* Buttons */
    .btn-premium {
        font-weight: 700;
        padding: 11px 22px;
        border-radius: 10px;
        border: none;
        transition: all 0.2s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
        text-decoration: none;
    }

    .btn-premium:hover {
        transform: translateY(-1px);
        text-decoration: none;
    }

    .btn-premium-outline {
        background: #ffffff;
        border: 2px solid #cbd5e1;
        color: #475569;
    }

    .btn-premium-outline:hover {
        border-color: #ef4444;
        color: #ef4444;
        background: #fff5f5;
    }

    .btn-action-sm {
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.775rem;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-decoration: none;
        transition: all 0.2s ease;
        cursor: pointer;
        border: none;
    }

    .btn-action-sm:hover { text-decoration: none; }

    .btn-action-details { background-color: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; }
    .btn-action-details:hover { background-color: #cbd5e1; color: #0f172a; }

    .btn-action-refresh { background-color: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
    .btn-action-refresh:hover { background-color: #fde68a; color: #92400e; }

    /* Delivery rate cell */
    .delivery-rate-cell {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 700;
    }

    .delivery-rate-cell .rate-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }
</style>
@endsection

@section('content')
<div id="fraud-results-page" class="container-fluid px-4 py-4">

    <!-- Header -->
    <div class="premium-panel-header">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h1>
                    <i class="fas fa-clipboard-list"></i>
                    Verification Results Archive
                </h1>
                <p class="text-muted mb-0">All fraud verification scan records with risk ratings and delivery histories.</p>
            </div>
            <a href="{{ route('admin.fraud-checker.index') }}" class="btn-premium btn-premium-outline">
                <i class="fas fa-arrow-left"></i> Back to Guard Center
            </a>
        </div>
    </div>

    <!-- Results Table -->
    <div class="premium-card">
        <div class="table-responsive">
            <table class="table-premium">
                <thead>
                    <tr>
                        <th>Phone Number</th>
                        <th>Risk Category</th>
                        <th>Risk Score</th>
                        <th>Delivery Rate</th>
                        <th>Total Parcels</th>
                        <th>Last Verified</th>
                        <th class="text-end">Operations</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($results as $result)
                        @php
                            $riskLevel = $result->risk_level ?? 'unknown';
                            $score = (int) ($result->risk_score ?? 0);

                            $badgeClass = match($riskLevel) {
                                'high'     => 'badge-glow-high',
                                'medium'   => 'badge-glow-medium',
                                'low'      => 'badge-glow-low',
                                'very_low' => 'badge-glow-very-low',
                                default    => 'badge-glow-inactive',
                            };
                            $fillClass = match($riskLevel) {
                                'high'     => 'risk-fill-high',
                                'medium'   => 'risk-fill-medium',
                                'low'      => 'risk-fill-low',
                                'very_low' => 'risk-fill-very-low',
                                default    => 'risk-fill-unknown',
                            };
                            $scoreColor = match($riskLevel) {
                                'high'     => '#dc2626',
                                'medium'   => '#b45309',
                                'low'      => '#1d4ed8',
                                'very_low' => '#15803d',
                                default    => '#64748b',
                            };
                            $rate = (float) ($result->delivery_success_rate ?? 0);
                            $rateDotColor = $rate >= 80 ? '#10b981' : ($rate >= 50 ? '#f59e0b' : '#ef4444');
                        @endphp
                        <tr>
                            <td>
                                <span style="font-weight: 700; font-size: 1rem;">{{ $result->phone }}</span>
                            </td>
                            <td>
                                <span class="badge-glow {{ $badgeClass }}">
                                    {{ $result->risk_level_display }}
                                </span>
                            </td>
                            <td>
                                <div class="risk-bar-wrap">
                                    <div class="risk-bar">
                                        <div class="risk-bar-fill {{ $fillClass }}" style="width: {{ $score }}%;"></div>
                                    </div>
                                    <span class="risk-score-num" style="color: {{ $scoreColor }};">{{ $score }}/100</span>
                                </div>
                            </td>
                            <td>
                                <div class="delivery-rate-cell">
                                    <span class="rate-dot" style="background:{{ $rateDotColor }};"></span>
                                    <span style="color: #0f172a;">{{ $rate }}%</span>
                                </div>
                            </td>
                            <td>
                                <span style="font-weight: 600; color: #475569;">{{ $result->total_parcels }}</span>
                            </td>
                            <td>
                                <span style="font-size: 0.825rem; color: #64748b;">{{ $result->formatted_last_checked }}</span>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    <a href="{{ route('admin.fraud-checker.result-details', $result->id) }}" class="btn-action-sm btn-action-details">
                                        <i class="fas fa-eye"></i> Details
                                    </a>
                                    <button class="btn-action-sm btn-action-refresh refresh-result" data-id="{{ $result->id }}">
                                        <i class="fas fa-sync-alt"></i> Refresh
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="fas fa-clipboard-list fa-3x mb-3" style="color: #cbd5e1; display: block;"></i>
                                <span style="font-size: 1.05rem; font-weight: 600; color: #0f172a;">No verification results found.</span>
                                <div style="font-size: 0.85rem; margin-top: 4px;">Run a phone scan from the Fraud Guard Center to generate records.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($results->hasPages())
            <div class="px-4 py-3 border-top" style="border-color: #e2e8f0;">
                {{ $results->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.refresh-result').click(function() {
            const id = $(this).data('id');
            const btn = $(this);

            if (confirm('Are you sure you want to refresh this result? This will call the APIs again.')) {
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Refreshing...');

                $.get(`/admin/fraud-checker/results/${id}/refresh`, function(response) {
                    if (response.success) {
                        alert('Result refreshed successfully!');
                        location.reload();
                    } else {
                        alert('Refresh failed: ' + response.message);
                    }
                }).fail(function() {
                    alert('Refresh failed');
                }).always(function() {
                    btn.prop('disabled', false).html('<i class="fas fa-sync-alt"></i> Refresh');
                });
            }
        });
    });
</script>
@endsection
