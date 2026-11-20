<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\ChitDetail;
use App\Models\Bid;
use App\Models\Order;
use App\Models\Emi;              // ✅ added
use App\Models\EmiPenalty;       // ✅ added
use App\Models\PaymentLog;       // ✅ added
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CashfreeController1 extends Controller
{
  
   public function createOrder(Request $request)
    {
        // dd($request->all()); die();
        \Log::info('📩 Create Order API Hit', $request->all());
        $request->validate([
            'order_amount'   => 'required|numeric|min:1',
            'customer_id'    => 'required|string',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string',
            'user_id'        => 'required|integer',
            'chit_group_id'  => 'required|integer',
            'chit_id'        => 'required|integer',
        ]);
    
        $appId     = "1066662783142e7bd032d0926c32666601";
        $secretKey = env("CASHFREE_SECRET_KEY");
       $url = "https://api.cashfree.com/pg/orders";
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
            ]),
           "order_meta" => [
        "return_url" => "https://admin.investifya.com/payment/callback?order_id=$orderId"
    ],
        ];
    
        \Log::info("📤 CreateOrder Payload:", $body);
    
        $response = Http::withHeaders([
            "x-client-id"     => $appId,
            "x-client-secret" => $secretKey,
            "x-api-version"   => "2022-09-01",
            "Content-Type"    => "application/json",
        ])->post($url, $body);
    
        \Log::info("📥 Cashfree Response:", $response->json());
    
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
                    // 🔹 Validate request
                    $validated = Validator::make($request->all(), [
                        'user_id'       => 'required|integer',
                        'chit_group_id' => 'required|integer',
                        'chit_id'       => 'required|integer',   // 👈 ab chit_id lenge
                        'order_id'      => 'required|string',
                        'amount'        => 'required|numeric',
                        'status'        => 'nullable|string',
                    ]);
        
                    if ($validated->fails()) {
                        return response()->json([
                            'success' => false,
                            'errors'  => $validated->errors()
                        ], 422);
                    }
        
                    // 🔹 Extract values
                    $userId  = $request->user_id;
                    $groupId = $request->chit_group_id;
                    $chitId  = $request->chit_id;   // 👈 chit_id
                    $orderId = $request->order_id;
                    $amount  = $request->amount;
                    $status  = $request->status ?? 'Done';
        
                    // 🔹 Find EMI record by chit_id
                    $emi = Bid::where('chit_id', $chitId)
                      ->where('user_id', $userId)
                      ->where('chit_group_id', $groupId)
                      ->whereMonth('date', now()->month)
                      ->whereYear('date', now()->year)
                      ->first();
        
                    // ✅ Check if already Done
                    if ($emi && $emi->emi_status_of_this_month === 'Done') {
                        return response()->json([
                            'success' => false,
                            'message' => '❌ Payment already done for this month'
                        ], 409);
                    }
        
                    if ($emi) {
                        // 🔹 Update existing EMI
                        $emi->update([
                            'emi_status_of_this_month' => $status,
                            'order_id' => $orderId,
                            'amount'   => $amount,
                            'status'   => $status,
                            'date'     => now(),
                        ]);
        
                        return response()->json([
                            'success' => true,
                            'message' => '✅ EMI updated successfully'
                        ]);
                    } else {
                        // 🔹 Create new EMI entry if not found
                        Bid::create([
                            'user_id' => $userId,
                            'chit_group_id' => $groupId,
                            'chit_id' => $chitId,   // 👈 insert chit_id
                            'order_id' => $orderId,
                            'amount' => $amount,
                            'status' => $status,
                            'emi_status_of_this_month' => $status,
                            'date' => now(),
                        ]);
        
                        return response()->json([
                            'success' => true,
                            'message' => '✅ EMI record created successfully'
                        ]);
                    }
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => '❌ Error: '.$e->getMessage()
                    ], 500);
                }
            }
            
            
            
    /**
     * ✅ Step 2: Payment Success Callback (Dynamic Insert)
     */
    public function paymentSuccess(Request $request)
{
    dd('ok'); die();
    $orderId = $request->query('order_id');

    // ✅ Order details Cashfree API se verify karo (optional)
    // ✅ Fir EMI update yahin se karo:
    Bid::create([
        'order_id' => $orderId,
        'user_id' => session('user_id'),  // ya query se lo
        'chit_id' => session('chit_id'),
        'amount' => session('amount'),
        'emi_status_of_this_month' => 'Done',
        'date' => now(),
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

    // 🔎 Order details fetch from Cashfree
    $appId     = "1066662783142e7bd032d0926c32666601";
        $secretKey = env("CASHFREE_SECRET_KEY");
   $url = "https://api.cashfree.com/pg/orders/$orderId";

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

    // ✅ Step 1: Fix HTML encoded order_note
    $rawNote = html_entity_decode($orderData['order_note'] ?? '{}', ENT_QUOTES);
    $note    = json_decode($rawNote, true);

    \Log::info("📜 Decoded order_note", ['decoded' => $note, 'raw' => $rawNote]);

    $userId      = (int) ($note['user_id'] ?? 0);
    $chitGroupId = (int) ($note['chit_group_id'] ?? 0);
    $chitId      = (int) ($note['chit_id'] ?? 0);
    $amount      = (float) ($orderData['order_amount'] ?? 0);

    // ✅ Sanity check
    if (!$userId || !$chitGroupId || !$chitId) {
        \Log::error("❌ Missing chit or user data", [
            'note' => $note,
            'orderData' => $orderData
        ]);
        return response()->json([
            'success' => false,
            'message' => '❌ Missing chit or user data in order_note'
        ], 422);
    }

    // ✅ Check payment status
    if (($orderData['order_status'] ?? '') !== 'PAID') {
        return response()->json([
            'success' => false,
            'message' => '⚠️ Payment not completed yet'
        ], 400);
    }

    // ============================================================
    // ✅ NEW FIFO LOGIC START (purane Bid update logic ki jagah)
    // ============================================================

    // 1. Latest applied_id nikaalo
    $applied = DB::table('applied_chits')
        ->where('user_id', $userId)
        ->where('chit_id', $chitId)
        ->where('status', 1)
        ->orderByDesc('id')
        ->first();

    if (!$applied) {
        \Log::error("❌ No active applied_chit found", [
            'user_id' => $userId,
            'chit_id' => $chitId
        ]);
        return response()->json([
            'success' => false,
            'message' => '❌ No active application found for this user & chit'
        ], 404);
    }

    $appliedId = $applied->id;

    // 2. Order_id already process ho chuka hai to skip
    if (PaymentLog::where('order_id', $orderId)->exists()) {
        \Log::info("ℹ️ Order already processed (FIFO)", ['order_id' => $orderId]);
        return view('payment.success', [
            'order_id' => $orderId
        ]);
    }

    // 3. FIFO Transaction
    DB::transaction(function () use ($appliedId, $amount, $orderId) {
        // order_id par unique check: dobara aaye toh skip (upar already check kar liya)
        $left = round($amount, 2);
        $emis = Emi::where('apply_id', $appliedId)
            ->orderBy('due_date')->orderBy('id')->lockForUpdate()->get();

        foreach ($emis as $emi) {
            if ($left <= 0) break;

            $penalty = EmiPenalty::forEmi($emi, now()); // 15 tarikh ke baad 5% penalty
            $total = $emi->monthly_amount + $penalty;
            $remaining = round($total - $emi->paid_amount, 2);

            if ($remaining <= 0) continue;

            $apply = min($remaining, $left);
            $emi->paid_amount += $apply;
            $emi->penalty = $penalty;

            if ($emi->paid_amount + 0.001 >= $total) {
                $emi->status = 'Done';
                $emi->paid_date = now();
            }

            $emi->save();

            PaymentLog::create([
                'order_id' => $orderId,
                'emi_id'   => $emi->id,
                'amount'   => $apply,
                'paid_at'  => now()
            ]);

            $left -= $apply;
        }
    });

    // ============================================================
    // ✅ NEW FIFO LOGIC END
    // ============================================================

    \Log::info("✅ EMI successfully updated via FIFO", [
        'user_id'       => $userId,
        'chit_group_id' => $chitGroupId,
        'chit_id'       => $chitId,
        'applied_id'    => $appliedId,
        'amount'        => $amount,
        'order_id'      => $orderId
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
            'chitdetails.chit_value',
            'chitdetails.subscription_amount',
            'chitdetails.duration',
            'chit_groups.group_name'
        )
        ->where('bids.user_id', $userId)
        // ->where('bids.status', 'success')  ❌ Isko comment kar
        ->orderBy('bids.date', 'desc')
        ->get();

    if ($myDeals->isEmpty()) {
        return response()->json([
            'success' => true,
            'message' => 'No deals found',
            'mychit' => []
        ]);
    }

    return response()->json([
        'success' => true,
        'mychit' => $myDeals
    ]);
}
public function applyChit(Request $request)
{
    try {
        // ✅ Step 1: Validation
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'chit_id' => 'required|integer',
            'email' => 'required|email',
            'phone' => 'required',
            'applied_date' => 'required|date',
            'force_apply' => 'nullable|boolean', // ✅ Add this for confirmation
        ]);

        // ✅ Step 2: Check how many times user applied
        $appliedCount = DB::table('applied_chits')
            ->where('user_id', $validated['user_id'])
            ->where('chit_id', $validated['chit_id'])
            ->count();

        // ✅ Step 3: If user reached limit
        if ($appliedCount >= 3) {
            return response()->json([
                'status' => 'limit_reached',
                'message' => '⚠️ You have already applied 3 times for this chit.',
            ], 200);
        }

        // ✅ Step 4: If user has already applied but not yet reached limit
        if ($appliedCount > 0 && empty($validated['force_apply'])) {
            return response()->json([
                'status' => 'exists',
                'message' => '⚠️ You have already applied for this chit. Apply again?',
            ], 200);
        }

        // ✅ Step 5: Insert new record (including confirmed reapply)
        DB::table('applied_chits')->insert([
            'user_id' => $validated['user_id'],
            'chit_id' => $validated['chit_id'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'applied_date' => $validated['applied_date'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ✅ Step 6: Response
        return response()->json([
            'status' => 'success',
            'message' => '✅ Your application has been submitted successfully!',
            'applied_count' => $appliedCount + 1, // Return updated count
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => '❌ Something went wrong!',
            'error' => $e->getMessage(),
        ], 500);
    }
}
    
public function getUserAppliedChits(Request $request)
{
    $user_id = $request->input('user_id');
    if (!$user_id) {
        return response()->json([
            'status' => false,
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
            'status' => false,
            'message' => 'No chits found for this user',
            'data' => []
        ]);
    }

    return response()->json([
        'status' => true,
        'message' => 'User applied chits fetched successfully',
        'data' => $data,
    ]);
}

    
}
