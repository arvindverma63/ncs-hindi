<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\CoachProfileRepositoryInterface;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    protected $profileRepo;

    public function __construct(CoachProfileRepositoryInterface $profileRepo)
    {
        $this->profileRepo = $profileRepo;
    }

    public function edit()
    {
        $user = $this->profileRepo->getProfile(Auth::id());
        $categories = Category::where('is_active', 1)->get();

        return view('coach.profile.edit', compact('user', 'categories'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'company_name' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'gender' => 'nullable|string|in:male,female,other',
            'bio' => 'nullable|string',
            'experience_years' => 'nullable|integer',
            'linkedin_url' => 'nullable|url',
            'website_url' => 'nullable|url',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'country' => 'nullable|string',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
        ]);

        $user = Auth::user();
        $userData = $request->only(['name', 'email']);

        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $filename = time() . '_' . Auth::id() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profile'), $filename);
            $userData['profile_image'] = 'uploads/profile/' . $filename;
        }

        $profileData = $request->only([
            'company_name', 'designation', 'city', 'state', 'country',
            'bio', 'linkedin_url', 'website_url', 'experience_years', 'gender',
            'show_personal_details'
        ]);

        $profileData['show_personal_details'] = $request->has('show_personal_details');

        $this->profileRepo->updateProfile(Auth::id(), $userData, $profileData);

        $user->coachProfile->categories()->sync($request->categories);

        return back()->with('success', 'Profile updated successfully.');
    }
}