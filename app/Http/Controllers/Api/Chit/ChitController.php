<?php

namespace App\Http\Controllers\Api\Chit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chit;
use App\Models\ChitDetail;
use App\Models\Chitgroup;
use App\Models\Profit;
use App\Models\Bid;
use App\Models\LuckyMember;
use App\Models\Wallet;
use App\Models\PaymentLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $chits = Chit::with('chitdetail', 'chitgroup')->where('status', '1')->get();
        return response(["chit" => $chits]);
    }

    public function table(Request $request)
    {
        try {
            $validatedData = Validator::make($request->all(), [
                'user_id' => 'required',
                'chit_group_id' => 'required'
            ]);

            if ($validatedData->fails()) {
                return response()->json([
                    'errors' => $validatedData->errors()
                ], 422);
            }

            $group = Chitgroup::find($request->chit_group_id);
            if (!$group) {
                return response()->json([
                    'error' => 'Chit group not found'
                ], 404);
            }

            $chit = Chit::with('chitdetail', 'profit')->find($group->chit_id);
            if (!$chit) {
                return response()->json([
                    'error' => 'Chit not found'
                ], 404);
            }

            if ($chit->chitdetail->isEmpty()) {
                return response()->json([
                    'error' => 'Chit details not found'
                ], 404);
            }

            $luckymembers = LuckyMember::where('chit_group_id', $request->chit_group_id)
                ->with('user')
                ->get();

            $detail = $chit->chitdetail[0];
            $firstAuctionDate = !empty($detail->first_auction_date)
                ? Carbon::parse($detail->first_auction_date)
                : Carbon::now();

            $monthlyAmount = (float) ($detail->subscription_amount ?? 0);
            $duration      = (int) ($detail->duration ?? 0);
            $totalAmount   = $monthlyAmount * $duration;

            $data = [];

            foreach ($chit->profit as $key => $profit_item) {
                $winner = '';
                $emiStatus = 'Pending';

                foreach ($luckymembers as $luckymember) {
                    if (!empty($luckymember->date)) {
                        if ($firstAuctionDate->format('m-Y') == Carbon::parse($luckymember->date)->format('m-Y')) {
                            $winner = $luckymember->user->name ?? '';
                            break;
                        }
                    }
                }

                $userBid = Bid::where('user_id', $request->user_id)
                    ->where('chit_group_id', $request->chit_group_id)
                    ->where('chit_id', $chit->id)
                    ->whereMonth('date', $firstAuctionDate->month)
                    ->whereYear('date', $firstAuctionDate->year)
                    ->first();

                if ($userBid && $userBid->emi_status_of_this_month === 'Done') {
                    $emiStatus = 'Done';
                }

                $percent = (float) ($profit_item->presentage ?? 0);
                $profitAmount = round(($totalAmount * $percent) / 100, 0);
                $luckyAmountRaw = $totalAmount - $profitAmount;
                $luckyAmount = round($luckyAmountRaw / 1000) * 1000;

                $data[] = [
                    'no' => $key + 1,
                    'date' => $firstAuctionDate->format('d-m-Y'),
                    'monthly_amount' => round($monthlyAmount, 0),
                    'lucky_amount' => $luckyAmount,
                    'lucky_member' => $winner,
                    'emi_status_of_this_month' => $emiStatus,
                ];

                $firstAuctionDate->addMonth();
            }

            return response()->json($data);
        } catch (\Throwable $e) {
            Log::error('Error in ChitController@table: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = $request->validate([
            'name' => 'required|string|max:255',
            'chit_value' => 'required',
            'duration' => 'required',
            'first_auction_date' => 'required|date',
            'chit_type' => 'required',
            'subscription_amount' => 'required',
            'valueofprofit' => 'required|string',
        ]);

        if ($validation) {
            $chit = new Chit;
            $chit->name = $validation['name'];
            $chit->save();

            $chitdetalis = new ChitDetail;
            $chitdetalis->chit_value = $validation['chit_value'];
            $chitdetalis->subscription_amount = $validation['subscription_amount'];
            $chitdetalis->duration = $validation['duration'];
            $chitdetalis->first_auction_date = $validation['first_auction_date'];
            $chitdetalis->chit_type = $validation['chit_type'];
            $chitdetalis->chit_id = $chit->id;
            $chitdetalis->save();

            $group = new Chitgroup;
            $group->group_name = 'A';
            $group->chit_id = $chit->id;
            $group->date = $validation['first_auction_date'];
            $group->save();

            $percentageArray = explode(',', $validation['valueofprofit']);

            foreach ($percentageArray as $percentage) {
                $percentage = trim($percentage);
                if ($percentage === '') {
                    continue;
                }

                Profit::create([
                    'chit_id' => $chit->id,
                    'presentage' => $percentage,
                ]);
            }

            return response()->json(['message' => 'chits data saved successfully']);
        } else {
            return response()->json(['errors' => $request->validator->errors()], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Profit::where('chit_id', $id)->get();
        $ChitDetail = ChitDetail::where('chit_id', $id)->get();

        return view('Chits.table', ['data' => $data, 'chit_detalis' => $ChitDetail]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $Chit = Chit::with('chitdetail')->findOrFail($id);
        return response()->json($Chit);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validation = $request->validate([
            'name' => 'required|string|max:255',
            'chit_value' => 'required',
            'duration' => 'required',
            'first_auction_date' => 'required|date',
            'chit_type' => 'required',
            'subscription_amount' => 'required'
        ]);

        if ($validation) {
            $chit = Chit::find($id);
            $chit->name = $validation['name'];
            $chit->save();

            $chitdetalis = ChitDetail::where('chit_id', $id)->first();
            $chitdetalis->chit_value = $validation['chit_value'];
            $chitdetalis->subscription_amount = $validation['subscription_amount'];
            $chitdetalis->duration = $validation['duration'];
            $chitdetalis->first_auction_date = $validation['first_auction_date'];
            $chitdetalis->chit_type = $validation['chit_type'];
            $chitdetalis->chit_id = $chit->id;
            $chitdetalis->save();

            return response()->json(['message' => 'chits data update successfully']);
        } else {
            return response()->json(['errors' => $request->validator->errors()], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $chit = Chit::find($id);

        if (!$chit) {
            return response()->json(['message' => 'Chit detail not found'], 404);
        }

        $chit->delete();

        return response()->json(['message' => 'Chit detail deleted successfully']);
    }

    public function group($id)
    {
        $group = Chitgroup::where('chit_id', $id)->get();
        return view('Chits.group', ['group' => $group]);
    }

    public function select_group(Request $request)
    {
        $group = Chitgroup::with(['chit.chitdetail', 'chit.profit', 'bid.user', 'luckyMember.user'])->find($request->group_id);
        return view('Chits.lucky', ['data' => $group]);
    }

    public function winner(Request $request)
    {
        $existingWinner = LuckyMember::where('user_id', $request->user_id)
            ->where('date', $request->date)
            ->where('chit_group_id', $request->group_id)
            ->exists();

        if ($existingWinner) {
            return response()->json(['message' => 'User has already won for this date and chit group']);
        }

        $LuckyMember = new LuckyMember;
        $LuckyMember->user_id = $request->user_id;
        $LuckyMember->chit_group_id = $request->group_id;
        $LuckyMember->amount = $request->amount;
        $LuckyMember->date = $request->date;
        $LuckyMember->status = '1';
        $LuckyMember->save();

        $userWallet = Wallet::where('user_id', $request->user_id)->first();
        if (!$userWallet) {
            $userWallet = new Wallet;
            $userWallet->user_id = $request->user_id;
            $userWallet->balance = $request->amount;
        } else {
            $userWallet->balance += $request->amount;
        }
        $userWallet->save();

        return response()->json(['message' => 'User has won']);
    }

    public function showEmiData(Request $request)
    {
        try {
            Log::info('Incoming EMI Data Request:', $request->all());

            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer',
                'chit_group_id' => 'required|integer',
                'chit_id' => 'required|integer',
                'applied_chit_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $group = Chitgroup::find($request->chit_group_id);
            if (!$group) {
                return response()->json([
                    'status' => false,
                    'error' => 'Chit group not found'
                ], 404);
            }

            $chit = Chit::with('chitdetail', 'profit')->find($request->chit_id);
            if (!$chit || $chit->chitdetail->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'error' => 'Chit details not found'
                ], 404);
            }

            $bids = Bid::where('user_id', $request->user_id)
                ->where('applied_chit_id', $request->applied_chit_id)
                ->get();

            $paidBidsMap = [];
            foreach ($bids as $bid) {
                if (!empty($bid->date)) {
                    $monthKey = Carbon::parse($bid->date)->format('Y-m');
                    $paidBidsMap[$monthKey] = $bid;
                }
            }

            $paymentLogsQuery = PaymentLog::where('user_id', $request->user_id);
            if (Schema::hasColumn('payment_logs', 'applied_chit_id')) {
                $paymentLogsQuery->where('applied_chit_id', $request->applied_chit_id);
            }
            $paymentLogs = $paymentLogsQuery->orderBy('paid_at', 'asc')
                ->get()
                ->groupBy('emi_id');

            $hasMonthlyWiseWinnerTable = Schema::hasTable('monthly_wise_winner');

            $firstAuctionDate = Carbon::parse($chit->chitdetail[0]->first_auction_date);
            $monthlyAmount   = (float) ($chit->chitdetail[0]->subscription_amount ?? 0);
            $duration        = (int) ($chit->chitdetail[0]->duration ?? 0);
            $totalAmount     = $monthlyAmount * $duration;

            $data = [];

            for ($i = 0; $i < $duration; $i++) {
                $emiDate  = $firstAuctionDate->copy()->addMonths($i);
                $monthKey = $emiDate->format('Y-m');

                $matchedBid = $paidBidsMap[$monthKey] ?? null;
                $isPaid   = !is_null($matchedBid);

                $paidDate = $matchedBid
                    ? ($matchedBid->paid_date ?? ($matchedBid->created_at ? Carbon::parse($matchedBid->created_at)->format('d-m-Y') : null))
                    : null;
                $penalty  = $matchedBid ? ($matchedBid->penalty ?? 0) : 0;

                $payments = [];

                if ($matchedBid && isset($paymentLogs[$matchedBid->id])) {
                    foreach ($paymentLogs[$matchedBid->id] as $log) {
                        $payments[] = [
                            'paid_amount'  => (float) ($log->paid_amount ?? $log->amount ?? 0),
                            'paid_at'      => $log->paid_at ? Carbon::parse($log->paid_at)->format('d-m-Y H:i') : null,
                            'payment_mode' => $log->payment_mode ?? null,
                            'penalty_paid' => (float) ($log->penalty_paid ?? 0),
                        ];
                    }
                } elseif ($matchedBid) {
                    $payments[] = [
                        'paid_amount'  => (float) ($matchedBid->amount ?? $monthlyAmount),
                        'paid_at'      => $paidDate,
                        'payment_mode' => $matchedBid->payment_mode ?? null,
                        'penalty_paid' => (float) $penalty,
                    ];
                }

                $monthlyWinner = null;
                if ($hasMonthlyWiseWinnerTable) {
                    $monthlyWinner = DB::table('monthly_wise_winner')
                        ->join('users', 'monthly_wise_winner.user_id', '=', 'users.id')
                        ->where('monthly_wise_winner.chit_id', $request->chit_id)
                        ->whereRaw("DATE_FORMAT(monthly_wise_winner.date, '%Y-%m') = ?", [$monthKey])
                        ->select('users.id as user_id', 'users.name')
                        ->first();
                }

                $winnerName = $monthlyWinner
                    ? "{$monthlyWinner->name} (#{$monthlyWinner->user_id})"
                    : 'Pending';

                $profitItem   = $chit->profit[$i] ?? null;
                $profitAmount = $profitItem
                    ? round(($totalAmount * $profitItem->presentage) / 100, 0)
                    : 0;

                $luckyAmountRaw = $totalAmount - $profitAmount;
                $luckyAmount    = round($luckyAmountRaw / 1000) * 1000;

                $data[] = [
                    'no' => $i + 1,
                    'date' => $emiDate->format('d-m-Y'),
                    'monthly_amount' => round($monthlyAmount, 2),
                    'lucky_amount' => $luckyAmount,
                    'emi_status_of_this_month' => $isPaid ? 'Done' : 'Pending',
                    'lucky_member' => $winnerName,
                    'lucky_member_id' => $monthlyWinner->user_id ?? null,
                    'is_paid' => $isPaid,
                    'paid_date' => $paidDate,
                    'penalty' => $penalty,
                    'payments' => $payments,
                ];
            }

            return response()->json([
                'status' => true,
                'chit_id' => $chit->id,
                'user_id' => $request->user_id,
                'chit_group_id' => $request->chit_group_id,
                'emi_data' => $data
            ]);
        } catch (\Throwable $e) {
            Log::error('Error in ChitController@showEmiData: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => basename($e->getFile()),
            ], 500);
        }
    }

    /**
     * Get all Applied / Completed Chits of a User (No status filter)
     */
    public function completedChits(Request $request)
    {
        $userId = $request->query('user_id');

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'User ID is required'
            ], 400);
        }

        $completedChits = DB::table('applied_chits')
            ->join('chitdetails', 'applied_chits.chit_id', '=', 'chitdetails.id')
            ->join('chit_groups', 'chitdetails.chit_id', '=', 'chit_groups.chit_id')
            ->select(
                'applied_chits.id as applied_id',
                'applied_chits.user_id',
                'applied_chits.status',
                'chitdetails.chit_value',
                'chitdetails.subscription_amount',
                'chitdetails.duration',
                'chit_groups.group_name',
                'applied_chits.updated_at as completed_date'
            )
            ->where('applied_chits.user_id', $userId)
            ->orderBy('applied_chits.id', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'completed_chits' => $completedChits
        ]);
    }
}
