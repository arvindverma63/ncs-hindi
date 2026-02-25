<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  int  $type  The required user_type (0, 1, 2, or 3)
     */
    public function handle(Request $request, Closure $next, $type): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if the user's type matches the required type for the route
        // Note: $type comes from the route as a string, so we cast to int
        if ((int)$user->user_type !== (int)$type) {
            
            // Strict Redirection Logic
            return match ((int)$user->user_type) {
                0, 1    => redirect()->route('admin.dashboard'),
                2       => redirect()->route('coach.dashboard')
                            ->with('error', 'Access restricted to your dashboard.'),
                3       => redirect()->route('seeker.dashboard'), // Adjust route name as needed
                default => abort(403, 'Unauthorized action.'),
            };
        }

        return $next($request);
    }
}