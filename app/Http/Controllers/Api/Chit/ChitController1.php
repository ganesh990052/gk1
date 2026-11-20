<?php

namespace App\Http\Controllers\Api\Chit;

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
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class ChitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $chits = Chit::with('chitdetail','chitgroup')->get();
         return response()->json($chits);
        // return response()->json(["chit=>$chits"]);

    }

    public function table(Request $request)
    {
        $validatedData = Validator::make($request->all(),[
            'user_id' => 'required',
            'chit_group_id' => 'required'
        
        ]);
        // Checking if validation passed
        if ($validatedData->passes()) {
            $group = Chitgroup::find($request->chit_group_id);
            $chit = Chit::with('chitdetail','profit')->find($group->chit_id);
            $bid = Bid::where(['user_id' =>$request->user_id, 'chit_group_id' =>$request->chit_group_id])->get();
            $luckymember = LuckyMember::where('chit_group_id', $request->chit_group_id)->with('user')->get();
            
            $firstAuctionDate = Carbon::parse($chit->chitdetail[0]->first_auction_date);
            $totalamount=$chit->chitdetail[0]->subscription_amount * $chit->chitdetail[0]->duration ;
         $data=[];

         $biddate = '';
         $winner='';    
foreach($chit->profit as $key=> $profit_item)
{

    

    foreach($luckymember as $luckymemberitem)
    {
        if($firstAuctionDate->format('m-Y') ==  Carbon::parse($luckymemberitem->date)->format('m-Y'))
        { 

            $winner=$luckymemberitem->user->name;
            break;
        }
    }
    foreach ($bid as $biditem) {

        if($firstAuctionDate->format('m-Y') ==  Carbon::parse($biditem->date)->format('m-Y'))
        {
        $biddate = Carbon::parse($biditem->date)->format('m-Y');
    break;
        }
    }
 
    $profitamount= round( $totalamount * $profit_item->presentage/100 ,0);
    $data[]=[
        'no'=>$key+1,
        'date'=>$firstAuctionDate->format('d-m-y'),
        'monthly_amount'=>round($chit->chitdetail[0]->subscription_amount,0),
        'lucky_amount'=>$totalamount-$profitamount,
        'lucky_member'=> $winner !=''  ? $winner: '',
        'emi_status_of_this_month'=>$firstAuctionDate->format('m-Y') == $biddate ? 'Done' : 'Panding',
        
    ];
    

     $firstAuctionDate->addMonth();
     $winner='';
}            return response()->json($data);

        } else {
            // Validation failed, return response with errors
            return response()->json(['errors' => $validatedData->errors()], 422);
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
    public function show(string $id)
    {
        $data=Profit::where('chit_id',$id)->get();

               $ChitDetail=ChitDetail::where('chit_id',$id)->get();
               
return  view('Chits.table',['data'=>$data,'chit_detalis'=>$ChitDetail]);

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
    
        $chit->delete();
    
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
}
