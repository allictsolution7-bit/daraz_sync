<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VendorWalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminVendorPaymentController extends Controller
{
    public function index(Request $request)
    {
        // Mark all unseen as seen when admin opens the page
        VendorWalletTransaction::where('is_seen', false)->update(['is_seen' => true]);

        $statusFilter = $request->get('status', 'all');

        $query = VendorWalletTransaction::with(['vendor', 'admin'])->latest();

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        $transactions = $query->paginate(20);
        $vendors = User::role('vendor')->orWhereHas('roles', function($q){
            $q->where('name', 'vendor');
        })->get();

        if ($vendors->isEmpty()) {
            $vendors = User::all();
        }

        $pendingCount = VendorWalletTransaction::where('status', 'pending')->count();
        $totalRecharged = VendorWalletTransaction::where('status', 'approved')
            ->whereIn('type', ['recharge_request', 'admin_grant'])
            ->sum('amount');

        return view('admin.vendor_payments.index', compact('transactions', 'vendors', 'pendingCount', 'totalRecharged', 'statusFilter'));
    }

    public function approve($id)
    {
        $transaction = VendorWalletTransaction::findOrFail($id);

        if ($transaction->status !== 'pending') {
            return redirect()->back()->with('error', 'Transaction has already been processed.');
        }

        DB::transaction(function () use ($transaction) {
            $transaction->update([
                'status' => 'approved',
                'admin_id' => auth()->id(),
            ]);

            // Auto credit vendor wallet balance
            $vendor = User::findOrFail($transaction->vendor_id);
            $vendor->increment('wallet_balance', $transaction->amount);
        });

        return redirect()->back()->with('success', 'Payment request approved and funds added to vendor wallet.');
    }

    public function reject(Request $request, $id)
    {
        $transaction = VendorWalletTransaction::findOrFail($id);

        if ($transaction->status !== 'pending') {
            return redirect()->back()->with('error', 'Transaction has already been processed.');
        }

        $transaction->update([
            'status' => 'rejected',
            'admin_id' => auth()->id(),
            'admin_note' => $request->admin_note ?? 'Rejected by Admin',
        ]);

        return redirect()->back()->with('success', 'Payment request has been rejected.');
    }

    public function grantFund(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
            'admin_note' => 'nullable|string|max:500',
        ]);

        $vendor = User::findOrFail($request->vendor_id);
        $amount = $request->amount;

        DB::transaction(function () use ($vendor, $amount, $request) {
            $vendor->increment('wallet_balance', $amount);

            VendorWalletTransaction::create([
                'vendor_id' => $vendor->id,
                'admin_id' => auth()->id(),
                'type' => 'admin_grant',
                'amount' => $amount,
                'payment_method' => 'Admin Direct Grant',
                'status' => 'approved',
                'admin_note' => $request->admin_note ?? 'Direct wallet credit by Admin',
                'is_seen' => true,
            ]);
        });

        return redirect()->back()->with('success', 'Successfully credited ৳' . number_format($amount, 2) . ' to ' . $vendor->name . '\'s wallet.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:vendor_wallet_transactions,id',
        ]);

        VendorWalletTransaction::whereIn('id', $request->ids)->delete();

        return response()->json(['success' => true, 'message' => 'Selected transactions deleted successfully.']);
    }

    public function exportCsv()
    {
        $transactions = VendorWalletTransaction::with(['vendor', 'admin'])->latest()->get();

        $filename = 'vendor_wallet_transactions_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Date', 'Vendor Name', 'Vendor Email', 'Type', 'Amount (BDT)', 'Payment Method', 'TRX ID', 'Status', 'Notes']);

            foreach ($transactions as $trx) {
                fputcsv($file, [
                    $trx->id,
                    $trx->created_at->format('Y-m-d H:i:s'),
                    $trx->vendor->name ?? 'N/A',
                    $trx->vendor->email ?? 'N/A',
                    $trx->type,
                    $trx->amount,
                    $trx->payment_method ?? 'N/A',
                    $trx->transaction_id ?? 'N/A',
                    $trx->status,
                    $trx->admin_note ?? '',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function downloadPdf($id)
    {
        $trx = VendorWalletTransaction::with(['vendor', 'admin'])->findOrFail($id);

        $html = view('admin.vendor_payments.pdf', compact('trx'))->render();

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 15,
            'margin_bottom' => 15,
        ]);

        $mpdf->WriteHTML($html);

        return response($mpdf->Output("vendor_receipt_{$trx->id}.pdf", 'D'))
            ->header('Content-Type', 'application/pdf');
    }
}
