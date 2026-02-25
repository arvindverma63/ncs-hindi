<?php

namespace App\Actions\Role;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class UpdateRoleAction
{
    public function handle(Role $role, array $data): Role
    {
        return DB::transaction(function () use ($role, $data) {
            $role->update(['name' => $data['name']]);

            if (isset($data['permissions'])) {
                $permissions = Permission::whereIn('id', $data['permissions'])->get();
                $role->syncPermissions($permissions);
            } else {
                $role->syncPermissions([]);
            }

            return $role;
        });
    }
}