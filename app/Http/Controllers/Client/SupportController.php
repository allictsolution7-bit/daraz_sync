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
            'priority' => 'required|in:low,medium,high',
            'message' => 'required_without:attachments|nullable|string',
            'attachments' => 'required_without:message|nullable|array',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,zip|max:5120',
        ]);

        $ticket = SupportTicket::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'category' => $request->category,
            'priority' => $request->priority,
            'status' => 'open',
        ]);

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('support_attachments'), $fileName);
                $attachments[] = 'support_attachments/' . $fileName;
            }
        }

        SupportTicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message ?? '',
            'attachments' => $attachments,
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
            'message' => 'required_without:attachments|nullable|string',
            'attachments' => 'required_without:message|nullable|array|max:3',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,zip|max:5120',
        ]);

        // If ticket is closed, don't allow replies, or reopen it
        if ($ticket->status === 'resolved' || $ticket->status === 'closed') {
            $ticket->update(['status' => 'open']);
        }

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('support_attachments'), $fileName);
                $attachments[] = 'support_attachments/' . $fileName;
            }
        }

        $reply = SupportTicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message ?? '',
            'attachments' => $attachments,
        ]);

        // Touch updated_at timestamp on parent ticket
        $ticket->touch();

        if ($request->ajax() || $request->wantsJson()) {
            $isAdminReply = Auth::user()->role === 'admin' || 
                            Auth::user()->role === 'super_admin' || 
                            Auth::user()->role === 'super admin' || 
                            Auth::user()->role === 'manager' ||
                            Auth::user()->id == 1;
            
            $profilePhoto = !empty(Auth::user()->profile_photo_path)
                ? \Illuminate\Support\Facades\Storage::disk('public')->url(Auth::user()->profile_photo_path)
                : asset('clientside/images/profile.png');
            
            $formattedAttachments = [];
            foreach ($attachments as $path) {
                $formattedAttachments[] = [
                    'path' => $path,
                    'url' => asset($path),
                    'name' => basename($path),
                    'is_image' => in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp']),
                ];
            }

            return response()->json([
                'success' => true,
                'reply' => [
                    'id' => $reply->id,
                    'message' => $reply->message,
                    'attachments' => $formattedAttachments,
                    'created_at_human' => $reply->created_at->diffForHumans(),
                    'user' => [
                        'name' => Auth::user()->name,
                        'profile_photo' => $profilePhoto,
                        'is_admin' => $isAdminReply,
                    ],
                ]
            ]);
        }

        return redirect()->back()->with('success', 'Reply submitted successfully.');
    }

    /**
     * Update a reply message or attachments.
     */
    public function updateReply(Request $request, $id)
    {
        $reply = SupportTicketReply::where('user_id', Auth::id())->findOrFail($id);

        if ($reply->created_at->diffInMinutes(now()) >= 10) {
            return response()->json(['success' => false, 'message' => 'Time limit (10 minutes) exceeded.'], 400);
        }

        $request->validate([
            'message' => 'required_without:attachments|nullable|string',
            'attachments' => 'required_without:message|nullable|array|max:3',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,zip|max:5120',
            'delete_attachments' => 'nullable|array',
        ]);

        $attachments = $reply->attachments ?? [];

        // Handle file deletions
        if ($request->has('delete_attachments')) {
            foreach ($request->delete_attachments as $delPath) {
                if (($key = array_search($delPath, $attachments)) !== false) {
                    unset($attachments[$key]);
                    if (file_exists(public_path($delPath))) {
                        @unlink(public_path($delPath));
                    }
                }
            }
            $attachments = array_values($attachments); // re-index
        }

        // Handle new uploads (keep max 3 files)
        if ($request->hasFile('attachments')) {
            if (count($attachments) + count($request->file('attachments')) > 3) {
                return response()->json(['success' => false, 'message' => 'Maximum of 3 files allowed total.'], 400);
            }

            foreach ($request->file('attachments') as $file) {
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('support_attachments'), $fileName);
                $attachments[] = 'support_attachments/' . $fileName;
            }
        }

        $reply->update([
            'message' => $request->message ?? '',
            'attachments' => $attachments,
        ]);

        $formattedAttachments = [];
        foreach ($attachments as $path) {
            $formattedAttachments[] = [
                'path' => $path,
                'url' => asset($path),
                'name' => basename($path),
                'is_image' => in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp']),
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Reply updated successfully.',
            'reply' => [
                'id' => $reply->id,
                'message' => $reply->message,
                'attachments' => $formattedAttachments,
            ]
        ]);
    }
}
