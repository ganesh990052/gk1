<?php

namespace App\Http\Controllers\Admin\Address;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CurrentAddress;
use Illuminate\Support\Facades\Route;

class CurrentAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $address=CurrentAddress::with('user')->get();

        return view('User-address.index',['address'=>$address]);
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
        //
        $request->validate([
            'house_no' => 'required',
            'building_name' => 'required',
            'street_name' => 'required',
            'city' => 'required',
            'state' => 'required',
            'postal_code' => 'required',
            'landmark' => 'required',
            'user_id' => 'required',
        ]);
    
        // Assuming you have a model named Address
        $address = new CurrentAddress([
            'house_no' => $request->input('house_no'),
            'building_name' => $request->input('building_name'),
            'street_name' => $request->input('street_name'),
            'city' => $request->input('city'),
            'state' => $request->input('state'),
            'postal_code' => $request->input('postal_code'),
            'landmark' => $request->input('landmark'),
            'user_id' => $request->input('user_id'),
        ]);
    
        $address->save();
    
        return response()->json(['message' => 'Data inserted successfully'], 201);
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
        $address = CurrentAddress::findOrFail($id);
    return response()->json($address);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        
        $currentaddId = Route::current()->parameter('id');

        // Validate and update the data in the database
        // Example validation - you should adjust it based on your needs
        $request->validate([
            'house_no' => 'required',
            'building_name' => 'required',
            'street_name' => 'required',
            'city' => 'required',
            'state' => 'required',
            'postal_code' => 'required',
            'landmark'=>'required',
        ]);
    
        // Assuming you have a BankDetail model
        $addressDetails = CurrentAddress::find($currentaddId);
        $addressDetails->update($request->all());
    
        return response()->json(['message' => 'Bank details updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $address = CurrentAddress::find($id);

        if (!$address) {
            return response()->json(['message' => 'Address detail not found'], 404);
        }
    
        $address->delete();
    
        return response()->json(['message' => 'Address detail deleted successfully']);
    }
}
