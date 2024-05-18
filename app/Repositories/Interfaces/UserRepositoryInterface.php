<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface
{
    public function getAllUsers();
    public function createUser($data);
    public function findUserById($id);
    public function updateUser($id, $data);
    public function deleteUser($id);
}
