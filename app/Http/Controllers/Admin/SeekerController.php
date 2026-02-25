<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\SeekerRepositoryInterface;
use App\Http\Requests\Admin\StoreSeekerProfileRequest; // <--- Import this
use App\Http\Requests\Admin\UpdateSeekerProfileRequest;
use App\Models\User; // <--- Import User Model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <--- For Transactions
use Illuminate\Support\Facades\Hash; // <--- For Passwords
use App\Exports\SeekersExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Imports\SeekersImport; // Add Import
use Illuminate\Database\QueryException;

class SeekerController extends Controller
{
    protected $seekerRepo;

    public function __construct(SeekerRepositoryInterface $seekerRepo)
    {
        $this->seekerRepo = $seekerRepo;
    }

    public function index(Request $request)
    {
        // Validation for filter inputs
        $request->validate([
            'search' => 'nullable|string|max:100',
            'status' => 'nullable|in:verified,unverified',
        ]);

        $seekers = $this->seekerRepo->getAll();
        
        return view('admin.seekers.index', compact('seekers'));
    }

    public function create()
    {
        return view('admin.seekers.create');
    }


    public function store(StoreSeekerProfileRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                // 1. Create the User Account (Login Info)
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'password' => Hash::make($request->password),
                    'user_type' => 3, // 3 = Seeker
                    'email_verified_at' => now(), // Auto-verify since Admin created it
                    'status' => 1
                ]);

                $profileData = [
                    'user_id' => $user->id,
                    'company_name' => $request->company_name,
                    'business_domain' => $request->business_domain,
                    'city' => $request->city,
                    'state' => $request->state,
                    'is_verified' => $request->is_verified ?? 0,
                    'notification_preferences' => ['email' => true] // Default
                ];

                $this->seekerRepo->create($profileData);
            });

            return redirect()->route('admin.seekers.index')
                ->with('success', 'Seeker account created successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error creating seeker: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $seeker = $this->seekerRepo->findById($id);
        
        if (!$seeker) {
            return back()->with('error', 'Seeker profile not found.');
        }

        return view('admin.seekers.show', compact('seeker'));
    }

 
    public function edit($id)
    {
        $seeker = $this->seekerRepo->findById($id);

        if (!$seeker) {
            return back()->with('error', 'Seeker not found.');
        }

        return view('admin.seekers.edit', compact('seeker'));
    }


    public function update(UpdateSeekerProfileRequest $request, $id)
    {
        $seeker = $this->seekerRepo->findById($id);
        
        if (!$seeker) {
            return back()->with('error', 'Seeker not found.');
        }

        try {
            DB::transaction(function () use ($request, $seeker) {

                $userUpdateData = [
                    'name' => $request->name,
                    'phone' => $request->phone,
                ];

                if ($request->filled('password')) {
                    $userUpdateData['password'] = Hash::make($request->password);
                }

                $seeker->user->update($userUpdateData);

                $this->seekerRepo->update($id, [
                    'company_name' => $request->company_name,
                    'business_domain' => $request->business_domain,
                    'city' => $request->city,
                    'state' => $request->state,
                    'is_verified' => $request->is_verified,
                ]);
            });

            return redirect()->route('admin.seekers.index')->with('success', 'Seeker profile updated successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Update failed: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $seeker = $this->seekerRepo->findById($id);

        if (!$seeker) {
            return back()->with('error', 'Seeker not found.');
        }

        \DB::transaction(function () use ($seeker) {
            if ($seeker->user) {
                $timestamp = now()->timestamp;

                $seeker->user->email = $seeker->user->email . '_deleted_' . $timestamp;
                $seeker->user->phone = $seeker->user->phone . '_deleted_' . $timestamp;
                $seeker->user->save();
            }

            $seeker->delete(); // if using soft delete
        });

        return back()->with('success', 'Seeker account deleted.');
    }


    public function exportExcel()
    {
        return Excel::download(new SeekersExport, 'seekers_list.xlsx');
    }

    public function exportPdf()
    {
        $seekers = $this->seekerRepo->getAll(); 
        
        $pdf = Pdf::loadView('admin.seekers.pdf', compact('seekers'));
        
        return $pdf->download('seekers_list.pdf');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            Excel::import(new SeekersImport, $request->file('file'));
            return response()->json(['success' => 'Seekers imported successfully!']);
            
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMsg = 'Row ' . $failures[0]->row() . ': ' . implode(', ', $failures[0]->errors());
            return response()->json(['error' => $errorMsg], 422);

        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                return response()->json(['error' => 'Duplicate Entry Error: Email or User ID already exists.'], 422);
            }
            return response()->json(['error' => 'Database Error: ' . $e->getMessage()], 500);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error importing file: ' . $e->getMessage()], 500);
        }
    }
}