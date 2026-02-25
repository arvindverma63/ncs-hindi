<?php

namespace App\Imports;

use App\Models\User;
use App\Models\SeekerProfile;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SeekersImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $user = User::firstOrCreate(
            ['email' => $row['email']],
            [
                'name' => $row['name'],
                'password' => Hash::make('password'), // Default password
                'user_type' => 3, // Seeker
                'status' => 1,
            ]
        );

        $existingProfile = SeekerProfile::where('user_id', $user->id)->first();

        if ($existingProfile) {
            $existingProfile->update([
                'company_name'    => $row['company_name'] ?? $existingProfile->company_name,
                'business_domain' => $row['business_domain'] ?? $existingProfile->business_domain,
                'city'            => $row['city'] ?? $existingProfile->city,
                'state'           => $row['state'] ?? $existingProfile->state,
                'website_link'    => $row['website_link'] ?? $existingProfile->website_link,
            ]);
            return null; // Skip insert
        }

        return new SeekerProfile([
            'user_id'         => $user->id,
            'company_name'    => $row['company_name'] ?? null,
            'business_domain' => $row['business_domain'] ?? null,
            'city'            => $row['city'] ?? null,
            'state'           => $row['state'] ?? null,
            'website_link'    => $row['website_link'] ?? null,
            'is_verified'     => 1, 
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required|email',
            'company_name' => 'required',
            'city' => 'required',
        ];
    }
}