<?php

namespace App\Http\Controllers\WebApp;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Contracts\ProfileRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    protected $profileRepo;

    public function __construct(ProfileRepositoryInterface $profileRepo)
    {
        $this->profileRepo = $profileRepo;
    }

    /**
     * Show the Google-only login view
     */
    public function showLogin()
    {
        return view('webapp.auth.login');
    }

    /**
     * Redirect the user to the Google Authentication Page
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the callback from Google
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Find existing user or create a new one using CHAR(36) UUID
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'id' => (string) Str::uuid(),
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'profile_image' => $googleUser->getAvatar(),
                    'password' => bcrypt(Str::random(24)), // Dummy password for DB integrity
                    'user_type' => 3, // Defaulting to Seeker/Artist per your DB comment
                    'status' => 1,
                    'email_verified_at' => now(),
                ]);

                // Automatically create the NCS Hindi Studio Profile
                $this->profileRepo->updateProfile($user->id, [
                    'studio_name' => $user->name . ' Studio',
                    'rank_title' => 'New Artist',
                    'xp_count' => 0
                ]);
            }

            Auth::login($user);

            return redirect()->intended(route('home'));
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'email' => 'Google authentication failed. Please try again.',
            ]);
        }
    }

    /**
     * Standard logout remains the same
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
