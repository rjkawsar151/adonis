<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        $allowedRoles = ['super_admin', 'admin', 'hr', 'content_editor', 'reception'];
        if (Auth::check() && in_array(Auth::user()->role, $allowedRoles) && Auth::user()->status === 'active') {
            return redirect('/admin/dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $allowedRoles = ['super_admin', 'admin', 'hr', 'content_editor', 'reception'];
            
            if (in_array($user->role, $allowedRoles)) {
                if ($user->status !== 'active') {
                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'Your account has been deactivated. Please contact the administrator.',
                    ])->onlyInput('email');
                }

                $request->session()->regenerate();
                
                // Redirect Content Editor directly to blogs, HR to applications, Reception to appointments, others to dashboard
                if ($user->role === 'content_editor') {
                    return redirect()->intended('/admin/blogs');
                }
                if ($user->role === 'hr') {
                    return redirect()->intended('/admin/careers/applications');
                }
                if ($user->role === 'reception') {
                    return redirect()->intended('/admin/appointments');
                }

                return redirect()->intended('/admin/dashboard');
            }
            
            // If logged in user is not admin
            Auth::logout();
            return back()->withErrors([
                'email' => 'You do not have administrative access.',
            ])->onlyInput('email');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login')->with('success', 'Logged out successfully.');
    }
}
