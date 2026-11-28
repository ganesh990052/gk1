<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\ChitDetail;
use App\Models\Bid;
use App\Models\Order;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CashfreeController1 extends Controller
{
    public function createOrder(Request $request)
    {
        $request->validate([
            'order_amount'   => 'required|numeric|min:1',
            'customer_id'    => 'required|string',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string',
            'user_id'        => 'required|integer',
            'chit_group_id'  => 'required|integer',
            'chit_id'        => 'required|integer',
            'apply_id'       => 'required|integer',
        ]);
    
        $appId     = env("CASHFREE_APP_ID");
        $secretKey = env("CASHFREE_SECRET_KEY");
        $url       = "https://sandbox.cashfree.com/pg/orders";
        $orderId   = "order_" . uniqid();
    
        $body = [
            "order_id"       => $orderId,
            "order_amount"   => $request->order_amount,
            "order_currency" => "INR",
            "customer_details" => [
                "customer_id"    => $request->customer_id,
                "customer_email" => $request->customer_email,
                "customer_phone" => $request->customer_phone,
            ],
            "order_note" => json_encode([
                "user_id"       => $request->user_id,
                "chit_group_id" => $request->chit_group_id,
                "chit_id"       => $request->chit_id,
                "apply_id"      => $request->apply_id,
            ]),
            "order_meta" => [
                "return_url" => "https://gk1-9zox.onrender.com/api/payment/success?order_id=$orderId"
            ],
        ];
    
        $response = Http::withHeaders([
            "x-client-id"     => $appId,
            "x-client-secret" => $secretKey,
            "x-api-version"   => "2022-09-01",
            "Content-Type"    => "application/json",
        ])->post($url, $body);
    
        if ($response->successful()) {
            $cashfreeRes = $response->json();
            return response()->json([
                "success"             => true,
                "order_id"            => $cashfreeRes['order_id'] ?? null,
                "payment_session_id"  => $cashfreeRes['payment_session_id'] ?? null,
                "payment_link"        => $cashfreeRes['payment_link'] ?? null,
            ]);
        }
    
        return response()->json([
            "success" => false,
            "error"   => $response->body(),
        ], $response->status());
    }

    public function updateEmi(Request $request)
    {
        try {
            $validated = Validator::make($request->all(), [
                'user_id'       => 'required|integer',
                'apply_id'      => 'required|integer',
                'order_id'      => 'required|string',
                'amount'        => 'required|numeric',
                'status'        => 'nullable|string',
                'payment_mode'  => 'nullable|string',
            ]);
    
            if ($validated->fails()) {
                return response()->json([
                    'success' => false,
                    'errors'  => $validated->errors()
                ], 422);
            }
    
            $userId      = $request->user_id;
            $applyId     = $request->apply_id;
            $orderId     = $request->order_id;
            $amount      = $request->amount;
            $status      = $request->status ?? 'Done';
            $paymentMode = $request->payment_mode ?? null;
    
            $emi = Bid::where('user_id', $userId)
                ->where('applied_chit_id', $applyId)
                ->whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->first();
    
            if ($emi && $emi->emi_status_of_this_month === 'Done') {
                return response()->json([
                    'success' => false,
                    'message' => '❌ Payment already done for this month'
                ], 409);
            }
    
            $data = [
                'emi_status_of_this_month' => $status,
                'order_id'                 => $orderId,
                'amount'                   => $amount,
                'status'                   => $status,
                'paid_by'                  => 'customer',
                'date'                     => now(),
            ];

            if ($paymentMode) {
                $data['payment_mode'] = $paymentMode;
            }
    
            if ($emi) {
                $emi->update($data);
            } else {
                // Resolve chit_group_id and chit_id from applied_chits
                // to avoid MySQL error 1364
                $applied = DB::table('applied_chits as ac')
                    ->join('chitdetails as cd', 'ac.chit_id', '=', 'cd.id')
                    ->leftJoin('chit_groups as cg', 'cd.chit_id', '=', 'cg.chit_id')
                    ->where('ac.id', $applyId)
                    ->select(
                        'cd.id as chit_detail_id',
                        'cd.chit_id as chit_id',
                        'cg.id as chit_group_id'
                    )
                    ->first();

                if (!$applied) {
                    return response()->json([
                        'success' => false,
                        'message' => '❌ Applied chit not found for given apply_id'
                    ], 404);
                }

                Bid::create(array_merge($data, [
                    'user_id'         => $userId,
                    'applied_chit_id' => $applyId,
                    'chit_id'         => $applied->chit_detail_id ?? $applied->chit_id,
                    'chit_group_id'   => $applied->chit_group_id,
                ]));
            }
    
            return response()->json([
                'success' => true,
                'message' => '✅ EMI updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ Error: '.$e->getMessage()
            ], 500);
        }
    }

    public function paymentSuccess(Request $request)
    {
        $orderId = $request->query('order_id');

        Bid::create([
            'order_id'                 => $orderId,
            'user_id'                  => session('user_id'),
            'chit_id'                  => session('chit_id'),
            'amount'                   => session('amount'),
            'emi_status_of_this_month' => 'Done',
            'date'                     => now(),
        ]);

        return view('payment-success', ['order_id' => $orderId]);
    }

    public function paymentCallback(Request $request)
    {
        $orderId = $request->query('order_id');
        if (!$orderId) {
            return response()->json([
                'success' => false,
                'message' => '❌ Order ID missing in callback'
            ], 400);
        }

        $appId     = env("CASHFREE_APP_ID");
        $secretKey = env("CASHFREE_SECRET_KEY");
        $url       = "https://sandbox.cashfree.com/pg/orders/$orderId";

        $response = Http::withHeaders([
            "x-client-id"     => $appId,
            "x-client-secret" => $secretKey,
            "x-api-version"   => "2022-09-01",
        ])->get($url);

        if (!$response->successful()) {
            \Log::error("❌ Failed to fetch order from Cashfree", ['body' => $response->body()]);
            return response()->json([
                "success" => false,
                "message" => "Failed to verify payment",
                "error"   => $response->body()
            ], 500);
        }

        $orderData = $response->json();

        $rawNote = html_entity_decode($orderData['order_note'] ?? '{}', ENT_QUOTES);
        $note    = json_decode($rawNote, true);

        \Log::info("📜 Decoded order_note", ['decoded' => $note, 'raw' => $rawNote]);

        $userId      = (int) ($note['user_id'] ?? 0);
        $chitGroupId = (int) ($note['chit_group_id'] ?? 0);
        $chitId      = (int) ($note['chit_id'] ?? 0);
        $applyId     = (int) ($note['apply_id'] ?? 0);
        $amount      = (float) ($orderData['order_amount'] ?? 0);

        if (!$userId || !$chitGroupId || !$chitId || !$applyId) {
            \Log::error("❌ Missing chit or user data", [
                'note' => $note,
                'orderData' => $orderData
            ]);
            return response()->json([
                'success' => false,
                'message' => '❌ Missing chit or user data in order_note'
            ], 422);
        }

        if (($orderData['order_status'] ?? '') !== 'PAID') {
            return response()->json([
                'success' => false,
                'message' => '⚠️ Payment not completed yet'
            ], 400);
        }

        $paymentMode = null;

        $paymentsUrl = "https://sandbox.cashfree.com/pg/orders/{$orderId}/payments";
        $paymentsRes = Http::withHeaders([
            "x-client-id"     => $appId,
            "x-client-secret" => $secretKey,
            "x-api-version"   => "2022-09-01",
        ])->get($paymentsUrl);

        if ($paymentsRes->successful()) {
            $payments = $paymentsRes->json();
            if (is_array($payments) && count($payments) > 0) {
                $latestPayment = $payments[0];
                $paymentMode = $latestPayment['payment_group'] 
                            ?? $latestPayment['payment_method'] 
                            ?? $latestPayment['payment_method_details']['payment_method'] 
                            ?? null;
            }
        }

        \Log::info("💳 Payment Mode Detected", ['payment_mode' => $paymentMode, 'order_id' => $orderId]);

        $emi = Bid::where([
                'user_id'         => $userId,
                'applied_chit_id' => $applyId,
            ])
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->first();

        if ($emi && $emi->emi_status_of_this_month === 'Done') {
            return response()->json([
                'success' => false,
                'message' => '✅ Payment already recorded for this month'
            ], 409);
        }

        $data = [
            'emi_status_of_this_month' => 'Done',
            'order_id'                 => $orderId,
            'amount'                   => $amount,
            'status'                   => 'Done',
            'date'                     => now(),
            'paid_by'                  => 'customer',
            'payment_mode'             => $paymentMode,
        ];

        if ($emi) {
            $emi->update($data);
        } else {
            Bid::create(array_merge($data, [
                'user_id'         => $userId,
                'chit_group_id'   => $chitGroupId,
                'applied_chit_id' => $applyId,
                'chit_id'         => $chitId,
            ]));
        }

        \Log::info("✅ EMI successfully updated in DB", [
            'user_id'       => $userId,
            'chit_group_id' => $chitGroupId,
            'chit_id'       => $chitId,
            'apply_id'      => $applyId,
            'amount'        => $amount,
            'payment_mode'  => $paymentMode,
        ]);

        return view('payment.success', [
            'order_id' => $orderId
        ]);
    }

    public function myDeals(Request $request)
    {
        $userId = $request->query('user_id');

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'User ID is required'
            ], 400);
        }

        $myDeals = \DB::table('bids')
            ->join('chitdetails', 'bids.chit_id', '=', 'chitdetails.id')
            ->join('chit_groups', 'bids.chit_group_id', '=', 'chit_groups.id')
            ->select(
                'bids.id as bid_id',
                'bids.user_id',
                'bids.amount as paid_amount',
                'bids.status as bid_status',
                'bids.emi_status_of_this_month',
                'bids.date as paid_date',
                'bids.payment_mode',
                'chitdetails.chit_value',
                'chitdetails.subscription_amount',
                'chitdetails.duration',
                'chit_groups.group_name'
            )
            ->where('bids.user_id', $userId)
            ->orderBy('bids.date', 'desc')
            ->get();

        if ($myDeals->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'No deals found',
                'mychit'  => []
            ]);
        }

        return response()->json([
            'success' => true,
            'mychit'  => $myDeals
        ]);
    }

    public function applyChit(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id'      => 'required|integer',
                'chit_id'      => 'required|integer',
                'email'        => 'required|email',
                'phone'        => 'required',
                'applied_date' => 'required|date',
                'force_apply'  => 'nullable|boolean',
            ]);

            $appliedCount = DB::table('applied_chits')
                ->where('user_id', $validated['user_id'])
                ->where('chit_id', $validated['chit_id'])
                ->count();

            if ($appliedCount >= 3) {
                return response()->json([
                    'status'  => 'limit_reached',
                    'message' => '⚠️ You have already applied 3 times for this chit.',
                ], 200);
            }

            if ($appliedCount > 0 && empty($validated['force_apply'])) {
                return response()->json([
                    'status'  => 'exists',
                    'message' => '⚠️ You have already applied for this chit. Apply again?',
                ], 200);
            }

            DB::table('applied_chits')->insert([
                'user_id'      => $validated['user_id'],
                'chit_id'      => $validated['chit_id'],
                'email'        => $validated['email'],
                'phone'        => $validated['phone'],
                'applied_date' => $validated['applied_date'],
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

            return response()->json([
                'status'         => 'success',
                'message'        => '✅ Your application has been submitted successfully!',
                'applied_count'  => $appliedCount + 1,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => '❌ Something went wrong!',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
    
    public function getUserAppliedChits(Request $request)
    {
        $user_id = $request->input('user_id');
        if (!$user_id) {
            return response()->json([
                'status'  => false,
                'message' => 'User ID is required',
            ], 400);
        }

        $data = DB::table('applied_chits')
            ->join('chitdetails', 'applied_chits.chit_id', '=', 'chitdetails.id')
            ->join('chit_groups', 'chitdetails.chit_id', '=', 'chit_groups.chit_id') 
            ->select(
                'applied_chits.id as applied_id',
                'applied_chits.user_id',
                'applied_chits.status',
                'applied_chits.applied_date',
                'applied_chits.email',
                'applied_chits.phone',
                'chitdetails.id as chit_detail_id',
                'chitdetails.chit_id',
                'chit_groups.id as chit_group_id',
                'chitdetails.chit_value',
                'chitdetails.duration',
                'chitdetails.subscription_amount',
                'chitdetails.first_auction_date',
                'chitdetails.chit_type',
                'chitdetails.emi_status'
            )
            ->where('applied_chits.user_id', $user_id)
            ->where('applied_chits.status', 1)
            ->orderBy('applied_chits.id', 'desc')
            ->get();

        if ($data->isEmpty()) {
            return response()->json([
                'status'  => false,
                'message' => 'No chits found for this user',
                'data'    => []
            ]);
        }

        return response()->json([
            'status'  => true,
            'message' => 'User applied chits fetched successfully',
            'data'    => $data,
        ]);
    }
}
