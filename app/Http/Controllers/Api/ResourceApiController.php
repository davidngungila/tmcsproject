<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\ResourceCategory;
use App\Models\UserResourceInteraction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ResourceApiController extends Controller
{
    /**
     * Display a listing of resources.
     */
    public function index(Request $request)
    {
        $query = Resource::with('category');

        // Search filter
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->has('category_slug') && $request->category_slug != 'all') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category_slug);
            });
        }

        // Featured filter
        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        $resources = $query->latest()->paginate($request->get('per_page', 15));

        // Add interaction data for the authenticated user
        $user = Auth::user();
        $resources->getCollection()->transform(function ($resource) use ($user) {
            $interaction = UserResourceInteraction::where('user_id', $user->id)
                ->where('resource_id', $resource->id)
                ->first();
            
            $resource->is_bookmarked = $interaction ? (bool) $interaction->is_bookmarked : false;
            $resource->last_page_read = $interaction ? $interaction->last_page_read : null;
            $resource->file_url = $resource->file_path ? asset('storage/' . $resource->file_path) : null;
            return $resource;
        });

        return response()->json([
            'status' => 'success',
            'data' => $resources
        ]);
    }

    /**
     * Get all resource categories.
     */
    public function categories()
    {
        $categories = ResourceCategory::withCount('resources')->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $categories
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Resource $resource)
    {
        $resource->load('category');
        
        $interaction = UserResourceInteraction::firstOrCreate([
            'user_id' => Auth::id(),
            'resource_id' => $resource->id
        ]);

        $interaction->update(['last_viewed_at' => now()]);
        $resource->increment('view_count');

        $resource->is_bookmarked = (bool) $interaction->is_bookmarked;
        $resource->last_page_read = $interaction->last_page_read;
        $resource->personal_notes = $interaction->personal_notes;
        $resource->file_url = $resource->file_path ? asset('storage/' . $resource->file_path) : null;

        return response()->json([
            'status' => 'success',
            'data' => $resource
        ]);
    }

    /**
     * Toggle bookmark for a resource.
     */
    public function toggleBookmark(Resource $resource)
    {
        $interaction = UserResourceInteraction::firstOrCreate([
            'user_id' => Auth::id(),
            'resource_id' => $resource->id
        ]);

        $interaction->update(['is_bookmarked' => !$interaction->is_bookmarked]);

        return response()->json([
            'status' => 'success',
            'message' => $interaction->is_bookmarked ? 'Resource bookmarked' : 'Bookmark removed',
            'is_bookmarked' => (bool) $interaction->is_bookmarked
        ]);
    }

    /**
     * Update reading progress for a resource.
     */
    public function updateProgress(Request $request, Resource $resource)
    {
        $request->validate([
            'page' => 'required|integer|min:1'
        ]);

        $interaction = UserResourceInteraction::firstOrCreate([
            'user_id' => Auth::id(),
            'resource_id' => $resource->id
        ]);

        $interaction->update(['last_page_read' => $request->page]);

        return response()->json([
            'status' => 'success',
            'message' => 'Progress updated',
            'last_page_read' => $interaction->last_page_read
        ]);
    }

    /**
     * Update personal notes for a resource.
     */
    public function updateNotes(Request $request, Resource $resource)
    {
        $request->validate([
            'notes' => 'nullable|string'
        ]);

        $interaction = UserResourceInteraction::firstOrCreate([
            'user_id' => Auth::id(),
            'resource_id' => $resource->id
        ]);

        $interaction->update(['personal_notes' => $request->notes]);

        return response()->json([
            'status' => 'success',
            'message' => 'Notes updated',
            'personal_notes' => $interaction->personal_notes
        ]);
    }

    /**
     * Get download info and increment download count.
     */
    public function download(Resource $resource)
    {
        if (!$resource->file_path || !Storage::disk('public')->exists($resource->file_path)) {
            return response()->json([
                'status' => 'error',
                'message' => 'File not found'
            ], 404);
        }

        $resource->increment('download_count');

        return response()->json([
            'status' => 'success',
            'download_url' => asset('storage/' . $resource->file_path),
            'file_name' => $resource->title . '.' . $resource->file_type,
            'file_type' => $resource->file_type,
            'file_size' => $resource->file_size
        ]);
    }
}
