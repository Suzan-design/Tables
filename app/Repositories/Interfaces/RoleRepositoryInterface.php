<?php

namespace App\Repositories\Interfaces;

interface RoleRepositoryInterface
{
    public function getAllRoles();
    public function findRoleById($id);
    public function createRole($data);
    public function updateRole($id, $data);
    public function deleteRole($id);
}
