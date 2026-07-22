<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $module
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $module)
    {
        $user = Auth::user();

        if (!$user || !$user->hasModuleAccess($module)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized access for your role.'], 403);
            }

            // Redirect based on role
            if ($user && $user->role === 'content_editor') {
                return redirect('/admin/blogs')->with('error', 'You do not have access to that module.');
            }
            if ($user && $user->role === 'hr') {
                return redirect('/admin/careers/applications')->with('error', 'You do not have access to that module.');
            }

            abort(403, 'Unauthorized access for your role.');
        }

        return $next($request);
    }
}
