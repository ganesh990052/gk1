<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Chit;
use App\Models\Chitgroup;
class TestControoler extends Controller
{
    public function index()
    {
$chits = Chit::with('chitdetail','chitgroup.bid')->get();

foreach ($chits as $chitData) {
    // Process chit data
   
    // Process chit details
    foreach ($chitData['chitdetail'] as $detail) {
    
        // Process other chit detail fields
    }
    // Process chit groups
    foreach ($chitData['chitgroup'] as $group) {
       
  $encounteredUserIds = [];

    // Process chit group bids
    foreach ($group['bid'] as $bid) {
        // Check if the bid's chit_group_id matches the current group's id
      if (!in_array($bid->user_id, $encounteredUserIds) && $bid->chit_group_id == $group->id) {
            // Store the user ID in the encountered array
            $encounteredUserIds[] = $bid->user_id;
        }
    }

   
     $uniqueUserCount = count($encounteredUserIds);
// echo "Group ID: $group->id - Unique User Count: $uniqueUserCount"; 
 
//  echo   $uniqueUserCount % $chitData['chitdetail'][0]->duration;


 if($uniqueUserCount % $chitData['chitdetail'][0]->duration==0 && $group->status!=0)
 {
     $oldgroup=Chitgroup::find($group->id);
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
     
     
   
 }
    }
}
    }
}
