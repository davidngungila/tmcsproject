<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckProfileCompletion
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip check if user is not authenticated or not a member
        if (!Auth::check() || !Auth::user()->member) {
            return $next($request);
        }

        // Skip check if already on profile edit page
        if ($request->is('member/profile*')) {
            return $next($request);
        }

        // Check if profile is completed
        $member = Auth::user()->member;
        if (!$member->profile_completed) {
            return redirect()->route('member.profile.edit')
                ->with('info', 'Please complete your profile to access all features.');
        }

        return $next($request);
    }
}
