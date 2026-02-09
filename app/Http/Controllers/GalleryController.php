<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $gallery = Gallery::with('user')->latest()->get();
        return view('gallery.index', compact('gallery'));
    }

    public function create()
    {
        return view('gallery.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $imagePath = $imageFile->store('gallery', 'public');
            $imageName = $imageFile->getClientOriginalName();
        }

        Gallery::create([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'image_path' => $imagePath,
            'image_name' => $imageName,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('gallery.index')
            ->with('success', 'Image added to gallery successfully!');
    }

    public function show(Gallery $gallery)
    {
        return view('gallery.show', compact('gallery'));
    }

    public function edit(Gallery $gallery)
    {
        return view('gallery.edit', compact('gallery'));
    }

    public function update(Request $request, Gallery $gallery)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
        ];

        if ($request->hasFile('image')) {
            // Delete old image
            if ($gallery->image_path && Storage::disk('public')->exists($gallery->image_path)) {
                Storage::disk('public')->delete($gallery->image_path);
            }

            $imageFile = $request->file('image');
            $imagePath = $imageFile->store('gallery', 'public');
            $data['image_path'] = $imagePath;
            $data['image_name'] = $imageFile->getClientOriginalName();
        }

        $gallery->update($data);

        return redirect()->route('gallery.index')
            ->with('success', 'Gallery image updated successfully!');
    }

    public function destroy(Gallery $gallery)
    {
        // Delete image from storage
        if ($gallery->image_path && Storage::disk('public')->exists($gallery->image_path)) {
            Storage::disk('public')->delete($gallery->image_path);
        }

        $gallery->delete();

        return redirect()->route('gallery.index')
            ->with('success', 'Gallery image deleted successfully!');
    }

    public function toggleStatus(Gallery $gallery)
    {
        $gallery->update([
            'is_active' => !$gallery->is_active
        ]);

        return redirect()->route('gallery.index')
            ->with('success', 'Gallery image status updated successfully!');
    }
}