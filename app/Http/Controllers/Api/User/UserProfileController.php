<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user(); // Jo user logged-in hai uska data

        // Saare relations load kar le taaki status check ho sake
        $user->load(['currentaddress', 'nomineedetail', 'bankdetail', 'educationdetail', 'occupationdetail', 'document']);

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'image' => $user->image,
                'profile_status' => [
                    'address'    => $this->state($user->currentaddress),
                    'nominee'    => $this->state($user->nomineedetail),
                    'bank'       => $this->state($user->bankdetail),
                    'documents'  => $this->docState($user),
                    'education'  => $this->state($user->educationdetail),
                    'occupation' => $this->state($user->occupationdetail),
                ]
            ]
        ]);
    }

    private function state($rec) {
        $r = $rec instanceof \Illuminate\Support\Collection ? $rec->first() : $rec;
        if (!$r) return 'missing';
        return match (strtolower($r->status ?? 'approved')) {
            'approved', 'verified' => 'completed',
            'rejected' => 'rejected',
            default => 'pending',
        };
    }

    private function docState($user) {
        $doc = $user->document instanceof \Illuminate\Support\Collection ? $user->document->first() : $user->document;
        
        if (!$doc || empty($user->image)) {
            return 'missing';
        }

        $status = strtolower($doc->status ?? 'approved');
        if ($status === 'rejected') return 'rejected';
        if ($status === 'pending') return 'pending';

        return 'completed';
    }
}