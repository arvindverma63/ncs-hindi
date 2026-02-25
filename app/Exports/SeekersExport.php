<?php

namespace App\Exports;

use App\Models\SeekerProfile;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SeekersExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        // Eager load 'user' relationship to get name/email
        return SeekerProfile::with('user')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
            'Phone',
            'Company Name',
            'Domain',
            'City',
            'State',
            'Verified',
            'Joined Date',
        ];
    }

    public function map($seeker): array
    {
        return [
            $seeker->id,
            $seeker->user->name ?? 'N/A',
            $seeker->user->email ?? 'N/A',
            $seeker->user->phone ?? 'N/A',
            $seeker->company_name,
            $seeker->business_domain,
            $seeker->city,
            $seeker->state,
            $seeker->is_verified ? 'Yes' : 'No',
            $seeker->created_at->format('d M Y'),
        ];
    }
}