<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // public function index() {
    //     $user = Auth::user();

    //     return match ($user->role) {
    //         'admin' => view('admin.dashboard'),
    //         'staff' => view('staff.dashboard'),
    //         default => abort(403, 'You do not have permission to access this resource.'),
    //     };
    // }

    private const ROLE_ADMIN = 'admin';
    private const ROLE_STAFF = 'staff';

    /** Show the login form. */
    public function showLoginForm() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return $this->redirectBasedOnRole(Auth::user());
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegisterForm() {
        return view('auth.register');
    }

    public function register(Request $request) {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            return redirect()->route('admin.dashboard')
                           ->with('success', 'Registration successful. Please login.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Registration failed. Please try again.']);
        }
    }

    private function redirectBasedOnRole(?User $user): \Illuminate\Http\RedirectResponse
    {
        if (!$user) {
            return $this->logoutWithError(request());
        }

        return match ($user->role) {
            'admin' => redirect()->intended(route('admin.dashboard'))
                                ->with('success', 'You are logged in successfully'),
            'staff' => redirect()->intended(route('staff.dashboard'))
                                ->with('success', 'You are logged in successfully'),
            default => $this->logoutWithError(request()),
        };
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('login'))->with('success', 'You are logged out successfully');
    }

    private function logoutWithError(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        return back()->withErrors([
            'email' => 'Your account does not have the correct permissions.',
        ])->onlyInput('email');
    }
}

