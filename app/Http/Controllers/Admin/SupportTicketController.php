<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportTicketController extends Controller
{
    /**
     * Display a listing of all support tickets.
     */
    public function index(Request $request)
    {
        $query = SupportTicket::with('user')->orderBy('updated_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $tickets = $query->paginate(20);

        return view('admin.support.index', compact('tickets'));
    }

    /**
     * Display the specified support ticket details.
     */
    public function show($id)
    {
        $ticket = SupportTicket::with(['user', 'replies.user'])->findOrFail($id);
        return view('admin.support.show', compact('ticket'));
    }

    /**
     * Store admin's reply to the support ticket.
     */
    public function reply(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);

        $request->validate([
            'message' => 'required|string',
            'status' => 'required|string|in:open,pending,resolved,closed',
        ]);

        // Create the reply message
        SupportTicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        // Update ticket status
        $ticket->update([
            'status' => $request->status,
        ]);

        // Touch parent ticket to update timestamp
        $ticket->touch();

        return redirect()->back()->with('success', 'Reply posted and status updated.');
    }

    /**
     * Update the ticket's priority or status directly (taking action).
     */
    public function updateStatus(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);

        $request->validate([
            'status' => 'required|string|in:open,pending,resolved,closed',
            'priority' => 'required|string|in:low,medium,high',
        ]);

        $ticket->update([
            'status' => $request->status,
            'priority' => $request->priority,
        ]);

        return redirect()->back()->with('success', 'Ticket status and priority updated successfully.');
    }
}
