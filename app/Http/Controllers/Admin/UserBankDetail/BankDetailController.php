<?php

namespace App\Http\Controllers\UserBankDetail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BankDetail;
use App\Models\User;

use Illuminate\Support\Facades\Route;

class BankDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
$BankDetail=BankDetail::with('user')->get();

        return view('Bank-detalis.index',['bankdetail'=>$BankDetail]);
    }
    public function getAllUsers()
    {
        $users = User::all();
        return response()->json(['users' => $users]);
    } 
    public function addBankDetail(Request $request)
    {
        // Validate the request data
        $request->validate([
            'user_id' => 'required',
            'account_number' => 'required',
            'account_holder_name' => 'required',
            'bank_name' => 'required',
            'branch_name' => 'required',
            'ifsc_code' => 'required',
            'type_of_account' => 'required',
            // Add other validation rules as needed
        ]);

        // Create a new bank detail record
        BankDetail::create($request->all());

        return response()->json(['message' => 'Bank detail added successfully']);
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
        $bankDetail = BankDetail::findOrFail($id);
    return response()->json($bankDetail);
    }
    public function get_by_user(string $id)
    {
    $bankDetail = BankDetail::where('user_id', $id)->first();
        return response()->json($bankDetail);
        
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $currentBankId = Route::current()->parameter('id');

        // Validate and update the data in the database
        // Example validation - you should adjust it based on your needs
        $request->validate([
            'account_number' => 'required',
            'bank_name' => 'required',
            'account_holder_name' => 'required',
            'branch_name' => 'required',
            'ifsc_code' => 'required',
            'type_of_account' => 'required',
        ]);
    
        // Assuming you have a BankDetail model
        $bankDetail = BankDetail::find($currentBankId);
        $bankDetail->update($request->all());
    
        return response()->json(['message' => 'Bank details updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bankDetail = BankDetail::find($id);

        if (!$bankDetail) {
            return response()->json(['message' => 'Bank detail not found'], 404);
        }
    
        $bankDetail->delete();
    
        return response()->json(['message' => 'Bank detail deleted successfully']);    }
}
