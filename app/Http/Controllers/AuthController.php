<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Show specified view.
     */
    public function loginView(): View
    {
        return view('login.main', [
            'layout' => 'base'
        ]);
    }
    
    public function customerLoginView(): View
    {
        return view('login.customer', [
            'layout' => 'base'
        ]);
    }

    /**
     * Authenticate login user.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function login(Request $request)
    {
        if (auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect('/');
        }
        return back()->withErrors(['password' => 'Invalid credentials']);
    }

    public function customerLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (auth()->guard('client')->attempt([
            'email' => $request->email,
            'password' => $request->password,
        ])) {
            return redirect()->route('customer.dashboard');
        }

        return back()->withErrors([
            'password' => 'Invalid credentials',
        ]);
    }
  
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.index')->with('success', 'Logged out successfully.');
    }

    public function customerLogout(Request $request)
    {
        if (auth()->guard('client')->check()) {
            auth()->guard('client')->logout();
        } else {
            auth()->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('customer.login.index');
    }
}
