<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;

class OtpController extends Controller
{
    public function showLoginForm() {
        return view('auth.otp-login');
    }


    public function sendOtp(Request $request) 
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        // Check standard and trashed users
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            $isSoftDeleted = User::withTrashed()->where('email', $request->email)->exists();
            
            if ($isSoftDeleted) {
                return response()->json([
                    'status' => false, 
                    'message' => 'This account has been deactivated or deleted.'
                ], 403);
            }

            return response()->json([
                'status' => false, 
                'message' => 'User record not found in active database.'
            ], 404);
        }

        $otp = rand(100000, 999999);

        // Update using verified columns
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        try {
            Mail::to($user->email)->send(new OtpMail($otp));
            return response()->json(['status' => true, 'message' => 'OTP sent successfully.']);
        } catch (\Exception $e) {
            \Log::error("Mail Failure: " . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'SMTP Error.'], 500);
        }
    }
    public function verifyOtp(Request $request) {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|numeric'
        ]);

        $user = User::where('email', $request->email)
                    ->where('otp', $request->otp)
                    ->where('otp_expires_at', '>', now())
                    ->first();

        if (!$user) {
            return response()->json(['status' => false, 'message' => 'Invalid or expired OTP.'], 422);
        }

        // Clear OTP and login
        $user->update(['otp' => null, 'otp_expires_at' => null]);
        Auth::login($user);

        $redirectUrl = match ((int)$user->user_type) {
            0, 1    => route('admin.dashboard'),
            2       => route('coach.dashboard'),
            3       => route('seeker.dashboard'),
            default => '/',
        };

        return response()->json(['status' => true, 'redirect' => $redirectUrl]);
    }

    public function logout(Request $request) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('user.login')->with('success', 'Logged out successfully.');
    }
}