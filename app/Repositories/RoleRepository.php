<?php

namespace App\Repositories;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use DB;

class RoleRepository implements RoleRepositoryInterface
{
    public function getAllRoles()
    {
        return Role::with('permissions')->orderBy('id', 'DESC')->paginate(5);
    }

    public function findRoleById($id)
    {
        return Role::findById($id);
    }

    public function createRole($data)
    {
        $role = Role::create(['name' => $data['name']]);
        $role->syncPermissions($data['permissions']);
        return $role;
    }

    public function updateRole($id, $data)
    {
        $role = $this->findRoleById($id);
        $role->name = $data['name'];
        $role->save();
        $role->syncPermissions($data['permissions']);
    }

    public function deleteRole($id)
    {
        DB::table("roles")->where('id', $id)->delete();
    }
}
