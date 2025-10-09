<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Exception;
use App\Models\Report;
class IPaymuService
{
    protected $va;
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->va = config('services.ipaymu.va');
        $this->apiKey = config('services.ipaymu.api_key');
        $this->baseUrl = config('services.ipaymu.sandbox')
            ? 'https://sandbox.ipaymu.com'
            : 'https://my.ipaymu.com';
    }

    public function createPayment($report)
    {
    
        try {

            $report = Report::find($report->id);
            // Calculate total price from all forms in report
            $totalPrice = $report->form()->sum('total_spot') * 400;
            // Set minimum price to 50000
            if ($totalPrice < 50000) {
                $totalPrice = 50000;
            }

            $referenceId = hash('crc32b',$report->report_number).Carbon::now()->format('mY');
            // Build the request body
            $body = [
                'product' => array('UT Report - ' . $report->ship->name . ' - ' . $report->company->name . ' and branch'),
                'qty' => array(1),
                'price' => array($totalPrice), // Total price from all forms
                'returnUrl' => route('payment.success'),
                'cancelUrl' => route('payment.cancel'),
                'notifyUrl' => route('payment.callback'),
                'referenceId' => $referenceId,
                'imageUrl' => array('https://www.bki.co.id/logo/bkilogonew-whitebg.png'),
                'buyerName' => $report->user->name ?? '',
                'buyerEmail' => $report->user->email ?? '',
                'buyerPhone' => $report->user->phone ?? 0,
            ];
            // Generate signature
            //Generate Signature    
            // *Don't change this
            $jsonBody     = json_encode($body, JSON_UNESCAPED_SLASHES);
            $requestBody  = strtolower(hash('sha256', $jsonBody));
            $stringToSign = strtoupper('POST') . ':' . $this->va . ':' . $requestBody . ':' . $this->apiKey;
            $signature    = hash_hmac('sha256', $stringToSign, $this->apiKey);
            $timestamp    = Date('YmdHis');
            // dd($this->baseUrl . '/api/v2/payment', $body);
            // Make the API request
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'va' => $this->va,
                'signature' => $signature,
                'timestamp' => $timestamp
            ])->post($this->baseUrl . '/api/v2/payment', $body);

            $result = $response->json();
            // dd($result); 
            if ($response->successful() && ($result['Status'] ?? 0) == 200) {
                // Create invoice record
                $invoice = \App\Models\Invoice::updateOrCreate(
                    [
                        'invoice_number' => $referenceId,
                    ],
                    [
                        'report_id' => $report->id,
                        'payment_url' => $result['Data']['Url'],
                        'session_id' => $result['Data']['SessionID'],
                        'amount' => $totalPrice,
                        'status' => 'pending'
                ]);

                return [
                    'success' => true,
                    'redirect_url' => $result['Data']['Url'],
                    'invoice' => $invoice
                ];
            }

            throw new Exception($result['Message'] ?? 'Failed to create payment');

        } catch (Exception $e) {
            \Log::error('iPaymu Payment Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}