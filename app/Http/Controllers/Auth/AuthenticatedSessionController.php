<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('login-belfinance');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        if ($request->filled('remember')) {
            Auth::login(Auth::user(), $request->boolean('remember'));
        }

        $request->session()->regenerate();

        // Debugging: Log user role
        \Log::info('User role after login: ' . Auth::user()->role);

        // Redirect based on role
        $user = Auth::user();
        $role = $user->role; // Assuming 'role' is a column in your users table

        if ($role === 'owner') {
            return redirect()->route('owner-beranda');
        } elseif ($role === 'admin') {
            return redirect()->route('dashboard');
        }

        // Default redirect if role is not recognized
        return redirect()->route('dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login-belfinance');
    }
};
