<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VendorWalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VendorWalletController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $transactions = VendorWalletTransaction::where('vendor_id', $user->id)
            ->latest()
            ->paginate(15);

        return view('vendor.wallet.index', compact('user', 'transactions'));
    }

    public function recharge(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10',
            'payment_method' => 'required|string|max:50',
            'transaction_id' => 'required|string|max:100',
            'proof_file' => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:2048',
            'admin_note' => 'nullable|string|max:500',
        ]);

        $proofPath = null;
        if ($request->hasFile('proof_file')) {
            $proofPath = $request->file('proof_file')->store('wallet_proofs', 'public');
        }

        VendorWalletTransaction::create([
            'vendor_id' => auth()->id(),
            'type' => 'recharge_request',
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'transaction_id' => $request->transaction_id,
            'proof_file' => $proofPath,
            'status' => 'pending',
            'admin_note' => $request->admin_note,
            'is_seen' => false,
        ]);

        return redirect()->back()->with('success', 'Recharge request submitted successfully. Waiting for admin approval.');
    }

    public function transfer(Request $request)
    {
        $request->validate([
            'recipient_email' => 'required|email|exists:users,email',
            'amount' => 'required|numeric|min:1',
            'note' => 'nullable|string|max:255',
        ]);

        $sender = auth()->user();
        $amount = $request->amount;

        if ($sender->wallet_balance < $amount) {
            return redirect()->back()->with('error', 'Insufficient wallet balance for this transfer.');
        }

        $recipient = User::where('email', $request->recipient_email)->first();

        if ($recipient->id === $sender->id) {
            return redirect()->back()->with('error', 'You cannot send money to yourself.');
        }

        DB::transaction(function () use ($sender, $recipient, $amount, $request) {
            // Deduct sender balance
            $sender->decrement('wallet_balance', $amount);

            // Add sender transaction
            VendorWalletTransaction::create([
                'vendor_id' => $sender->id,
                'type' => 'transfer_sent',
                'amount' => $amount,
                'status' => 'approved',
                'admin_note' => 'Transferred to ' . $recipient->name . ' (' . $recipient->email . '). Note: ' . $request->note,
            ]);

            // Credit recipient balance
            $recipient->increment('wallet_balance', $amount);

            // Add recipient transaction
            VendorWalletTransaction::create([
                'vendor_id' => $recipient->id,
                'type' => 'transfer_received',
                'amount' => $amount,
                'status' => 'approved',
                'admin_note' => 'Received from ' . $sender->name . ' (' . $sender->email . '). Note: ' . $request->note,
            ]);
        });

        return redirect()->back()->with('success', 'Money transferred successfully to ' . $recipient->name);
    }
}
