<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\EmiTable;
use App\Models\ChitDetail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RegisterController extends Controller
{
    public function create()
    {
        return view('register.create');
    }

    public function store(){

        $attributes = request()->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:5|max:255',
        ]);

        $user = User::create($attributes);
        auth()->login($user);
        
        return redirect('/dashboard');
    } 

    public function user_mangengment()
    {
        $user = User::with('wallet')->get();

        return view('Transaction.user-management',['user'=>$user]);

    }

    public function user_details()
    {
       $user=User::with('wallet')->get();
       return view('user.user-details',['user'=>$user]);
    }

    public function emiDetails($user_id)
    {
    
        $user = DB::table('users')->where('id', $user_id)->first();
        if (!$user) abort(404, 'User not found');
    
        $appliedChits = DB::table('applied_chits')
            ->where('user_id', $user_id)
            ->where('status', 1)
            ->select('id', 'chit_id')   // 🔥 applied_chit_id
            ->get();
    
        $allChitEmis = [];
    
        foreach ($appliedChits as $applied) {
    
            $applyId = $applied->id;   // 🔥 UNIQUE
            $chitId  = $applied->chit_id;
    
            // ============ CHIT DETAILS ============
            $chit = DB::table('chitdetails')
                ->where('id', $chitId)
                ->first();
    
            if (!$chit) continue;
    
            $chitdetailsId = $chit->chit_id;
    
            // ============ PAID MONTHS (ONLY THIS APPLIED CHIT) ============
            $paidMonths = DB::table('bids')
                ->where('user_id', $user_id)
                ->where('applied_chit_id', $applyId) // 🔥 FIX
                ->where('emi_status_of_this_month', 'Done')
                ->whereNotNull('date')
                ->pluck('date')
                ->map(fn($date) => Carbon::parse($date)->format('Y-m'))
                ->toArray();
    
            $startDate = Carbon::parse($chit->first_auction_date)->startOfMonth();
            $duration  = (int) $chit->duration;
            $amount    = $chit->subscription_amount;
    
            $emis = [];
            $paidCount = 0;
            $pendingCount = 0;
    
            // ============ EMI LOOP ============
            for ($i = 0; $i < $duration; $i++) {
    
                $emiDate  = $startDate->copy()->addMonths($i);
                $monthKey = $emiDate->format('Y-m');
    
                $status = in_array($monthKey, $paidMonths) ? 'Done' : 'Pending';
    
                $status === 'Done' ? $paidCount++ : $pendingCount++;
    
                // WINNER
                $winner = DB::table('monthly_wise_winner')
                    ->join('users', 'monthly_wise_winner.user_id', '=', 'users.id')
                    ->where('monthly_wise_winner.chit_id', $chitdetailsId)
                    ->whereRaw("DATE_FORMAT(monthly_wise_winner.date,'%Y-%m') = ?", [$monthKey])
                    ->select('users.name')
                    ->first();
    
                $emis[] = (object) [
                    'apply_id' => $applyId,
                    'month'    => $emiDate->format('M Y'),
                    'date'     => $emiDate->format('Y-m-d'),
                    'amount'   => $amount,
                    'status'   => $status,
                    'winner'   => $winner->name ?? 'Pending'
                ];
                
            }
    
            $allChitEmis[] = [
                'apply_id'      => $applyId,
                'chit_id'       => $chitdetailsId,
                'chit_value'    => $chit->chit_value,
                'duration'      => $duration,
                'paid_count'    => $paidCount,
                'pending_count' => $pendingCount,
                'emis'          => $emis
            ];
            
            
        }
    
        return view('user.user-details-complit', compact('user', 'allChitEmis'));
    }

   public function payEmiByAdmin(Request $request)
{
    if ($request->confirmed !== 'yes') {
        return back()->with('error', 'Confirmation required');
    }

    $emiMonth = Carbon::parse($request->month)->startOfMonth();
    $currentMonth = now()->startOfMonth();

    // ❌ FUTURE EMI NOT ALLOWED
    if ($emiMonth->greaterThan($currentMonth)) {
        return back()->with('error', 'Future EMI cannot be paid by Admin');
    }

    // ❌ already paid check
    $exists = DB::table('bids')
        ->where('user_id', $request->user_id)
        ->where('applied_chit_id', $request->applied_chit_id)
        ->whereDate('date', $emiMonth)
        ->exists();

    if ($exists) {
        return back()->with('error', 'EMI already paid');
    }

    DB::table('bids')->insert([
        'user_id' => $request->user_id,
        'chit_id' => $request->chit_id,
        'applied_chit_id' => $request->applied_chit_id,
        'amount'  => $request->amount,
        'emi_status_of_this_month' => 'Done',
        'date' => $emiMonth,
        'paid_by' => 'admin',
        'admin_id' => auth()->id(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return back()->with('success', 'EMI Paid Successfully');
}

// public function ChitDetails($user_id)
// {
//     $user = DB::table('users')->where('id', $user_id)->first();
//     if (!$user) abort(404);

//     $bids = DB::table('applied_chits')
//         ->where('user_id', $user_id)
//         ->get();

//     return view('user.chit-details-complit', compact('user', 'bids'));
// }


public function ChitDetails($user_id)
{
    // User check
    $user = DB::table('users')->where('id', $user_id)->first();
    if (!$user) {
        abort(404);
    }

    // applied_chits + chitdetails JOIN
    $bids = DB::table('applied_chits')
        ->leftJoin('chitdetails', 'applied_chits.chit_id', '=', 'chitdetails.chit_id')
        ->where('applied_chits.user_id', $user_id)
        ->select(
            'applied_chits.*',
            'chitdetails.chit_value',
            'chitdetails.duration',
            'chitdetails.subscription_amount',
            'chitdetails.first_auction_date',
            'chitdetails.chit_type',
            'chitdetails.emi_status',
            'chitdetails.created_at as chit_created_at'
        )
        ->get();

    return view('user.chit-details-complit', compact('user', 'bids'));
}



public function deactivateAppliedChit($id)
{
    DB::table('applied_chits')
        ->where('id', $id)
        ->update([
            'status' => 0,
            'updated_at' => now()
        ]);

    return back()->with('success', 'Chit deactivated');
}

public function activateAppliedChit($id)
{
    DB::table('applied_chits')
        ->where('id', $id)
        ->update([
            'status' => 1,
            'updated_at' => now()
        ]);

    return back()->with('success', 'Chit activated');
}
public function deleteAppliedChit($id)
{
    DB::table('applied_chits')
        ->where('id', $id)
        ->delete();

    return back()->with('success', 'Chit deleted successfully');
}







}
