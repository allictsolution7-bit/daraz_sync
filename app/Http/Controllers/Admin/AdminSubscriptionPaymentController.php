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
        $user = auth()->user();
        $isSuperAdmin = $user?->hasRole('super_admin') || $user?->hasRole('super admin');

        $query = AdminSubscriptionPayment::with('user')->orderByDesc('created_at');

        // If not super admin, only show this admin's own subscription payments
        if (!$isSuperAdmin && $user) {
            $query->where('user_id', $user->id);
        }

        $payments = $query->get()->map(function ($p) {
            return [
                'id'          => $p->sub_id,
                'plan'        => $p->plan,
                'cycle'       => $p->cycle,
                'price'       => $p->price,
                'gateway'     => $p->gateway,
                'phone'       => $p->phone,
                'trxId'       => $p->trx_id,
                'status'      => $p->status,
                'expiryDate'  => $p->expiry_date?->format('Y-m-d'),
                'date'        => $p->created_at->format('n/j/Y'),
                'createdAt'   => $p->created_at->toISOString(),
                'adminName'   => $p->user?->name ?? 'Admin Merchant',
                'adminEmail'  => $p->user?->email ?? '',
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
            'user_id' => auth()->id(),
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

    // Helper to gather Issuer (Super Admin / Software Provider) and Issued To (Admin Subscriber)
    private function getInvoiceData($subId)
    {
        $payment = AdminSubscriptionPayment::with('user')->where('sub_id', $subId)->firstOrFail();

        // 1. ISSUER (Super Admin / Software Provider / Platform)
        $superAdminUser = \App\Models\User::whereHas('roles', function($q) {
            $q->whereIn('name', ['super_admin', 'super admin']);
        })->first();

        $issuerSiteName    = setting('general', 'site_name', config('app.name', 'PurnoBD'));
        $issuerCompanyName = setting('general', 'company_name', $issuerSiteName);
        $issuerPhone       = setting('ecommerce', 'bkash_number', setting('general', 'phone_number', '01779542054'));
        $issuerEmail       = $superAdminUser?->email ?? setting('general', 'contact_email', 'admin@purnobd.com');
        $issuerAddress     = setting('general', 'address', 'Dhaka, Bangladesh');
        $issuerWebsite     = config('app.url', url('/'));

        $issuerInfo = [
            'site_name'    => $issuerSiteName,
            'company_name' => $issuerCompanyName,
            'phone'        => $issuerPhone,
            'email'        => $issuerEmail,
            'address'      => $issuerAddress,
            'website'      => $issuerWebsite,
            'name'         => $superAdminUser?->name ?? 'Super Admin / Platform Provider',
        ];

        // 2. ISSUED TO (Admin Subscriber who paid for subscription)
        $subscriberUser = $payment->user ?? auth()->user();
        $adminPhone = $subscriberUser?->phone ?? $subscriberUser?->mobile ?? setting('general', 'phone_number', setting('general', 'whatsapp_number', 'N/A'));

        $issuedToInfo = [
            'name'    => $subscriberUser?->name ?? 'Subscribed Admin',
            'email'   => $subscriberUser?->email ?? 'admin@store.com',
            'phone'   => ($adminPhone !== 'N/A') ? $adminPhone : ($payment->phone ?? 'N/A'),
            'company' => $issuerSiteName . ' Merchant Account',
            'address' => setting('general', 'address', 'Dhaka, Bangladesh'),
        ];

        return compact('payment', 'issuerInfo', 'issuedToInfo');
    }

    // GET /admin/subscription-payments/{subId}/print-invoice
    public function printInvoice($subId)
    {
        $data = $this->getInvoiceData($subId);
        return view('admin.subscripton.invoice', $data);
    }

    // GET /admin/subscription-payments/{subId}/download-invoice
    public function downloadInvoice($subId)
    {
        $data = $this->getInvoiceData($subId);
        
        try {
            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_left' => 12,
                'margin_right' => 12,
                'margin_top' => 12,
                'margin_bottom' => 12,
                'default_font' => 'dejavusans',
                'autoScriptToLang' => true,
                'autoLangToFont' => true,
            ]);

            request()->merge(['pdf' => 1]);
            $html = view('admin.subscripton.invoice', $data)->render();
            $mpdf->WriteHTML($html);

            return response($mpdf->Output("Subscription-Invoice-{$subId}.pdf", 'S'))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="Subscription-Invoice-' . $subId . '.pdf"');
        } catch (\Exception $e) {
            return view('admin.subscripton.invoice', $data);
        }
    }
}
