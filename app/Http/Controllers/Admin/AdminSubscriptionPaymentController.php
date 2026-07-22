<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminSubscriptionPayment;
use Illuminate\Http\Request;

class AdminSubscriptionPaymentController extends Controller
{
    // GET /admin/subscription-payments — return all payments as JSON
    public function index()
    {
        $payments = AdminSubscriptionPayment::orderByDesc('created_at')->get()->map(function ($p) {
            return [
                'id'         => $p->sub_id,
                'plan'       => $p->plan,
                'cycle'      => $p->cycle,
                'price'      => $p->price,
                'gateway'    => $p->gateway,
                'phone'      => $p->phone,
                'trxId'      => $p->trx_id,
                'status'     => $p->status,
                'expiryDate' => $p->expiry_date?->format('Y-m-d'),
                'date'       => $p->created_at->format('n/j/Y'),
                'createdAt'  => $p->created_at->toISOString(),
            ];
        });

        return response()->json($payments);
    }

    // POST /admin/subscription-payments — create new pending payment
    public function store(Request $request)
    {
        $request->validate([
            'sub_id'  => 'required|string|unique:admin_subscription_payments,sub_id',
            'plan'    => 'required|string',
            'cycle'   => 'nullable|string',
            'price'   => 'nullable|string',
            'gateway' => 'required|string',
            'phone'   => 'nullable|string',
            'trx_id'  => 'nullable|string',
        ]);

        $payment = AdminSubscriptionPayment::create([
            'sub_id'  => $request->sub_id,
            'plan'    => $request->plan,
            'cycle'   => $request->cycle ?? 'Monthly',
            'price'   => $request->price ?? '0',
            'gateway' => strtoupper($request->gateway),
            'phone'   => $request->phone,
            'trx_id'  => $request->trx_id,
            'status'  => 'Pending',
        ]);

        return response()->json(['success' => true, 'id' => $payment->sub_id]);
    }

    // PATCH /admin/subscription-payments/{subId} — approve / reject / update expiry
    public function update(Request $request, $subId)
    {
        $payment = AdminSubscriptionPayment::where('sub_id', $subId)->firstOrFail();

        if ($request->has('status')) {
            $payment->status = $request->status; // 'Approved' or 'Rejected'
        }
        if ($request->has('expiry_date') && $request->expiry_date) {
            $payment->expiry_date = $request->expiry_date;
        }
        $payment->save();

        return response()->json(['success' => true]);
    }
}
