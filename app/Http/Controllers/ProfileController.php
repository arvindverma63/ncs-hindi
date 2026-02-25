<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Actions\User\UpdateUserProfileAction; 
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Validation\Rules\Password;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('admin.profile.index', [
            'user' => $request->user(),
        ]);
    }

    public function update(
        ProfileUpdateRequest $request,
        UpdateUserProfileAction $action
    ): RedirectResponse {
        try {
            $action->handle(
                $request->user(),
                $request->validated(),
                $request->file('profile')
            );

            return redirect()->route('admin.profile.edit')
                ->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Update failed: ' . $e->getMessage());
        }
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        try {
            $request->user()->update([
                'password' => Hash::make($validated['password']),
            ]);

            return back()->with('success', 'Password changed successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update password: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        try {
            $user = $request->user();

            Auth::logout();

            $media = $user->media()->where('collection_name', 'profile')->first();

            if ($media) {
                if (File::exists(public_path($media->file_name))) {
                    File::delete(public_path($media->file_name));
                }
                $media->delete();
            }

            $user->delete();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/')->with('success', 'Account deleted successfully.');
        } catch (\Exception $e) {

            return redirect('/')->with('error', 'Error deleting account: ' . $e->getMessage());
        }
    }
}
