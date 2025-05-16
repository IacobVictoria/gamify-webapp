<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserStatusChangedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
         
            // take the cart from cookie
            $cookieCart = json_decode($request->cookie('cart_' . auth()->id(), '[]'), true);
            // Combinăm coșul din cookie cu cel din sesiune
            $currentCart = session()->get('cart', []);
            session(['cart' => array_merge($currentCart, $cookieCart)]);
            //

            if (auth()->user()->hasRole('Admin')) {
                return redirect()->intended(route('admin.dashboard', absolute: false));
            }

            if (auth()->user()->hasRole('Admin-Gamification')) {
                return redirect()->intended(route('admin-gamification.dashboard', absolute: false));
            }

            if (auth()->user()->hasRole('User')) {
                return redirect()->intended(route('user.dashboard', absolute: false));
            }

            if (auth()->user()->hasRole('Super-Admin')) {
                return redirect()->intended(route('super-admin.dashboard', absolute: false));
            }

        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();
        Cache::forget('user_activity_' . $user->id);
        broadcast(new UserStatusChangedEvent($user, 'Offline'))->toOthers();
        
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        //cosul din sesiune salveaza l in cookie
        $cart = session()->get('cart', []);

        return redirect('/')
            ->withCookie(cookie('cart_' . auth()->id(), json_encode($cart), 60 * 24 * 30))
            ->with('success', 'Logged out successfully and cart saved.');
    }
}
