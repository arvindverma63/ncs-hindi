<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use App\Actions\Role\CreateRoleAction;
use App\Actions\Role\UpdateRoleAction;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::latest()->get(); 
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all()->groupBy('group_name');
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request, CreateRoleAction $action)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        try {
            $action->handle($request->all());
            return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating role: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all()->groupBy('group_name');
        $assignedPermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'assignedPermissions'));
    }

    public function update(Request $request, $id, UpdateRoleAction $action)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        try {
            $action->handle($role, $request->all());
            return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating role: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
    }
}