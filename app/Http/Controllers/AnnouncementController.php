<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        $activeCount = Announcement::active()->count();
        $totalCount = Announcement::count();
        $urgentCount = Announcement::where('type', 'urgent')->count();

        return view('announcements.index', compact(
            'announcements',
            'activeCount',
            'totalCount',
            'urgentCount'
        ));
    }

    public function create()
    {
        return view('announcements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:general,urgent,event',
            'target_audience' => 'required|in:all,members,staff,leadership',
            'expiry_date' => 'nullable|date|after:today',
            'is_active' => 'boolean'
        ]);

        Announcement::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'type' => $validated['type'],
            'target_audience' => $validated['target_audience'],
            'expiry_date' => $validated['expiry_date'] ?? null,
            'is_active' => $request->has('is_active'),
            'created_by' => Auth::id()
        ]);

        return redirect()->route('announcements.index')->with('success', 'Announcement created successfully');
    }

    public function show(Announcement $announcement)
    {
        return view('announcements.show', compact('announcement'));
    }

    public function edit(Announcement $announcement)
    {
        return view('announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:general,urgent,event',
            'target_audience' => 'required|in:all,members,staff,leadership',
            'expiry_date' => 'nullable|date|after:today',
            'is_active' => 'boolean'
        ]);

        $announcement->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'type' => $validated['type'],
            'target_audience' => $validated['target_audience'],
            'expiry_date' => $validated['expiry_date'] ?? null,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('announcements.index')->with('success', 'Announcement updated successfully');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return redirect()->route('announcements.index')->with('success', 'Announcement deleted successfully');
    }
}
