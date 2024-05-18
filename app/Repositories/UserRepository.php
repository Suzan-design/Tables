<?php

namespace App\Repositories;

use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Hash;
use DB;

class UserRepository implements UserRepositoryInterface
{
    public function getAllUsers()
    {
       
        return User::whereNotIn('roleName', ['staff', 'customer'])
        ->orderBy('id', 'DESC')
        ->get();
    }

    public function createUser($data)
    {
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        $user->assignRole($data['role_name']);
        return $user;
    }

    public function findUserById($id)
    {
        return User::find($id);
    }

    public function updateUser($id, $data)
    {
        $user = $this->findUserById($id);
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $user->update($data);
        DB::table('model_has_roles')->where('model_id', $id)->delete();
        $user->assignRole($data['roles']);
        return $user;
    }

    public function deleteUser($id)
    {
        User::find($id)->delete();
    }
}
