<?php

namespace App\Http\Controllers;

use App\Models\ContributionType;
use Illuminate\Http\Request;

class ContributionTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $types = ContributionType::all();
        return view('finance.types.index', compact('types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('finance.types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:contribution_types',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        ContributionType::create($request->all());

        return redirect()->route('finance.types.index')->with('success', 'Contribution type created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ContributionType $contributionType)
    {
        return view('finance.types.show', compact('contributionType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ContributionType $contributionType)
    {
        return view('finance.types.edit', compact('contributionType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ContributionType $contributionType)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:contribution_types,name,' . $contributionType->id,
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        $contributionType->update($request->all());

        return redirect()->route('finance.types.index')->with('success', 'Contribution type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContributionType $contributionType)
    {
        $contributionType->delete();
        return redirect()->route('finance.types.index')->with('success', 'Contribution type deleted successfully.');
    }
}
