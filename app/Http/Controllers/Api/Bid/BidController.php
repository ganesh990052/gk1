<?php

namespace App\Http\Controllers\Api\Bid;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bid;
use App\Models\Chit;
use App\Models\Chitgroup;
use App\Models\JoiningUsers;
use Illuminate\Support\Facades\Validator;
class BidController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = Validator::make($request->all(),[
            'amount' => 'required',
            'chit_group_id' => 'required|exists:chit_groups,id',
            'user_id' => 'required|exists:users,id',
            'date'=>'required'
       
        ]);
        if ($validatedData->fails()) {
          return response()->json(['errors' => $validatedData->errors()], 422);
      }
      $validatedData = $validatedData->validated();          // Store the data in the database
      
        $group = Bid::where([
        'chit_group_id' => $request->chit_group_id,
    ])->with('chitgroup')->first(); 
$Chit=Chit::where('id',$group->chitgroup->chit_id)->with('chitgroup')->get();
foreach ($Chit as $chitData) {
    // Process chit data
   
    // Process chit details
    foreach ($chitData['chitdetail'] as $detail) {
    
        // Process other chit detail fields
    }
    // Process chit groups
    foreach ($chitData['chitgroup'] as $groupinloop) {
       
  $encounteredUserIds = [];

    // Process chit group bids
    foreach ($groupinloop['bid'] as $bid) {
        // Check if the bid's chit_group_id matches the current group's id
      if (!in_array($bid->user_id, $encounteredUserIds) && $bid->chit_group_id == $groupinloop->id) {
            // Store the user ID in the encountered array
            $encounteredUserIds[] = $bid->user_id;
        }
    }

   
     $uniqueUserCount = count($encounteredUserIds);
// echo "Group ID: $group->id - Unique User Count: $uniqueUserCount"; 
 
//  echo   $uniqueUserCount % $chitData['chitdetail'][0]->duration;


 if($uniqueUserCount % $chitData['chitdetail'][0]->duration==0 && $groupinloop->status!=0)
 {
     $oldgroup=Chitgroup::find($groupinloop->id);
     $oldgroup->status=0;
     $oldgroup->save();
   
    $todayDate = date("Y-m-d");
   $asciiValueOfold = ord($oldgroup->group_name);
    $asciiValueOfnew = $asciiValueOfold + 1;
$newLetter = chr($asciiValueOfnew);
     $newgroup=new Chitgroup;
     $newgroup->chit_id=$oldgroup->chit_id;
     $newgroup->group_name=$newLetter;
     $newgroup->date=$todayDate;
     $newgroup->save();
     
         return response()->json(['message' => 'This group is limit is over please bid other group'], 200);
   
 }
    }
}

       
    
    if ($group=="") 
{
    $nominee = new Bid($validatedData);
    $nominee->save();
}

  else
     {   



foreach ($Chit as $Chitdata) {
    foreach ($Chitdata->chitgroup as $chititem) {
        $chitgroupinloop = Bid::where([
            'chit_group_id' => $chititem->id,])->get();  
            foreach ($chitgroupinloop as $bid) {
                if ($bid->user_id == $request->user_id && $bid->chit_group_id == $request->chit_group_id) {
                    $nominee = new Bid($validatedData);


                }
                elseif($bid->user_id == $request->user_id && $bid->chit_group_id != $request->chit_group_id)
                {
                    return response()->json(['error' => 'User has already bid on this chit in another group'], 422);

                }
            }

    }
}

}
$JoiningUsers = JoiningUsers::where('user_id', $request->user_id)
                            ->where('chit_group_id', $request->chit_group_id)
                            ->first();  
                            if($JoiningUsers=="")
                            {
                                $newjoininguser=new JoiningUsers;
                                $newjoininguser->user_id= $request->user_id;
                                $newjoininguser->chit_group_id=$request->chit_group_id;
                                $newjoininguser->save();
                            }
       
        return response()->json(['message' => 'Bid  successfully']);  
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
