<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\ResourceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ResourceManagementController extends Controller
{
    public function index()
    {
        $resources = Resource::with('category')->latest()->paginate(20);
        return view('resources.admin.index', compact('resources'));
    }

    public function create()
    {
        $categories = ResourceCategory::all();
        return view('resources.admin.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:resource_categories,id',
            'file' => 'required|file|mimes:pdf,docx,doc|max:20480', // 20MB max
            'description' => 'nullable|string',
            'is_featured' => 'boolean',
        ]);

        $file = $request->file('file');
        $path = $file->store('resources', 'public');

        $resource = Resource::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . time(),
            'category_id' => $request->category_id,
            'description' => $request->description,
            'file_path' => $path,
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'is_featured' => $request->has('is_featured'),
        ]);

        return redirect()->route('admin.resources.index')->with('success', 'Resource uploaded successfully.');
    }

    public function edit(Resource $resource)
    {
        $categories = ResourceCategory::all();
        return view('resources.admin.edit', compact('resource', 'categories'));
    }

    public function update(Request $request, Resource $resource)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:resource_categories,id',
            'file' => 'nullable|file|mimes:pdf,docx,doc|max:20480',
            'description' => 'nullable|string',
            'is_featured' => 'boolean',
        ]);

        $data = [
            'title' => $request->title,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'is_featured' => $request->has('is_featured'),
        ];

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($resource->file_path);
            $file = $request->file('file');
            $data['file_path'] = $file->store('resources', 'public');
            $data['file_type'] = $file->getClientOriginalExtension();
            $data['file_size'] = $file->getSize();
        }

        $resource->update($data);

        return redirect()->route('admin.resources.index')->with('success', 'Resource updated successfully.');
    }

    public function destroy(Resource $resource)
    {
        Storage::disk('public')->delete($resource->file_path);
        $resource->delete();
        return redirect()->route('admin.resources.index')->with('success', 'Resource deleted successfully.');
    }
}
