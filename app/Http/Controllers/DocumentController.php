<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    /**
     * Display a listing of the documents.
     */
    public function index()
    {
        $documents = Document::with('user')->latest()->get();
        return view('documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new document.
     */
    public function create()
    {
        return view('documents.create');
    }

   public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'document_file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:20480', // Increased to 20MB
            'preview_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // Increased to 5MB
        ]);

        // Handle document file upload
        if ($request->hasFile('document_file')) {
            $documentFile = $request->file('document_file');
            $documentPath = $documentFile->store('documents', 'public');
            $fileName = $documentFile->getClientOriginalName();
            $fileSize = $documentFile->getSize();
            $fileType = $documentFile->getClientMimeType();
        }

        // Handle preview image upload
        $imagePath = null;
        if ($request->hasFile('preview_image')) {
            $imagePath = $request->file('preview_image')->store('document-images', 'public');
        }

        Document::create([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'file_path' => $documentPath,
            'file_name' => $fileName,
            'file_size' => $fileSize,
            'file_type' => $fileType,
            'image_path' => $imagePath,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('documents.index')
            ->with('success', 'Document uploaded successfully!');
    }

    /**
     * Display the specified document.
     */
    public function show(Document $document)
    {
        return view('documents.show', compact('document'));
    }

    /**
     * Show the form for editing the specified document.
     */
    public function edit(Document $document)
    {
        return view('documents.edit', compact('document'));
    }

     public function update(Request $request, Document $document)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'document_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:20480', // Increased to 20MB
            'preview_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // Increased to 5MB
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
        ];

        // Handle document file update
        if ($request->hasFile('document_file')) {
            // Delete old file
            if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }

            $documentFile = $request->file('document_file');
            $documentPath = $documentFile->store('documents', 'public');
            $data['file_path'] = $documentPath;
            $data['file_name'] = $documentFile->getClientOriginalName();
            $data['file_size'] = $documentFile->getSize();
            $data['file_type'] = $documentFile->getClientMimeType();
        }

        // Handle preview image update
        if ($request->hasFile('preview_image')) {
            // Delete old image
            if ($document->image_path && Storage::disk('public')->exists($document->image_path)) {
                Storage::disk('public')->delete($document->image_path);
            }

            $imagePath = $request->file('preview_image')->store('document-images', 'public');
            $data['image_path'] = $imagePath;
        }

        $document->update($data);

        return redirect()->route('documents.index')
            ->with('success', 'Document updated successfully!');
    }

    /**
     * Remove the specified document from storage.
     */
    public function destroy(Document $document)
    {
        // Delete files from storage
        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }
        if ($document->image_path && Storage::disk('public')->exists($document->image_path)) {
            Storage::disk('public')->delete($document->image_path);
        }

        $document->delete();

        return redirect()->route('documents.index')
            ->with('success', 'Document deleted successfully!');
    }

    /**
     * Download the specified document.
     */
    public function download(Document $document)
    {
        if (Storage::disk('public')->exists($document->file_path)) {
            return Storage::disk('public')->download($document->file_path, $document->file_name);
        }

        return redirect()->back()->with('error', 'File not found!');
    }
}