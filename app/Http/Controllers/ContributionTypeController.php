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
    public function show(ContributionType $type, Request $request)
    {
        $query = $type->contributions()->with('member');

        // Filtering
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('receipt_number', 'like', "%$search%")
                  ->orWhereHas('member', function($mq) use ($search) {
                      $mq->where('full_name', 'like', "%$search%");
                  });
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('contribution_date', $request->date);
        }

        $contributions = $query->latest()->paginate(15);
        
        // Statistics
        $totalCollected = $type->contributions()->sum('amount');
        $thisMonth = $type->contributions()->whereMonth('contribution_date', now()->month)->sum('amount');
        $thisYear = $type->contributions()->whereYear('contribution_date', now()->year)->sum('amount');
        
        // Chart Data (Last 6 Months)
        $monthlyTrend = $type->contributions()
            ->selectRaw('MONTH(contribution_date) as month, SUM(amount) as total')
            ->whereYear('contribution_date', date('Y'))
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $monthlyTrend[$i] ?? 0;
        }

        return view('finance.types.show', compact('type', 'contributions', 'totalCollected', 'thisMonth', 'thisYear', 'chartData'));
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
