<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\ResourceCategory;
use App\Models\UserResourceInteraction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ResourceController extends Controller
{
    public function index(Request $request)
    {
        $categories = ResourceCategory::withCount('resources')->get();
        
        $query = Resource::with('category');

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->has('category') && $request->category != 'all') {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $featuredResources = Resource::where('is_featured', true)->latest()->take(5)->get();
        $resources = $query->latest()->paginate(12);

        return view('resources.library.index', compact('categories', 'resources', 'featuredResources'));
    }

    public function show(Resource $resource)
    {
        $interaction = UserResourceInteraction::firstOrCreate([
            'user_id' => Auth::id(),
            'resource_id' => $resource->id
        ]);

        $interaction->update(['last_viewed_at' => now()]);
        $resource->increment('view_count');

        return view('resources.library.show', compact('resource', 'interaction'));
    }

    public function download(Resource $resource)
    {
        $resource->increment('download_count');
        return Storage::download($resource->file_path, $resource->title . '.' . $resource->file_type);
    }

    public function toggleBookmark(Resource $resource)
    {
        $interaction = UserResourceInteraction::firstOrCreate([
            'user_id' => Auth::id(),
            'resource_id' => $resource->id
        ]);

        $interaction->update(['is_bookmarked' => !$interaction->is_bookmarked]);

        return response()->json(['is_bookmarked' => $interaction->is_bookmarked]);
    }

    public function updateProgress(Request $request, Resource $resource)
    {
        $request->validate([
            'page' => 'required|integer|min:1'
        ]);

        UserResourceInteraction::where('user_id', Auth::id())
            ->where('resource_id', $resource->id)
            ->update(['last_page_read' => $request->page]);

        return response()->json(['success' => true]);
    }
}
