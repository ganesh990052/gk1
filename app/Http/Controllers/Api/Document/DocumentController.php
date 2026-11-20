<?php

namespace App\Http\Controllers\Api\Document;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $documents=Document::with('user')->get();

        return view('documents.index',['documents'=>$documents]);
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
        // dd($request->all()); die();
        try {
            $validatedData = Validator::make($request->all(), [
                'user_id' => 'required',
                'aadhar_card_front' => 'nullable|file|max:20480',
                'aadhar_card_back' => 'nullable|file|max:20480',
                'pan_card' => 'nullable|file|max:20480',
                'bank_statement' => 'nullable|file|max:20480',
                'salary_slip' => 'nullable|file|max:20480',
            ]);

            if ($validatedData->fails()) {
                return response()->json(['errors' => $validatedData->errors()], 422);
            }

            // Check if document record exists for user
            $document = Document::where('user_id', $request->user_id)->first();

            if (!$document) {
                $document = new Document();
                $document->user_id = $request->user_id;
            }

            // Helper for upload
            $uploadFile = function ($fieldName) use ($request) {
                if ($request->hasFile($fieldName)) {
                    $fileName = time() . '_' . $request->file($fieldName)->getClientOriginalName();
                    $path = $request->file($fieldName)->storeAs('public/documents', $fileName);
                    return 'public/documents/' . $fileName;
                }
                return null;
            };

            // Upload each only if provided
            if ($path = $uploadFile('aadhar_card_front')) $document->aadhar_card_front = $path;
            if ($path = $uploadFile('aadhar_card_back')) $document->aadhar_card_back = $path;
            if ($path = $uploadFile('pan_card')) $document->pan_card = $path;
            if ($path = $uploadFile('bank_statement')) $document->bank_statement = $path;
            if ($path = $uploadFile('salary_slip')) $document->salary_slip = $path;

            $document->save();

            return response()->json([
                'message' => $document->wasRecentlyCreated
                    ? 'Documents uploaded successfully'
                    : 'Documents updated successfully',
                'document' => $document
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to upload documents',
                'details' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Display the specified resource.
     */
   public function show(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'user_id' => 'required'
        ]);

        if ($validatedData->fails()) {
            return response()->json(['errors' => $validatedData->errors()], 422);
        }

        $document = Document::where('user_id', $request->user_id)->first();

        if (!$document) {
            return response()->json(['message' => 'No documents found'], 404);
        }

        return response()->json($document);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $document = Document::findOrFail($id);
    return response()->json($document);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //      
          $currentaddId = Route::current()->parameter('id');

          $validatedData = $request->validate([
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
        foreach (['aadhar_card_front', 'aadhar_card_back', 'pan_card', 'bank_statement', 'salary_slip'] as $type) {
            if ($request->hasFile($type)) {
                // Check if the existing file path is not null before deleting
                if ($document->$type) {
                    // Delete the existing file before updating
                    Storage::delete($document->$type);
                }
        
                // Store the new file
                $document->$type = $request->file($type)->store('documents');
            }
        }
        
        // Update other non-file fields
        $document->update(array_filter($request->except(['aadhar_card_front', 'aadhar_card_back', 'pan_card', 'bank_statement', 'salary_slip'])));
        
        return response()->json(['message' => 'Document details updated successfully']);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $address = Document::find($id);

        if (!$address) {
            return response()->json(['message' => 'Document detail not found'], 404);
        }
    
        $address->delete();
    
        return response()->json(['message' => 'Document  deleted successfully']);
    }
}
