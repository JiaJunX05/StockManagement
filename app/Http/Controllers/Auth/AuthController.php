<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class AuthController extends Controller
{
    public function index() {
        try {
            $user = Auth::user();

            return match ($user->role) {
                'admin' => view('admin.dashboard'),
                'staff' => view('staff.dashboard'),
                default => abort(403, 'You do not have permission to access this resource.'),
            };
        } catch (\Exception $e) {
            \Log::error('Error in AuthController@index: ' . $e->getMessage());
            return redirect()->route('login')
                            ->with('error', 'You do not have permission to access this resource.');
        }
    }

    public function showLoginForm() {
        return view('auth.login');
    }

    public function login(Request $request) {
        try {
            $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required', 'min:6'],
            ]);

            if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                \Log::error('Failed login attempt: ' . $request->email);
                return redirect()->back()
                                ->withErrors(['email' => 'The provided credentials do not match our records.'])
                                ->onlyInput('email');
            }

            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'))
                                ->with('success', 'You are logged in successfully');
            }

            if ($user->role === 'staff') {
                return redirect()->intended(route('staff.dashboard'))
                                ->with('success', 'You are logged in successfully');
            }

            Auth::logout();
            $request->session()->invalidate();
            \Log::error('Invalid role access attempt: ' . $user->email);
            return redirect()->back()
                            ->withErrors(['email' => 'Your account does not have the correct permissions.'])
                            ->onlyInput('email');

        } catch (\Exception $e) {
            \Log::error('Error in AuthController@login: ' . $e->getMessage());
            return redirect()->route('login')
                            ->withErrors(['error' => 'An error occurred while logging in. Please try again.'])
                            ->onlyInput('email');
        }
    }

    public function showRegisterForm() {
        return view('auth.register');
    }

    public function register(Request $request) {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:6', 'confirmed'],
                'role' => ['nullable', 'string', 'in:admin,staff'],
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role ?? 'staff',
            ]);

            return redirect()->route('user.index')
                            ->with('success', 'Staff member has been successfully added.');
        } catch (\Exception $e) {
            \Log::error('Error in AuthController@register: ' . $e->getMessage());
            return redirect()->back()
                            ->withErrors(['error' => 'Registration failed. Please try again.']);
        }
    }

    public function logout(Request $request) {
        try {
            $user = Auth::user();
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                            ->with('success', 'You are logged out successfully');

        } catch (\Exception $e) {
            \Log::error('Error in AuthController@logout: ' . $e->getMessage());
            return redirect()->route('login')
                            ->withErrors(['error' => 'An error occurred during logout. Please try again.'])
                            ->onlyInput('email');
        }
    }
}

