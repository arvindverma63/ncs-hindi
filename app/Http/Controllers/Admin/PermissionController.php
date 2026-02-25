<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::orderBy('group_name')->get()->groupBy('group_name');
        return view('admin.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('admin.permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
            'group_name' => 'nullable|string|max:255',
        ]);

        Permission::create([
            'name' => $request->name,
            'group_name' => $request->group_name ?? 'Default',
            'guard_name' => 'web',
        ]);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission created successfully.');
    }

    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:permissions,name,' . $id,
            'group_name' => 'nullable|string|max:255',
        ]);

        $permission->update([
            'name' => $request->name,
            'group_name' => $request->group_name,
        ]);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission updated successfully.');
    }

    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();
        return redirect()->route('admin.permissions.index')->with('success', 'Permission deleted successfully.');
    }
}