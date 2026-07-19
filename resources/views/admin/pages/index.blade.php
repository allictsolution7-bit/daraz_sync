@extends('layouts.master')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        #pages-dashboard {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            color: #1e293b;
            background-color: #f8fafc;
        }

        .dashboard-header {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border-radius: 16px;
            padding: 32px;
            color: white;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.15), 0 8px 10px -6px rgba(15, 23, 42, 0.15);
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }

        .dashboard-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, rgba(99, 102, 241, 0) 70%);
            border-radius: 50%;
        }

        .breadcrumb-custom {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            color: #94a3b8;
            margin-bottom: 12px;
            padding: 0;
            list-style: none;
        }

        .breadcrumb-custom a {
            color: #cbd5e1;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .breadcrumb-custom a:hover {
            color: #6366f1;
        }

        .breadcrumb-separator {
            color: #64748b;
        }

        .breadcrumb-active {
            color: #94a3b8;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: -0.025em;
            margin: 0;
        }

        .page-subtitle {
            color: #94a3b8;
            font-size: 0.95rem;
            margin-top: 6px;
            margin-bottom: 0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 20px -8px rgba(0, 0, 0, 0.1);
            border-color: #cbd5e1;
        }

        .stat-info h4 {
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            margin: 0 0 6px 0;
        }

        .stat-value {
            font-size: 1.85rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .icon-blue { background: #eff6ff; color: #3b82f6; }
        .icon-green { background: #f0fdf4; color: #22c55e; }
        .icon-purple { background: #faf5ff; color: #a855f7; }

        .main-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 40px;
        }

        .card-toolbar {
            padding: 24px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            background: #f8fafc;
        }

        .btn-add-page {
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 0.9rem;
            font-weight: 600;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 10px rgba(79, 70, 229, 0.25);
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-add-page:hover {
            box-shadow: 0 6px 14px rgba(79, 70, 229, 0.35);
            transform: translateY(-1px);
            color: white;
        }

        .table-container {
            padding: 24px;
        }

        /* Modern Table Styling */
        table.dataTable {
            border-collapse: collapse !important;
            width: 100% !important;
            margin: 0 !important;
        }

        table.dataTable thead th {
            background: #0f172a !important;
            color: #f8fafc !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            font-size: 0.75rem !important;
            letter-spacing: 0.08em !important;
            padding: 16px 18px !important;
            border-bottom: none !important;
        }

        table.dataTable thead th:first-child {
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
        }

        table.dataTable thead th:last-child {
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        /* Custom elegant sorting indicators */
        table.dataTable thead .sorting,
        table.dataTable thead .sorting_asc,
        table.dataTable thead .sorting_desc {
            cursor: pointer !important;
            position: relative !important;
            padding-right: 32px !important;
        }

        table.dataTable thead .sorting,
        table.dataTable thead .sorting_asc,
        table.dataTable thead .sorting_desc,
        table.dataTable thead .sorting_asc_disabled,
        table.dataTable thead .sorting_desc_disabled {
            background-image: none !important;
        }

        table.dataTable thead .sorting::after {
            content: "\f0dc";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.7rem;
            color: rgba(248, 250, 252, 0.25);
            transition: color 0.2s ease;
        }

        table.dataTable thead .sorting:hover::after {
            color: rgba(248, 250, 252, 0.6);
        }

        table.dataTable thead .sorting_asc::after {
            content: "\f0de";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.75rem;
            color: #818cf8 !important;
        }

        table.dataTable thead .sorting_desc::after {
            content: "\f0dd";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.75rem;
            color: #818cf8 !important;
        }

        table.dataTable tbody td {
            padding: 16px !important;
            border-bottom: 1px solid #f1f5f9 !important;
            color: #334155 !important;
            font-size: 0.9rem !important;
            vertical-align: middle !important;
        }

        table.dataTable tbody tr:hover {
            background-color: #f8fafc !important;
        }

        /* Badges */
        .status-badge-active {
            background-color: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
            padding: 6px 12px;
            font-size: 0.75rem;
            font-weight: 700;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .status-badge-inactive {
            background-color: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
            padding: 6px 12px;
            font-size: 0.75rem;
            font-weight: 700;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        /* Copy link element */
        .slug-link {
            color: #6366f1;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .slug-link:hover {
            text-decoration: underline;
        }

        /* Action Buttons */
        .action-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-action {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background: white;
            color: #64748b;
            transition: all 0.2s ease;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-action:hover {
            background: #f1f5f9;
            color: #0f172a;
            border-color: #cbd5e1;
        }

        .btn-action-edit:hover {
            color: #4f46e5;
            background: #e0e7ff;
            border-color: #c7d2fe;
        }

        .btn-action-view:hover {
            color: #0ea5e9;
            background: #e0f2fe;
            border-color: #bae6fd;
        }

        .btn-action-copy:hover {
            color: #059669;
            background: #d1fae5;
            border-color: #a7f3d0;
        }

        .btn-action-delete:hover {
            color: #dc2626;
            background: #fee2e2;
            border-color: #fecaca;
        }

        /* DataTables controls customization */
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 20px;
        }

        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #cbd5e1 !important;
            border-radius: 8px !important;
            padding: 8px 14px !important;
            background: white !important;
            margin-left: 8px !important;
            font-family: inherit;
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            outline: none !important;
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1) !important;
        }

        .dt-buttons {
            margin-bottom: 15px;
        }

        button.dt-button {
            background: white !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 8px !important;
            padding: 8px 16px !important;
            color: #475569 !important;
            font-weight: 600 !important;
            transition: all 0.2s !important;
        }

        button.dt-button:hover {
            background: #f8fafc !important;
            border-color: #94a3b8 !important;
            color: #0f172a !important;
        }

        /* Beautiful Toast styling */
        .toast-premium {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(8px);
            color: white;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            transform: translateY(20px);
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .toast-premium.show {
            transform: translateY(0);
            opacity: 1;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid mt-4" id="pages-dashboard">
        <!-- Breadcrumb & Header Card -->
        <div class="dashboard-header d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="page-title">Custom Pages Manager</h1>
                <p class="page-subtitle">Publish, manage, and design content pages for your web application store front.</p>
            </div>
            <ul class="breadcrumb-custom m-0">
                <li><a href="{{ route('admin') }}"><i class="fa-solid fa-house"></i> Home</a></li>
                <li class="breadcrumb-separator"><i class="fa-solid fa-chevron-right" style="font-size: 0.7rem;"></i></li>
                <li class="breadcrumb-active">Pages</li>
            </ul>
        </div>

        <!-- Metrics Section -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-info">
                    <h4>Total Pages</h4>
                    <p class="stat-value">{{ count($pages) }}</p>
                </div>
                <div class="stat-icon icon-blue">
                    <i class="fa-solid fa-file-lines"></i>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <h4>Active Pages</h4>
                    <p class="stat-value">{{ $pages->where('status', 1)->count() }}</p>
                </div>
                <div class="stat-icon icon-green">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <h4>Drafts / Inactive</h4>
                    <p class="stat-value">{{ $pages->where('status', 0)->count() }}</p>
                </div>
                <div class="stat-icon icon-purple">
                    <i class="fa-solid fa-file-pen"></i>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="main-card">
            <div class="card-toolbar">
                <h5 class="m-0 font-weight-bold" style="font-size: 1.1rem; color: #0f172a;">All Available Pages</h5>
                <a href="{{ route('admin.pages.create') }}" class="btn-add-page">
                    <i class="fa-solid fa-plus"></i> Add New Page
                </a>
            </div>

            <div class="table-container">
                <table class="table" id="Pages">
                    <thead>
                    <tr>
                        <th>Page ID</th>
                        <th style="width: 70px;">Serial</th>
                        <th>Title</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Created Date</th>
                        <th style="width: 160px; text-align: center;">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($pages as $page)
                        <tr>
                            <td><strong>#{{ $page->id }}</strong></td>
                            <td></td>
                            <td class="font-weight-semibold">{{ $page->title }}</td>
                            <td>
                                <a href="{{ url($page->slug) }}" class="slug-link" target="_blank" title="Open page in frontend">
                                    <code>/{{ $page->slug }}</code>
                                    <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 0.75rem;"></i>
                                </a>
                            </td>
                            <td>
                                @if($page->status)
                                    <span class="status-badge-active">
                                        <i class="fa-solid fa-circle" style="font-size: 0.5rem;"></i> Active
                                    </span>
                                @else
                                    <span class="status-badge-inactive">
                                        <i class="fa-solid fa-circle-notch" style="font-size: 0.5rem;"></i> Inactive
                                    </span>
                                @endif
                            </td>
                            <td><i class="fa-regular fa-calendar me-1" style="color: #64748b;"></i> {{ $page->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="action-group justify-content-center">
                                    <a href="{{ route('admin.pages.edit', $page->id) }}" 
                                       class="btn-action btn-action-edit" title="Edit Page Layout">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </a>
                                    <a href="{{ url($page->slug) }}" 
                                       class="btn-action btn-action-view" title="Preview Live Page" 
                                       target="_blank">
                                        <i class="fa-regular fa-eye"></i>
                                    </a>
                                    <button type="button" class="btn-action btn-action-copy" 
                                            title="Copy Page URL" onclick="copyToClipboard('{{ url($page->slug) }}')">
                                        <i class="fa-regular fa-copy"></i>
                                    </button>
                                    <form action="{{ route('admin.pages.destroy', $page->id) }}" 
                                          method="POST" class="d-inline m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-action-delete" 
                                                title="Delete Page" onclick="return confirm('Are you sure you want to delete this page permanently?')">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#Pages').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'pdf', 'csv', 'print'
                ],
                ordering: true, // Enable sorting
                order: [[0, 'asc']], // Sort by Page ID ascending by default
                pageLength: 25,
                columnDefs: [
                    {
                        targets: 1, // Serial column (index 1)
                        searchable: false,
                        orderable: false,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    }
                ]
            });
        });

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Remove existing toast if present
                const existingToast = document.getElementById('premium-toast-container');
                if (existingToast) {
                    existingToast.remove();
                }

                // Create container
                const container = document.createElement('div');
                container.id = 'premium-toast-container';
                container.className = 'position-fixed bottom-0 end-0 p-4';
                container.style.zIndex = '99999';
                
                // Create toast element
                const toast = document.createElement('div');
                toast.className = 'toast-premium';
                toast.innerHTML = `
                    <i class="fa-solid fa-circle-check" style="color: #10b981; font-size: 1.25rem;"></i>
                    <div>
                        <div style="font-weight: 700; font-size: 0.9rem; margin-bottom: 2px;">URL Copied Successfully</div>
                        <div style="font-size: 0.75rem; color: #94a3b8; word-break: break-all;">${text}</div>
                    </div>
                `;
                
                container.appendChild(toast);
                document.body.appendChild(container);
                
                // Trigger transition animation
                setTimeout(() => {
                    toast.classList.add('show');
                }, 50);
                
                // Auto remove toast
                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => {
                        container.remove();
                    }, 300);
                }, 3000);
            }).catch(function(err) {
                console.error('Could not copy text: ', err);
            });
        }
    </script>
@endsection
