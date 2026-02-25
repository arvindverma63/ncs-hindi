<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
use App\Http\Requests\Auth\LoginRequest as AuthLoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(AuthLoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        Log::info('Admin login attempt detected', ['email' => $credentials['email']]);

        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $admin = Auth::guard('admin')->user();

            Log::info('Admin login successful', ['admin_id' => $admin->id]);

            activity()
                ->causedBy($admin)
                ->log('Admin logged in');

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Welcome back!');
        }

        Log::warning('Admin login failed', ['email' => $credentials['email']]);

        return back()
            ->withErrors(['email' => 'The provided credentials do not match our records.'])
            ->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        $admin = Auth::guard('admin')->user();

        if ($admin) {
            Log::info('Admin logging out', ['admin_id' => $admin->id]);

            activity()
                ->causedBy($admin)
                ->log('Admin logged out');
        }

        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'You have been logged out successfully.');
    }

    public function updateFcm(Request $request)
    {
        $request->validate([
            'fcm' => 'required|string',
        ]);

        $user = Auth::user();

        if (!$user) {
            Log::error('FCM update failed: User unauthenticated');

            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated user.'
            ], 401);
        }

        Log::info('Updating FCM token for user', ['user_id' => $user->id]);

        $user->fcm = $request->fcm;
        $user->save();

        Log::info('FCM token updated successfully', ['user_id' => $user->id]);

        return response()->json([
            'success' => true,
            'message' => 'FCM token updated successfully.',
            'data' => [
                'user_id' => $user->id,
                'fcm' => $user->fcm,
            ]
        ]);
    }
}
