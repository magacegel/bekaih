<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\IPaymuService;
use App\Models\Invoice;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    //
    public function initiatePayment($report)
    {
        // dd($report->id);
        $paymentService = new IPaymuService();
        $result = $paymentService->createPayment($report);

        return $result;
    }

    public function success(Request $request)
    {
        // Handle successful payment return
        return view('payment.success');
    }

    public function cancel(Request $request)
    {
        // Handle cancelled payment
        return view('payment.cancel');
    }

    public function callback(Request $request)
    {
        \Log::info('Payment Callback received:', $request->all());

        $signature = $request->header('signature');
        $timestamp = $request->header('timestamp');
        
        // Verify the carb
        // Add signature verification logic here
        
        $sid = $request->input('sid');
        $status = $request->input('status');
        
        $invoice = Invoice::where('session_id', $sid)->firstOrFail();
        
        if ($status === 'berhasil') {
            $invoice->update([
                'status' => 'paid',
                'paid_at' => Carbon::now(),
            ]);
            
            // Update report status
            $invoice->report->update([
                'payment_status' => 'paid'
            ]);
        } elseif (in_array($status, ['expired', 'gagal'])) {
            $invoice->update([
                'status' => $status === 'expired' ? 'expired' : 'failed'
            ]);
        }
        
        return response()->json(['success' => true]);
    }
}
