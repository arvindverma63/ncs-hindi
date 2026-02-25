<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\SeekerProfileInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct(protected SeekerProfileInterface $profileRepo) {}

    public function edit()
    {
        $user = Auth::user();
        $profile = $this->profileRepo->findByUserId($user->id);
        return view('seeker.profile.edit', compact('user', 'profile'));
    }

   public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'business_domain' => 'nullable|string',
            'company_name' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
        ]);

        $userData = ['name' => $request->name];

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image && file_exists(public_path($user->profile_image))) {
                unlink(public_path($user->profile_image));
            }

            $file = $request->file('profile_image');
            $fileName = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profile'), $fileName);
            $userData['profile_image'] = 'uploads/profile/' . $fileName;
        }

        $user->update($userData);

        $this->profileRepo->updateOrCreate($user->id, [
            'business_domain' => $request->business_domain,
            'company_name' => $request->company_name,
            'city' => $request->city,
            'state' => $request->state,
        ]);

        return back()->with('success', 'Profile updated successfully.');
    }
}