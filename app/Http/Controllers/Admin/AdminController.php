<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
class AdminController extends Controller
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

    try {
    $validetion=$request->validate([
'name'=>'required',
'email'=>'required|unique:admins,email',
'password'=>'required|string|min:8',
        ]);
        $validetion['password'] = Hash::make($validetion['password']);

        $admin=Admin::create($validetion);
return response()->json(['message'=>'Admin Created Seccessfully','data'=>$admin]);
    } 
    catch (ValidationException $th) {
        //throw $th;
        return response()->json(['error' => $th->validator->errors()], 422);

    }catch (QueryException $e) {
        // Handle database-related errors (e.g., unique constraint violation)
        return response()->json(['error' => 'Database error: ' . $e->getMessage()], 500);

    } catch (\Exception $e) {
        // Catch other exceptions
        return response()->json(['error' => 'Unexpected error: ' . $e->getMessage()], 500);
    }
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
