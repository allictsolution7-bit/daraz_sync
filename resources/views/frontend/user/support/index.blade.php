@extends('frontend.app')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('new/user.styles.css') }}">
    <style>
        .support-container {
            margin: 30px auto;
            padding: 0 15px;
            max-width: 1200px;
        }

        .support-layout {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 30px;
        }

        .support-card {
            background-color: #ffffff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid rgba(226, 232, 240, 0.8);
        }

        .support-header {
            padding: 24px 30px;
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            background-color: #ffffff;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .support-header h4 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .support-body {
            padding: 30px;
        }

        .btn-create-ticket {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-create-ticket:hover {
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.3);
            transform: translateY(-1px);
            color: white;
        }

        .badge-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: capitalize;
            display: inline-block;
        }

        .status-open {
            background-color: #dbeafe !important;
            color: #1e40af !important;
        }

        .status-pending {
            background-color: #fef3c7 !important;
            color: #92400e !important;
        }

        .status-resolved {
            background-color: #d1fae5 !important;
            color: #065f46 !important;
        }

        .status-closed {
            background-color: #f1f5f9 !important;
            color: #475569 !important;
        }

        .badge-priority {
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .priority-low {
            background-color: #f1f5f9 !important;
            color: #475569 !important;
        }

        .priority-medium {
            background-color: #e0f2fe !important;
            color: #0369a1 !important;
        }

        .priority-high {
            background-color: #fee2e2 !important;
            color: #b91c1c !important;
        }

        .ticket-table {
            width: 100%;
            border-collapse: collapse;
        }

        .ticket-table th {
            text-align: left;
            padding: 16px 20px;
            font-size: 13px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            border-bottom: 1px solid #e2e8f0;
        }

        .ticket-table td {
            padding: 20px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
            color: #334155;
            vertical-align: middle;
        }

        .ticket-table tr:hover {
            background-color: #f8fafc;
        }

        .ticket-subject {
            font-weight: 600;
            color: #4f46e5;
            text-decoration: none;
            transition: all 0.2s;
        }

        .ticket-subject:hover {
            color: #3730a3;
            text-decoration: underline;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            backdrop-filter: blur(4px);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 30px;
            border: 1px solid #e2e8f0;
            width: 550px;
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            position: relative;
        }

        .close-modal {
            color: #94a3b8;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            position: absolute;
            right: 24px;
            top: 20px;
            transition: color 0.2s;
        }

        .close-modal:hover {
            color: #0f172a;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #0f172a;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid rgba(226, 232, 240, 1);
            border-radius: 10px;
            font-size: 14px;
            box-sizing: border-box;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #6366f1;
            outline: none;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
        }

        .empty-tickets-box {
            text-align: center;
            padding: 50px 20px;
        }

        .empty-tickets-icon {
            font-size: 48px;
            color: #94a3b8;
            margin-bottom: 16px;
        }

        @media (max-width: 768px) {
            .support-layout {
                grid-template-columns: 1fr;
            }
            .modal-content {
                width: 90%;
                margin: 20% auto;
            }
        }
    </style>
@endsection

@section('content')
    <div class="base-container support-container">
        <div class="support-layout">
            <!-- Sidebar Menu -->
            @include('frontend.user.partials.sidebar')

            <!-- Main Content Card -->
            <div class="support-card">
                <div class="support-header">
                    <h4><i class="fa-solid fa-headset text-indigo-500"></i> Support Tickets / Complaints</h4>
                    <button class="btn-create-ticket" onclick="openTicketModal()">
                        <i class="fa-solid fa-plus"></i> New Complaint
                    </button>
                </div>
                <div class="support-body">
                    @if (session('success'))
                        <div class="alert alert-success" style="padding: 15px; border-radius: 10px; background-color: #d1fae5; color: #065f46; margin-bottom: 20px; border: none; font-weight: 500;">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($tickets->count() > 0)
                        <div style="overflow-x: auto;">
                            <table class="ticket-table">
                                <thead>
                                    <tr>
                                        <th>Ticket ID</th>
                                        <th>Subject</th>
                                        <th>Category</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Last Updated</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tickets as $ticket)
                                        <tr>
                                            <td style="font-weight: 700; color: #64748b;">#{{ $ticket->id }}</td>
                                            <td>
                                                <a href="{{ route('support.show', $ticket->id) }}" class="ticket-subject">
                                                    {{ $ticket->subject }}
                                                </a>
                                            </td>
                                            <td>
                                                <span style="font-weight: 500; color: #475569;">{{ $ticket->category }}</span>
                                            </td>
                                            <td>
                                                <span class="badge-priority priority-{{ $ticket->priority }}">
                                                    {{ $ticket->priority }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge-status status-{{ $ticket->status }}">
                                                    {{ $ticket->status }}
                                                </span>
                                            </td>
                                            <td style="color: #64748b; font-size: 13px;">
                                                {{ $ticket->updated_at->diffForHumans() }}
                                            </td>
                                            <td>
                                                <a href="{{ route('support.show', $ticket->id) }}" class="btn-create-ticket" style="padding: 6px 14px; font-size: 12px; margin: 0;">
                                                    <i class="fa-solid fa-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div style="margin-top: 25px;">
                            {{ $tickets->links() }}
                        </div>
                    @else
                        <div class="empty-tickets-box">
                            <div class="empty-tickets-icon">
                                <i class="fa-solid fa-clipboard-question"></i>
                            </div>
                            <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">No Complaints or Tickets Found</h3>
                            <p style="color: #64748b; font-size: 14px; margin-bottom: 24px; max-width: 400px; margin-left: auto; margin-right: auto;">
                                If you are facing any issues with your orders, payments, products, or anything else, feel free to open a support ticket. Our team will resolve it as soon as possible.
                            </p>
                            <button class="btn-create-ticket" onclick="openTicketModal()">
                                <i class="fa-solid fa-plus"></i> Open First Ticket
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Create Ticket Modal -->
    <div id="ticketModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeTicketModal()">&times;</span>
            <h3 style="font-size: 20px; font-weight: 700; color: #0f172a; margin-top: 0; margin-bottom: 20px;">Submit a Complaint / Request</h3>
            
            <form action="{{ route('support.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="subject">Subject / Title</label>
                    <input type="text" id="subject" name="subject" class="form-control" placeholder="Brief summary of your issue" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="form-group">
                        <label for="category">Category</label>
                        <select id="category" name="category" class="form-control" required>
                            <option value="">Select Category</option>
                            <option value="Order Issues">Order Issues</option>
                            <option value="Payment Issues">Payment Issues</option>
                            <option value="Product Quality">Product Quality</option>
                            <option value="Vendor Complain">Vendor Complain</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="priority">Priority</label>
                        <select id="priority" name="priority" class="form-control" required>
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="message">Detailed Explanation</label>
                    <textarea id="message" name="message" class="form-control" rows="5" placeholder="Describe your issue in detail. If relating to an order, please include order ID." required style="resize: vertical; font-family: inherit;"></textarea>
                </div>

                <div style="text-align: right; margin-top: 25px;">
                    <button type="button" class="btn-create-ticket" style="background: #e2e8f0; color: #475569; box-shadow: none; margin-right: 10px;" onclick="closeTicketModal()">Cancel</button>
                    <button type="submit" class="btn-create-ticket">Submit Ticket</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        var modal = document.getElementById("ticketModal");

        function openTicketModal() {
            modal.style.display = "block";
        }

        function closeTicketModal() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                closeTicketModal();
            }
        }
    </script>
@endsection
