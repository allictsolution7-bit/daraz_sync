<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Http;

class ContactController extends Controller
{
    public function index()
    {
        $messages = Contact::latest()->paginate(10);
        return view('admin.contacts.index', compact('messages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);
        
        // Get user agent information
        $agent = new Agent();
        $agent->setUserAgent($request->header('User-Agent'));
        
        // Get IP address
        $ipAddress = $request->ip();
        
        // Try to get location information from IP
        $locationData = [];
        try {
            $response = Http::get("http://ip-api.com/json/{$ipAddress}");
            if ($response->successful()) {
                $locationData = $response->json();
            }
        } catch (\Exception $e) {
            // Silently fail if the API is unavailable
        }
        
        // Create contact with additional information
        Contact::create(array_merge($validated, [
            'ip_address' => $ipAddress,
            'user_agent' => $request->header('User-Agent'),
            'device' => $agent->device(),
            'browser' => $agent->browser(),
            'platform' => $agent->platform(),
            'country' => $locationData['country'] ?? null,
            'city' => $locationData['city'] ?? null,
            'region' => $locationData['regionName'] ?? null,
        ]));

        return redirect()->back()->with('success', 'Thank you for your message. We will get back to you soon!');
    }

    public function unread()
    {
        $messages = Contact::where('is_read', false)->latest()->paginate(10);
        return view('admin.contacts.index', compact('messages'));
    }

    public function show(Contact $contact)
    {
        $contact->update(['is_read' => true]);
        return view('admin.contacts.show', compact('contact'));
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return redirect()->route('admin.contacts.index')->with('success', 'Message deleted successfully');
    }
}