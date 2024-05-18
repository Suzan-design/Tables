<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Faker\Factory;
class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        $user = \App\Models\User::create([
            'name' => 'HDR ADMIN',
            'email' => 'HDR@gmail.com',
            'password' => bcrypt('Hdr@2132'),
            'roleName' => 'admin',
            'phone' => '09' . random_int(11111111, 99999999),
            'status' => 'active'
        ]);


        $role = Role::where('name', 'admin')->first();
        $user->assignRole([$role->id]);

    }
}
