<?php

namespace App\Exports;

use App\Models\CoachProfile; // Assuming your model is CoachProfile
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CoachesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        // Eager load 'user' to get login details
        return CoachProfile::with('user')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
            'Phone',
            'Designation',
            'Company',
            'City',
            'Experience (Yrs)',
            'Status',
            'Joined Date',
        ];
    }

    public function map($coach): array
    {
        return [
            $coach->id,
            $coach->user->name ?? 'N/A',
            $coach->user->email ?? 'N/A',
            $coach->user->phone ?? 'N/A',
            $coach->designation,
            $coach->company_name,
            $coach->city,
            $coach->experience_years,
            ucfirst($coach->approval_status), // 'pending', 'approved', etc.
            $coach->created_at->format('d M Y'),
        ];
    }
}