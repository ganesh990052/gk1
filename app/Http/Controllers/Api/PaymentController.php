<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function generateCashfreeSession(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string',
            'order_amount' => 'required|numeric',
            'customer_id' => 'required|string',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string',
        ]);

        $orderId = $request->order_id;
        $orderAmount = $request->order_amount;
        $customerId = $request->customer_id;
        $customerEmail = $request->customer_email;
        $customerPhone = $request->customer_phone;

        $appId = env('CASHFREE_APP_ID');
        $secretKey = env('CASHFREE_SECRET_KEY');
        $env = env('CASHFREE_ENV', 'sandbox');

        $baseUrl = $env === 'production'
            ? 'https://api.cashfree.com/pg/orders'
            : 'https://sandbox.cashfree.com/pg/orders';

        $headers = [
            'Content-Type' => 'application/json',
            'x-api-version' => '2022-09-01',
            'x-client-id' => $appId,
            'x-client-secret' => $secretKey,
        ];

        $payload = [
            'order_id' => $orderId,
            'order_amount' => $orderAmount,
            'order_currency' => 'INR',
            'customer_details' => [
                'customer_id' => $customerId,
                'customer_email' => $customerEmail,
                'customer_phone' => $customerPhone,
            ],
        ];

        $response = Http::withHeaders($headers)->post($baseUrl, $payload);

        if ($response->successful()) {
            $data = $response->json();
            return response()->json([
                'session_id' => $data['payment_session_id'],
                'order_id' => $orderId,
                'order_amount' => $orderAmount,
                'currency' => 'INR',
            ]);
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Failed to generate session',
                'details' => $response->json()
            ], 500);
        }
    }
}