<?php

namespace App\Services\api;

use App\Models\User\MobileUser;
use App\Traits\FileStorageTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UserService
{

    use FileStorageTrait ;

    public function getUserById($id)
    {
        return MobileUser::find($id);
    }

    public function updateUser($data)
    {
        $user = Auth::guard('mobile')->user();

        // Define the fields you want to update
        $fieldsToUpdate = ['first_name', 'last_name', 'phone_number', 'state', 'gender', 'birth_date' , 'image' , 'type'];

        // Check for image in the request and store it
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $imagePath = $this->storefile($data['image'],'User_images' ); // Storing the image
            $data['image'] = $imagePath;
        }

        // Use Arr::only to get only the fields that are provided in $data
        $updatedData = Arr::only($data, $fieldsToUpdate);

        // Filter out null values
        $updatedData = array_filter($updatedData, function ($value) {
            return !is_null($value);
        });
        $user->update($updatedData);

        return $user;
    }

    public function deleteUser()
    {
        $user = Auth::guard('mobile')->user();
        $user->delete();
    }

    public function resetUserPassword($oldPassword, $newPassword)
    {
        $user = Auth::guard('mobile')->user();

        if (!Hash::check($oldPassword, $user->password)) {
            return ['error' => 'password is incorrect.'];
        }

        $user->updatePassword($newPassword);
        return ['message' => 'Password has been updated.'];
    }

}
