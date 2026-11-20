<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Education;

class EducationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'qualification' => 'required|string',
            'marital_status' => 'required|string',
            'spouse_education' => 'nullable|string',
        ]);

        $user = $request->user(); // Sanctum token se user mil jayega

        $education = Education::updateOrCreate(
            ['user_id' => $user->id],
            [
                'qualification' => $request->qualification,
                'marital_status' => $request->marital_status,
                'spouse_education' => $request->spouse_education,
            ]
        );

        return response()->json([
            'status' => true,
            'message' => 'Education details saved successfully',
            'data' => $education
        ], 200);
    }
}