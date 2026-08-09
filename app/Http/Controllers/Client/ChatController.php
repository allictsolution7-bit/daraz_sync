<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ChatRoom;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the chat page.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get all chat rooms where the user is either the customer or the vendor
        $chatRooms = ChatRoom::where('customer_id', $user->id)
            ->orWhere('vendor_id', $user->id)
            ->with(['customer.vendorSettings', 'vendor.vendorSettings', 'lastMessage'])
            ->get();

        // Format names/logos based on participant
        foreach ($chatRooms as $room) {
            $otherUser = $room->customer_id === $user->id ? $room->vendor : $room->customer;
            $room->other_user = $otherUser;
            $room->display_name = ($otherUser->vendorSettings && $otherUser->vendorSettings->business_name)
                ? $otherUser->vendorSettings->business_name
                : $otherUser->name;
            
            if ($otherUser->vendorSettings && $otherUser->vendorSettings->business_logo) {
                $room->display_logo = asset('storage/' . $otherUser->vendorSettings->business_logo);
            } elseif ($otherUser->profile_photo_path) {
                $room->display_logo = asset('storage/' . $otherUser->profile_photo_path);
            } else {
                $room->display_logo = asset('clientside/images/profile.png');
            }
        }

        $activeRoom = null;
        if ($request->has('room')) {
            $activeRoom = ChatRoom::where('id', $request->room)
                ->where(function ($query) use ($user) {
                    $query->where('customer_id', $user->id)
                          ->orWhere('vendor_id', $user->id);
                })
                ->with(['messages.sender'])
                ->first();
        }

        if ($activeRoom) {
            // Mark all received messages in this room as read
            ChatMessage::where('chat_room_id', $activeRoom->id)
                ->where('sender_id', '!=', $user->id)
                ->update(['is_read' => true]);

            $otherUser = $activeRoom->customer_id === $user->id ? $activeRoom->vendor : $activeRoom->customer;
            $activeRoom->other_user = $otherUser;
            $activeRoom->display_name = ($otherUser->vendorSettings && $otherUser->vendorSettings->business_name)
                ? $otherUser->vendorSettings->business_name
                : $otherUser->name;

            if ($otherUser->vendorSettings && $otherUser->vendorSettings->business_logo) {
                $activeRoom->display_logo = asset('storage/' . $otherUser->vendorSettings->business_logo);
            } elseif ($otherUser->profile_photo_path) {
                $activeRoom->display_logo = asset('storage/' . $otherUser->profile_photo_path);
            } else {
                $activeRoom->display_logo = asset('clientside/images/profile.png');
            }
        }

        return view('frontend.chats', compact('chatRooms', 'activeRoom'));
    }

    /**
     * Start/Get chat room with a seller.
     */
    public function startChat(Request $request, User $seller)
    {
        $user = Auth::user();

        // Prevent chatting with yourself
        if ($user->id === $seller->id) {
            return redirect()->route('chats.index')->with('error', 'You cannot chat with yourself.');
        }

        // Find or create chatroom
        // Customer is the active user initiating, Vendor is the seller
        $chatRoom = ChatRoom::firstOrCreate([
            'customer_id' => $user->id,
            'vendor_id' => $seller->id,
        ]);

        return redirect()->route('chats.index', ['room' => $chatRoom->id]);
    }

    /**
     * Send a new chat message.
     */
    public function sendMessage(Request $request, ChatRoom $chatRoom)
    {
        $user = Auth::user();

        // Ensure user belongs to the chatroom
        if ($chatRoom->customer_id !== $user->id && $chatRoom->vendor_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        $message = ChatMessage::create([
            'chat_room_id' => $chatRoom->id,
            'sender_id' => $user->id,
            'message' => $request->message,
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'sender_id' => $message->sender_id,
                'message' => $message->message,
                'created_at' => str_contains(strtolower($message->created_at->diffForHumans()), 'second') ? 'Just now' : $message->created_at->diffForHumans(),
                'sender_name' => $user->name,
            ]
        ]);
    }

    /**
     * Fetch new/recent messages for polling.
     */
    public function getMessages(Request $request, ChatRoom $chatRoom)
    {
        $user = Auth::user();

        if ($chatRoom->customer_id !== $user->id && $chatRoom->vendor_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $query = ChatMessage::where('chat_room_id', $chatRoom->id)
            ->with('sender');

        if ($request->has('last_id')) {
            $query->where('id', '>', $request->last_id);
        }

        $messages = $query->orderBy('created_at', 'asc')->get();

        // Mark received messages as read
        ChatMessage::where('chat_room_id', $chatRoom->id)
            ->where('sender_id', '!=', $user->id)
            ->update(['is_read' => true]);

        $formatted = $messages->map(function ($msg) {
            return [
                'id' => $msg->id,
                'sender_id' => $msg->sender_id,
                'message' => $msg->message,
                'created_at' => str_contains(strtolower($msg->created_at->diffForHumans()), 'second') ? 'Just now' : $msg->created_at->diffForHumans(),
                'sender_name' => $msg->sender->name,
            ];
        });

        return response()->json([
            'success' => true,
            'messages' => $formatted
        ]);
    }
}
