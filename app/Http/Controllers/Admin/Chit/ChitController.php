<?php

namespace App\Http\Controllers\Admin\Chit;

use App\Http\Controllers\Controller;
use Faker\Guesser\Name;
use Illuminate\Http\Request;
use App\Models\Chit;
use App\Models\ChitDetail;
use App\Models\Chitgroup;
use App\Models\Profit;
use App\Models\Bid;
use App\Models\LuckyMember;
use App\Models\Wallet;
use App\Models\MonthlyWinner;
use Carbon\Carbon;
class ChitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $chits = Chit::with('chitdetail')->where('status','1')->get();
        return view('Chits.index', ['chits' => $chits]);
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
            'subscription_amount'=>'required',
            'valueofprofit' => 'required|string',
        ]);
        
        if ($validation) {
                             
                $chit= new Chit;

                $chit->name =$validation['name'];

            $chit->save();

            
                $chitdetalis = new ChitDetail;

              $chitdetalis->chit_value = $validation['chit_value'];
              $chitdetalis->subscription_amount=$validation['subscription_amount'];
              $chitdetalis->duration = $validation['duration'];
              $chitdetalis->first_auction_date = $validation['first_auction_date'];
              $chitdetalis->chit_type = $validation['chit_type'];
              $chitdetalis->chit_id = $chit->id;
              $chitdetalis->save();           
           $group= new Chitgroup;
$group->group_name='A';
$group->chit_id=$chit->id;
$group->date=$validation['first_auction_date'];
$group->save();

$percentageArray = explode(',', $validation['valueofprofit']);
        
// Create a new Profit record for each percentage value
foreach ($percentageArray as $percentage) {
    // Trim any leading or trailing whitespace
    $percentage = trim($percentage);
    
    // Skip empty values
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
            // Validation failed, return response with errors
    return response()->json(['errors' => $request->validator->errors()], 422);
        }

      
    }

    /**
     * Display the specified resource.
     */

//     public function show(string $id)
//     {
//         $data=Profit::where('chit_id',$id)->get();

//               $ChitDetail=ChitDetail::where('chit_id',$id)->get();
               
// return  view('Chits.table',['data'=>$data,'chit_detalis'=>$ChitDetail]);

//     }

public function show(string $id)
{
    $data = Profit::where('chit_id', $id)->get();
    $chit_detalis = ChitDetail::where('chit_id', $id)->first();

    $monthlyAmount = $chit_detalis->subscription_amount;
    $duration      = $chit_detalis->duration;
    $totalAmount   = $monthlyAmount * $duration;

    $totalProfit = 0;

    foreach ($data as $row) {
        $totalProfit += round($totalAmount * $row->presentage / 100);
    }

    $totalCollection = $monthlyAmount * $duration * $duration;
    return view('Chits.table', compact(
        'data',
        'chit_detalis',
        'totalAmount',
        'totalProfit',
        'totalCollection'
    ));
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
            'subscription_amount'=>'required'
        ]);
        
        if ($validation) {
                             
                $chit=Chit::find($id);

                $chit->name =$validation['name'];

            $chit->save();

            
            $chitdetalis = ChitDetail::where('chit_id', $id)->first();

              $chitdetalis->chit_value = $validation['chit_value'];
              $chitdetalis->subscription_amount=$validation['subscription_amount'];
              $chitdetalis->duration = $validation['duration'];
              $chitdetalis->first_auction_date = $validation['first_auction_date'];
              $chitdetalis->chit_type = $validation['chit_type'];
              $chitdetalis->chit_id = $chit->id;
              $chitdetalis->save();           
           
            return response()->json(['message' => 'chits data update successfully']);  

        } else {
            // Validation failed, return response with errors
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
    
        // $chit->delete();
         $chit->status = 0;
         $chit->save();
    
        return response()->json(['message' => 'Chit detail deleted successfully']);  
    
    }
    
    public function group($id)
    {

$group=Chitgroup::where('chit_id',$id)->get();

return view('Chits.group',['group'=>$group]);
    }
public function select_group(Request $request)
{
    
    $group=Chitgroup::with(['chit.chitdetail', 'chit.profit', 'bid.user', 'luckyMember.user'])->find($request->group_id);


    return view('Chits.lucky',['data'=>$group]);
    // $Bid=Bid::where('chit_group_id',$request->group_id)->get();


}

public function winner(Request $request)
{
    // Check if the user has already won for the given date and chit group
    $existingWinner = LuckyMember::where('user_id', $request->user_id)
        ->where('date', $request->date)
        ->where('chit_group_id', $request->group_id)
        ->exists();

    if ($existingWinner) {
        return response()->json(['message' => 'User has already won for this date and chit group']);
    }

    // If the user hasn't won before, save the winner record and update wallet
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


// Monthly Winner

    public function monthlyWinner($id)
    {
        $data['chits_id'] = Chit::find($id);
        $data['monthlyWinner'] = MonthlyWinner::where('chit_id', $id)->get();
        return view('Chits.winner',$data);
    }
    
    
    

         public function monthlyLuckyWinner(Request $request)
        {
            $request->validate([
                'user_id' => 'required',
                'chit_id' => 'required',
                'date' => 'required|date_format:Y-m-d',
            ]);
        
            $date = Carbon::parse($request->date);
            $month = $date->month;
            $year  = $date->year;
        
            // 🔥 SAME CHIT + SAME MONTH + SAME YEAR count
            $winnerCount = MonthlyWinner::where('chit_id', $request->chit_id)
                ->whereYear('date', $year)
                ->whereMonth('date', $month)
                ->count();
        
            // ❌ Max 3 winners allowed per chit per month
            if ($winnerCount >= 3) {
                return response()->json([
                    'message' => 'This chit already has 3 winners for this month'
                ], 400);
            }
        
            // ✅ Create winner
            MonthlyWinner::create([
                'user_id' => $request->user_id,
                'chit_id' => $request->chit_id,
                'date'    => $request->date,
            ]);
        
            return response()->json([
                'message' => 'Monthly Winner added successfully'
            ]);
        }
    
    
    public function monthlyWinnerDelete(string $id)
    {
        $monthlyWinner = MonthlyWinner::find($id);

        if (!$monthlyWinner) {
            return response()->json(['message' => 'Monthly Winner not found'], 404);
        }
    
        $monthlyWinner->delete();
    
        return response()->json(['message' => 'Monthly Winner deleted successfully']); 
   }

    public function chit_restore()
   {
       $chits = Chit::with('chitdetail')->where('status','0')->get();
    //    dd($chits);die();
       return view('Chits.restore', ['chits' => $chits]);
   }

    public function restore(string $id)
    {
        $chit = Chit::findOrFail($id);
        if (!$chit) {
            return response()->json(['message' => 'Chit detail not found'], 404);
        }
        $chit->status = 1;
        $chit->save();
        return redirect()->route('chit-restore')->with('success', 'Chit restored successfully!');
    }



}
