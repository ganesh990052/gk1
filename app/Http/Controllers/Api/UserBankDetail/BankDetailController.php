<?php

namespace App\Http\Controllers\Api\UserBankDetail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BankDetail;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

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
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer',
                'account_number' => 'required|string',
                'account_holder_name' => 'required|string',
                'bank_name' => 'required|string',
                'branch_name' => 'required|string',
                'ifsc_code' => 'required|string',
                'type_of_account' => 'required|string',
            ]);
        
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
        
            try {
                // Use updateOrCreate to handle insert OR update automatically
                $bankDetail = BankDetail::updateOrCreate(
                    ['user_id' => $request->user_id], // condition to check
                    [ // fields to update or insert
                        'account_number' => $request->account_number,
                        'account_holder_name' => $request->account_holder_name,
                        'bank_name' => $request->bank_name,
                        'branch_name' => $request->branch_name,
                        'ifsc_code' => $request->ifsc_code,
                        'type_of_account' => $request->type_of_account,
                    ]
                );
        
                // Detect whether it was created or updated
                $message = $bankDetail->wasRecentlyCreated
                    ? 'Bank detail added successfully'
                    : 'Bank detail updated successfully';
        
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => $bankDetail
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong',
                    'error' => $e->getMessage()
                ], 500);
            }
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
  public function show(Request $request)
{
    $validator = Validator::make($request->all(), [
        'user_id' => 'required',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $bankDetail = BankDetail::where('user_id', $request->user_id)->first();

    if ($bankDetail) {
        return response()->json($bankDetail);
    } else {
        return response()->json(['message' => 'No bank detail found'], 404);
    }
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
