<?php

namespace App\Actions\Role;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class CreateRoleAction
{
    public function handle(array $data): Role
    {
        return DB::transaction(function () use ($data) {
            $role = Role::create([
                'name' => $data['name'],
                'guard_name' => 'web',
            ]);

            if (!empty($data['permissions'])) {
                $permissions = Permission::whereIn('id', $data['permissions'])->get();
                $role->syncPermissions($permissions);
            }

            return $role;
        });
    }
}