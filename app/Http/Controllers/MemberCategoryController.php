<?php

namespace App\Http\Controllers;

use App\Models\MemberCategory;
use Illuminate\Http\Request;

class MemberCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = MemberCategory::all();
        return view('members.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('members.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:member_categories',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:50',
            'icon' => 'nullable|string|max:50',
        ]);

        MemberCategory::create($request->all());

        return redirect()->route('members.categories')->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MemberCategory $category)
    {
        return view('members.categories.show', ['memberCategory' => $category]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MemberCategory $category)
    {
        return view('members.categories.edit', ['memberCategory' => $category]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MemberCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:member_categories,name,' . $category->id,
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:50',
            'icon' => 'nullable|string|max:50',
        ]);

        $category->update($request->all());

        return redirect()->route('members.categories')->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MemberCategory $category)
    {
        $category->delete();
        return redirect()->route('members.categories')->with('success', 'Category deleted successfully.');
    }
}
