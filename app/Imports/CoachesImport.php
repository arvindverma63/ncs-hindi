<?php

namespace App\Imports;

use App\Models\User;
use App\Models\CoachProfile;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CoachesImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // 1. Find existing User or Create new one
        $user = User::firstOrCreate(
            ['email' => $row['email']],
            [
                'name' => $row['name'],
                'password' => Hash::make('password'), // You might want to randomize this or send via email
                'user_type' => 2, // Coach
                'status' => 1,
            ]
        );

        // 2. Check if this user already has a Coach Profile
        $existingProfile = CoachProfile::where('user_id', $user->id)->first();

        if ($existingProfile) {
            // UPDATE existing profile data
            $existingProfile->update([
                'designation'      => $row['designation'] ?? $existingProfile->designation,
                'company_name'     => $row['company_name'] ?? $existingProfile->company_name,
                'city'             => $row['city'] ?? $existingProfile->city,
                'experience_years' => $row['experience_years'] ?? $existingProfile->experience_years,
                'linkedin_url'     => $row['linkedin_url'] ?? $existingProfile->linkedin_url,
            ]);

            // Return null so Laravel Excel knows NOT to attempt an insert
            return null;
        }

        // 3. If no profile exists, create a NEW one
        return new CoachProfile([
            'user_id'          => $user->id,
            'designation'      => $row['designation'] ?? null,
            'company_name'     => $row['company_name'] ?? null,
            'city'             => $row['city'] ?? null,
            'experience_years' => $row['experience_years'] ?? 0,
            'linkedin_url'     => $row['linkedin_url'] ?? null,
            'approval_status'  => 'approved',
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required|email',
            'designation' => 'required',
            'city' => 'required',
        ];
    }
}