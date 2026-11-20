<?php

namespace App\Http\Controllers\Api\Occupation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OccupationDetail;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class OccupationDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $occupations=OccupationDetail::with('user')->get();

        return view('Occupation.index',['occupations'=>$occupations]);  
      }

      public function getAllUsers()
      {
          $users = User::all();
          return response()->json(['users' => $users]);
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
        $validatedData = Validator::make($request->all(), [
            'occupation' => 'required',
            'current_employer' => 'required',
            'years_at_current_employer' => 'required',
            'monthly_income' => 'required',
            'living_status' => 'required',
            'years_at_current_residence' => 'required',
            'user_id' => 'required',
        ]);

        if ($validatedData->fails()) {
            return response()->json(['errors' => $validatedData->errors()], 422);
        }

        $data = $validatedData->validated();

        // Check if user record exists
        $occupation = OccupationDetail::where('user_id', $data['user_id'])->first();

        if ($occupation) {
            // Update existing record
            $occupation->update($data);
            return response()->json([
                'message' => 'Occupation data updated successfully',
                'occupation' => $occupation
            ]);
        } else {
            // Create new record
            $occupation = OccupationDetail::create($data);
            return response()->json([
                'message' => 'Occupation data saved successfully',
                'occupation' => $occupation
            ]);
        }
    }

    // ✅ Show occupation detail by user_id
    public function show(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);

        if ($validatedData->fails()) {
            return response()->json(['errors' => $validatedData->errors()], 422);
        }

        $occupation = OccupationDetail::where('user_id', $request->user_id)->first();

        if ($occupation) {
            return response()->json($occupation);
        } else {
            return response()->json(null);
        }
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $Occupation = OccupationDetail::findOrFail($id);
        return response()->json($Occupation);    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $currentId = Route::current()->parameter('id');
        $validatedData = $request->validate([
            'occupation' => 'required',
            'current_employer' => 'required',
            'years_at_current_employer' => 'required',
            'monthly_income' => 'required',
            'living_status' => 'required',
            'years_at_current_residence' => 'required',
        ]);
    
        $occupation = OccupationDetail::find($currentId);
        $occupation->update($request->all());
    
        return response()->json(['message' => 'occupation details updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $occupation = OccupationDetail::find($id);

        if (!$occupation) {
            return response()->json(['message' => 'occupation detail not found'], 404);
        }
    
        $occupation->delete();
    
        return response()->json(['message' => 'occupation detail deleted successfully']);  
    }
}
