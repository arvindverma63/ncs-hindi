<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AuthController extends BaseController
{
    /**
     * Step 1: Send OTP
     * Accepts: phone
     */
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|min:10|max:15',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        $otp = '123456'; 


        $user = User::updateOrCreate(
            ['phone' => $request->phone],
            [
                'otp_code' => $otp,
                'otp_expires_at' => Carbon::now()->addMinutes(10),
            ]
        );


        return $this->success([
            'phone' => $user->phone,
            'otp' => $otp,
        ], 'OTP sent successfully');
    }

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string',
            'otp' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return $this->error('User not found. Please resend OTP.', 404);
        }

        if ($user->otp_code !== $request->otp) {
            return $this->error('Invalid OTP', 400);
        }

        if (Carbon::now()->greaterThan($user->otp_expires_at)) {
            return $this->error('OTP has expired', 400);
        }

        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->phone_verified_at = now();
        $user->save();

        if (empty($user->name) || empty($user->email)) {
            return $this->success([
                'is_registered' => false,
                'phone' => $user->phone,
            ], 'OTP Verified. Please complete registration.');
        }

        if ($user->status == 0) {
            return $this->error('Your account is inactive.', 403);
        }

        $token = $user->createToken('FitxApp')->plainTextToken;

        return $this->success([
            'is_registered' => true,
            'user' => $user->load('profile'),
            'token' => $token,
        ], 'Login successful');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|exists:users,phone', 
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422, $validator->errors());
        }

        $user = User::where('phone', $request->phone)->first();

        if (!empty($user->email)) {
             return $this->error('User already registered. Please login.', 400);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => 2, 
            'status' => 1,
            'email_verified_at' => now(), 
        ]);

     
        $user->profile()->create([
            'user_id' => $user->id
        ]);

        $token = $user->createToken('FitxApp')->plainTextToken;

        return $this->success([
            'user' => $user->load('profile'),
            'token' => $token,
        ], 'Registration completed successfully', 201);
    }
}