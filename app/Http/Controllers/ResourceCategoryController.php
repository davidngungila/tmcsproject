<?php

namespace App\Http\Controllers;

use App\Models\ResourceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ResourceCategoryController extends Controller
{
    public function index()
    {
        $categories = ResourceCategory::withCount('resources')->latest()->paginate(20);
        return view('resources.admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('resources.admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:resource_categories,name',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        ResourceCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'icon' => $request->icon,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.resource-categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(ResourceCategory $resourceCategory)
    {
        return view('resources.admin.categories.edit', compact('resourceCategory'));
    }

    public function update(Request $request, ResourceCategory $resourceCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:resource_categories,name,' . $resourceCategory->id,
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $resourceCategory->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'icon' => $request->icon,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.resource-categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(ResourceCategory $resourceCategory)
    {
        if ($resourceCategory->resources()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete category with associated resources.');
        }

        $resourceCategory->delete();
        return redirect()->route('admin.resource-categories.index')->with('success', 'Category deleted successfully.');
    }
}
