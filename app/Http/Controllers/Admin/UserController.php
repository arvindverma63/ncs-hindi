<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Actions\User\CreateUserAction;
use App\Actions\User\UpdateUserAction;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // 1. Validate inputs
        $request->validate([
            'search' => 'nullable|string|max:100',
            'role'   => 'nullable|exists:roles,name',
            'status' => 'nullable|in:0,1',
        ]);

        // 2. Build Query
        $query = User::where('user_type', 1) // Only Admin/Staff
            ->with(['media', 'roles']);

        // Filter by Search (Name or Email)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // Filter by Role (using Spatie's role relationship)
        if ($request->filled('role')) {
            $query->role($request->role);
        }

        // Filter by Account Status
        if ($request->has('status') && $request->status !== null) {
            $query->where('status', $request->status);
        }

        // 3. Execute with Pagination
        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();
        
        return view('admin.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request, CreateUserAction $action)
    {
        try {
            $action->handle(
                $request->validated(), 
                $request->file('profile_image'),
            );
            return redirect()->route('admin.users.index')->with('success', 'User registered successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    public function show(User $user)
    {
        $user->load(['media']);
        
        $profileImg = $user->profile_image; 

        return response()->json([
            'user' => $user,
            'profile_image' => $profileImg,
            'created_at_formatted' => $user->created_at->format('d M Y, h:i A')
        ]);
    }

    public function edit(User $user)
    {
        $roles = Role::pluck('name');
        $userRole = $user->getRoleNames()->first();

        return view('admin.users.edit', compact('user', 'roles', 'userRole'));
    }

    public function update(UpdateUserRequest $request, User $user, UpdateUserAction $action)
    {
        try {
            $action->handle(
                $user, 
                $request->validated(), 
                $request->file('profile_image')
            );
            return redirect()->route('admin.users.index')->with('success', 'User updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(User $user)
    {
        $timestamp = time();

        $user->update([
            'name'  => $user->name . '@deleted_' . $timestamp,
            'email' => $user->email . '@deleted_' . $timestamp,
            'phone' => $user->phone ? $user->phone . '@deleted_' . $timestamp : null,
        ]);

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully!');
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'status' => 'required|boolean',
        ]);

        $user = User::findOrFail($request->id);
        $user->status = $request->status;
        $user->save();

        return response()->json(['message' => 'User status updated successfully.']);
    }
}