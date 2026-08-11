<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the user's support tickets.
     */
    public function index()
    {
        $user = Auth::user();
        $tickets = SupportTicket::where('user_id', $user->id)
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('frontend.user.support.index', compact('tickets', 'user'));
    }

    /**
     * Store a newly created support ticket.
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'message' => 'required|string',
            'priority' => 'required|in:low,medium,high',
        ]);

        $ticket = SupportTicket::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'category' => $request->category,
            'priority' => $request->priority,
            'status' => 'open',
        ]);

        SupportTicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        return redirect()->route('support.show', $ticket->id)->with('success', 'Your complaint/ticket has been submitted successfully.');
    }

    /**
     * Display the specified support ticket and its conversation replies.
     */
    public function show($id)
    {
        $user = Auth::user();
        $ticket = SupportTicket::where('user_id', $user->id)
            ->with(['replies.user'])
            ->findOrFail($id);

        return view('frontend.user.support.show', compact('ticket', 'user'));
    }

    /**
     * Add a reply to the ticket conversation.
     */
    public function reply(Request $request, $id)
    {
        $ticket = SupportTicket::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'message' => 'required|string',
        ]);

        // If ticket is closed, don't allow replies, or reopen it
        if ($ticket->status === 'resolved' || $ticket->status === 'closed') {
            $ticket->update(['status' => 'open']);
        }

        SupportTicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        // Touch updated_at timestamp on parent ticket
        $ticket->touch();

        return redirect()->back()->with('success', 'Reply submitted successfully.');
    }
}
