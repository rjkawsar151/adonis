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
            if ($user && $user->role === 'reception') {
                return redirect('/admin/appointments')->with('error', 'You do not have access to that module.');
            }

            abort(403, 'Unauthorized access for your role.');
        }

        // Prevent receptionist from deleting or doing bulk delete
        if ($user && $user->role === 'reception') {
            if ($request->isMethod('delete')) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Unauthorized. Receptionists cannot delete records.'], 403);
                }
                return back()->with('error', 'Receptionists are not allowed to delete resources.');
            }
            
            if (($request->is('*bulk-action') || $request->is('*bulk*')) && $request->input('action') === 'delete') {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Unauthorized. Receptionists cannot delete records.'], 403);
                }
                return back()->with('error', 'Receptionists are not allowed to delete resources.');
            }
        }

        return $next($request);
    }
}
