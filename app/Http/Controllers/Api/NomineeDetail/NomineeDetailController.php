<?php

namespace App\Http\Controllers\Api\NomineeDetail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NomineeDetail;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class NomineeDetailController extends Controller
{
    public function index()
    {
        $nominees=NomineeDetail::with('user')->get();

        return view('Nominee.index',['nominees'=>$nominees]);  

      }
      public function getAllUsers()
      {
          $users = User::all();
          return response()->json(['users' => $users]);
      } 
     public function store(Request $request)
{
    $validatedData = Validator::make($request->all(), [
        'nominee_name' => 'required',
        'relation' => 'required',
        'contact_number' => 'required|numeric|digits:10',
        'user_id' => 'required',
    ]);

    if ($validatedData->fails()) {
        return response()->json(['errors' => $validatedData->errors()], 422);
    }

    $validatedData = $validatedData->validated();

    // Check if nominee already exists for this user
    $existingNominee = NomineeDetail::where('user_id', $validatedData['user_id'])->first();

    if ($existingNominee) {
        // ✅ Update existing nominee
        $existingNominee->update([
            'nominee_name' => $validatedData['nominee_name'],
            'relation' => $validatedData['relation'],
            'contact_number' => $validatedData['contact_number'],
        ]);

        return response()->json([
            'message' => 'Nominee details updated successfully',
            'nominee' => $existingNominee
        ], 200);
    } else {
        // ✅ Create new nominee
        $nominee = NomineeDetail::create($validatedData);

        return response()->json([
            'message' => 'Nominee details added successfully',
            'nominee' => $nominee
        ], 200);
    }
}

public function getNominee($user_id)
{
    $nominee = NomineeDetail::where('user_id', $user_id)->first();

    if ($nominee) {
        return response()->json([
            'success' => true,
            'nominee' => $nominee
        ], 200);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'No nominee details found for this user.'
        ], 200);
    }
}







        public function edit(string $id)
        {
            $nominee = NomineeDetail::findOrFail($id);
            return response()->json($nominee);   
         }
         public function update(Request $request, string $id)
         {
             $currentId = Route::current()->parameter('id');
             $validatedData = $request->validate([
                 'nominee_name' => 'required',
                 'relation' => 'required',
                 'contact_number' => 'required',
              
             ]);
         
             $nominee = NomineeDetail::find($currentId);
             $nominee->update($request->all());
         
             return response()->json(['message' => 'Nominee details updated successfully']);
         }
     
         public function destroy(string $id)
         {
             $occupation = NomineeDetail::find($id);
     
             if (!$occupation) {
                 return response()->json(['message' => 'Nominee detail not found'], 404);
             }
         
             $occupation->delete();
         
             return response()->json(['message' => 'Nominee detail deleted successfully']);  
         }
         public function show(Request $request)
    {
        $validatedData = Validator::make($request->all(),[
            'user_id' => 'required',
        
        ]);
        if ($validatedData->fails()) {
            return response()->json(['errors' => $validatedData->errors()], 422);
        }
        $documents=NomineeDetail::where('user_id',$request->user_id)->first();

return response()->json($documents);
    }
}
