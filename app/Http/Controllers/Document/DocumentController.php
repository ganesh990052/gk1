<?php

namespace App\Http\Controllers\Document;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documents = Document::with('user')->get();

        return view('documents.index', ['documents' => $documents]);
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
    public function store(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required',
                'photo' => 'file|max:20480',
                'aadhar_card_front' => 'file|max:20480',
                'aadhar_card_back' => 'file|max:20480',
                'pan_card' => 'file|max:20480',
                'bank_statement' => 'file|max:20480',
                'salary_slip' => 'file|max:20480',
            ]);

            $document = new Document;
            $document->user_id = $validatedData['user_id'];

            // Passport Photo
            if ($request->hasFile('photo')) {
                $fileName = $request->file('photo')->hashName();
                $path = $request->file('photo')->storeAs('public/documents', $fileName);
                $document->photo = 'public/documents/' . $fileName;
            }

            // Aadhar Card Front
            if ($request->hasFile('aadhar_card_front')) {
                $fileName = $request->file('aadhar_card_front')->hashName();
                $path = $request->file('aadhar_card_front')->storeAs('public/documents', $fileName);
                $document->aadhar_card_front = 'public/documents/' . $fileName;
            }

            // Aadhar Card Back
            if ($request->hasFile('aadhar_card_back')) {
                $fileName = $request->file('aadhar_card_back')->hashName();
                $path = $request->file('aadhar_card_back')->storeAs('public/documents', $fileName);
                $document->aadhar_card_back = 'public/documents/' . $fileName;
            }

            // Pan Card
            if ($request->hasFile('pan_card')) {
                $fileName = $request->file('pan_card')->hashName();
                $path = $request->file('pan_card')->storeAs('public/documents', $fileName);
                $document->pan_card = 'public/documents/' . $fileName;
            }

            // Bank Statement
            if ($request->hasFile('bank_statement')) {
                $fileName = $request->file('bank_statement')->hashName();
                $path = $request->file('bank_statement')->storeAs('public/documents', $fileName);
                $document->bank_statement = 'public/documents/' . $fileName;
            }

            // Salary Slip
            if ($request->hasFile('salary_slip')) {
                $fileName = $request->file('salary_slip')->hashName();
                $path = $request->file('salary_slip')->storeAs('public/documents', $fileName);
                $document->salary_slip = 'public/documents/' . $fileName;
            }

            $document->save();

            return response()->json(['message' => 'Documents uploaded successfully', 'document' => $document], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to upload documents', 'details' => $e->getMessage()], 500);
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
        $document = Document::findOrFail($id);
        return response()->json($document);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $currentaddId = Route::current()->parameter('id');

        $validatedData = $request->validate([
            'photo' => 'file|max:204800',
            'aadhar_card_front' => 'file|max:204800',
            'aadhar_card_back' => 'file|max:204800',
            'pan_card' => 'file|max:204800',
            'bank_statement' => 'file|max:204800',
            'salary_slip' => 'file|max:204800',
        ]);
        
        $document = Document::find($currentaddId);
        
        if (!$document) {
            return response()->json(['error' => 'Document not found'], 404);
        }
        
        // Process each file upload individually
        foreach (['photo', 'aadhar_card_front', 'aadhar_card_back', 'pan_card', 'bank_statement', 'salary_slip'] as $type) {
            if ($request->hasFile($type)) {
                if ($document->$type) {
                    Storage::delete($document->$type);
                }
        
                $document->$type = $request->file($type)->store('documents');
            }
        }
        
        // Update other non-file fields
        $document->update(array_filter($request->except(['photo', 'aadhar_card_front', 'aadhar_card_back', 'pan_card', 'bank_statement', 'salary_slip'])));
        
        return response()->json(['message' => 'Document details updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $address = Document::find($id);

        if (!$address) {
            return response()->json(['message' => 'Document detail not found'], 404);
        }
    
        $address->delete();
    
        return response()->json(['message' => 'Document deleted successfully']);
    }
}