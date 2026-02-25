<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCoachProfileRequest;
use App\Http\Requests\Admin\UpdateCoachProfileRequest;
use App\Repositories\Contracts\CoachRepositoryInterface;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Exports\CoachesExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Imports\CoachesImport; 
use Illuminate\Database\QueryException;

class CoachController extends Controller
{
    protected $coachRepo;
    protected $categoryRepo;

    public function __construct(
        CoachRepositoryInterface $coachRepo, 
        CategoryRepositoryInterface $categoryRepo
    ) {
        $this->coachRepo = $coachRepo;
        $this->categoryRepo = $categoryRepo;
    }

    public function index(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:100',
            'status' => 'nullable|in:approved,pending,rejected',
        ]);

        $coaches = $this->coachRepo->getAll();

        return view('admin.coaches.index', compact('coaches'));
    }

    public function create()
    {
        $categories = $this->categoryRepo->getAll();
        return view('admin.coaches.create', compact('categories'));
    }

    public function store(StoreCoachProfileRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'user_type' => 2, 
                    'email_verified_at' => now(),
                    'status' => 1
                ]);

                $categoryIds = collect($request->categories)->map(function ($value) {
                    if (Str::isUuid($value) && Category::where('id', $value)->exists()) {
                        return $value;
                    }
                    $newCategory = Category::firstOrCreate(
                        ['name' => $value],
                        ['slug' => Str::slug($value), 'is_active' => 1]
                    );
                    return $newCategory->id;
                })->toArray();

                $profileData = $request->validated();
                $profileData['user_id'] = $user->id;
                $profileData['approval_status'] = 'approved'; 
                $profileData['categories'] = $categoryIds;

                $this->coachRepo->create($profileData);
            });

            return redirect()->route('admin.coaches.index')
                ->with('success', 'Coach account created successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error creating coach: ' . $e->getMessage())->withInput();
        }
    }


    public function edit(string $id)
    {
        $coach = $this->coachRepo->findById($id);
        
        if (!$coach) {
            return redirect()->route('admin.coaches.index')->with('error', 'Coach not found.');
        }

        $categories = $this->categoryRepo->getAll();
        return view('admin.coaches.edit', compact('coach', 'categories'));
    }

    public function update(UpdateCoachProfileRequest $request, string $id)
    {
        try {
            $categoryIds = collect($request->categories)->map(function ($value) {
                if (Str::isUuid($value) && Category::where('id', $value)->exists()) {
                    return $value;
                }
                $newCategory = Category::firstOrCreate(
                    ['name' => $value],
                    ['slug' => Str::slug($value), 'is_active' => 1]
                );
                return $newCategory->id;
            })->toArray();

            $data = $request->validated();
            $data['categories'] = $categoryIds;

            $updated = $this->coachRepo->update($id, $data);

            if ($updated) {
                return redirect()->route('admin.coaches.index')->with('success', 'Coach profile updated.');
            }
            
            return back()->with('error', 'Update failed.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error updating coach: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, string $id)
    {
        $request->validate(['status' => 'required|in:pending,approved,rejected']);
        $this->coachRepo->updateStatus($id, $request->status);
        return back()->with('success', 'Coach status updated to ' . ucfirst($request->status));
    }

    public function destroy(string $id)
    {
        $coach = $this->coachRepo->findById($id);

        if (!$coach) {
            return back()->with('error', 'Coach not found.');
        }

        \DB::transaction(function () use ($coach) {

            if ($coach->user) {
                $timestamp = now()->timestamp;

                $coach->user->email = "deleted_{$timestamp}_" . $coach->user->email;
                $coach->user->phone = "deleted_{$timestamp}_" . $coach->user->phone;
                $coach->user->save();

                $coach->user->delete(); // soft delete user
            }

            $coach->delete(); // soft delete coach profile
        });

        return redirect()->route('admin.coaches.index')
            ->with('success', 'Coach deleted successfully.');
    }

    public function exportExcel()
    {
        return Excel::download(new CoachesExport, 'coaches_list.xlsx');
    }

    public function exportPdf()
    {
        $coaches = $this->coachRepo->getAll(); 
        $pdf = Pdf::loadView('admin.coaches.pdf', compact('coaches'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('coaches_list.pdf');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            Excel::import(new CoachesImport, $request->file('file'));
            return response()->json(['success' => 'Coaches imported successfully!']);
            
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMsg = 'Row ' . $failures[0]->row() . ': ' . implode(', ', $failures[0]->errors());
            return response()->json(['error' => $errorMsg], 422);

        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                return response()->json(['error' => 'Duplicate Entry Error: One of the emails or user IDs already exists.'], 422);
            }
            return response()->json(['error' => 'Database Error: ' . $e->getMessage()], 500);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error importing file: ' . $e->getMessage()], 500);
        }
    }

}