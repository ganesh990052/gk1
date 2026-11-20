<?php

namespace App\Http\Controllers\NomineeDetail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NomineeDetail;
use App\Models\User;
use Illuminate\Support\Facades\Route;

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
          $validatedData = $request->validate([
              'nominee_name' => 'required',
              'relation' => 'required',
              'contact_number' => 'required|numeric|digits:10',
              'user_id'=>'required',
              
          ]);
      
          // Store the data in the database
          $nominee = new NomineeDetail($validatedData);
          $nominee->save();
      
          return response()->json(['message' => 'Nominee data saved successfully']);  
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
}
