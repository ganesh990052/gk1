<?php

namespace App\Http\Controllers\Occupation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OccupationDetail;
use App\Models\User;
use Illuminate\Support\Facades\Route;

class OccupationDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $occupations = OccupationDetail::with('user')->get();

        return view('Occupation.index', ['occupations' => $occupations]);  
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
        $validatedData = $request->validate([
            'occupation' => 'required',
            'current_employer' => 'required',
            'years_at_current_employer' => 'required',
            'monthly_income' => 'required',
            'living_status' => 'required',
            'years_at_current_residence' => 'required',
            'user_id' => 'required',
            'designation' => 'nullable|string',
            'profession_type' => 'nullable|string',
            'business_type' => 'nullable|string',
        ]);
    
        // Store the data in the database
        $occupation = new OccupationDetail($validatedData);
        $occupation->save();
    
        return response()->json(['message' => 'Occupation data saved successfully']);  
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
        $Occupation = OccupationDetail::findOrFail($id);
        return response()->json($Occupation);    
    }

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
            'designation' => 'nullable|string',
            'profession_type' => 'nullable|string',
            'business_type' => 'nullable|string',
        ]);
    
        $occupation = OccupationDetail::find($currentId);
        
        if (!$occupation) {
            return response()->json(['message' => 'Occupation detail not found'], 404);
        }

        // Use validated data for safe updating
        $occupation->update($validatedData);
    
        return response()->json(['message' => 'Occupation details updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $occupation = OccupationDetail::find($id);

        if (!$occupation) {
            return response()->json(['message' => 'Occupation detail not found'], 404);
        }
    
        $occupation->delete();
    
        return response()->json(['message' => 'Occupation detail deleted successfully']);  
    }
}