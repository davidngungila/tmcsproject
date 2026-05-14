<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\ElectionCandidate;
use App\Models\ElectionVote;
use Illuminate\Http\Request;

class ElectionController extends Controller
{
    public function index()
    {
        $elections = Election::latest()->paginate(10);
        return view('elections.index', compact('elections'));
    }

    public function create()
    {
        return view('elections.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'positions_raw' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'description' => 'nullable|string',
        ]);

        $positions = array_filter(array_map('trim', explode("\n", $validated['positions_raw'])));

        Election::create([
            'title' => $validated['title'],
            'positions' => $positions,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'description' => $validated['description'],
            'created_by' => auth()->id(),
            'status' => 'Scheduled',
        ]);

        return redirect()->route('elections.index')->with('success', 'Election created successfully');
    }

    public function results()
    {
        return view('elections.results');
    }

    public function vote(Election $election)
    {
        return view('elections.vote', compact('election'));
    }
}
