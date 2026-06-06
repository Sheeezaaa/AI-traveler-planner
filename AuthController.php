<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Welcome to TripNova! Your account has been created.');
    }

    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|string|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            if ($user->name === 'Traveler' || empty($user->name)) {
                $user->name = $request->name;
                $user->save();
            }
        } else {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make(Str::random(20)),
            ]);
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'))
            ->with('success', 'Welcome back, ' . $user->name . '! Safe travels ahead.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Logged out successfully. Safe travels!');
    }

    public function dashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('info', 'Please login first to access your dashboard.');
        }

        $user  = Auth::user();
        $trips = $user->trips()->with(['destination', 'itinerary'])->latest()->get();

        return view('dashboard', compact('user', 'trips'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // SOCIAL LOGIN — Google / Facebook / Twitter
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Redirect the user to the OAuth provider's login page.
     * Route: GET /auth/{provider}
     * Supported providers: google | facebook | twitter
     */
    public function redirectToProvider(string $provider)
    {
        $this->validateProvider($provider);
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle the callback from the OAuth provider.
     * Route: GET /auth/{provider}/callback
     */
    public function handleProviderCallback(string $provider)
    {
        $this->validateProvider($provider);

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Social login failed. Please try again.');
        }

        // Find existing user by provider ID or email
        $user = User::where('provider', $provider)
                    ->where('provider_id', $socialUser->getId())
                    ->first();

        if (!$user) {
            // Try to find by email (in case they previously registered normally)
            $user = User::where('email', $socialUser->getEmail())->first();

            if ($user) {
                // Link this social account to the existing user
                $user->update([
                    'provider'    => $provider,
                    'provider_id' => $socialUser->getId(),
                    'avatar'      => $user->avatar ?? $socialUser->getAvatar(),
                ]);
            } else {
                // Create a brand new user from social data
                $user = User::create([
                    'name'        => $socialUser->getName() ?? $socialUser->getNickname() ?? 'Traveler',
                    'email'       => $socialUser->getEmail(),
                    'password'    => Hash::make(Str::random(24)),
                    'provider'    => $provider,
                    'provider_id' => $socialUser->getId(),
                    'avatar'      => $socialUser->getAvatar(),
                ]);
            }
        }

        Auth::login($user, true);

        return redirect()->intended(route('dashboard'))
            ->with('success', 'Welcome, ' . $user->name . '! You have signed in with ' . ucfirst($provider) . '.');
    }

    /**
     * Only allow the three supported providers.
     */
    private function validateProvider(string $provider): void
    {
        if (!in_array($provider, ['google', 'facebook', 'twitter'])) {
            abort(404);
        }
    }
}
