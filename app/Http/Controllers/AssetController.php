<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssetController extends Controller
{
    public function index()
    {
        $assets = Asset::paginate(10);
        $totalAssets = Asset::count();
        $totalValue = Asset::sum('purchase_cost');
        $assignedAssets = Asset::whereNotNull('assigned_to')->count();
        $maintenanceAssets = Asset::where('status', 'Maintenance')->count();
        return view('assets.index', compact('assets', 'totalAssets', 'totalValue', 'assignedAssets', 'maintenanceAssets'));
    }

    public function create()
    {
        return view('assets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'serial_number' => 'nullable|string|unique:assets,serial_number',
            'category' => 'required|string',
            'purchase_date' => 'nullable|date',
            'purchase_cost' => 'nullable|numeric',
            'location' => 'nullable|string',
            'status' => 'required|string',
        ]);

        $validated['created_by'] = Auth::id();

        Asset::create($validated);

        return redirect()->route('assets.index')->with('success', 'Asset added successfully');
    }

    public function maintenance()
    {
        return view('assets.maintenance');
    }

    public function assignments()
    {
        return view('assets.assignments');
    }
}
