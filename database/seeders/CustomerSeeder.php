<?php

namespace Database\Seeders;

use Faker\Factory;
use App\Models\Customer;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        $cities = ['Damascus', 'Homs', 'Lattakia', 'Aleppo', 'Tartus', 'As-suwayda'];
        for ($i = 0; $i < 30; $i++) {
            $phoneSuffix = str_pad($i, 2, '0', STR_PAD_LEFT);
            $phone = '09' . '111111' . $phoneSuffix;
            do {
                $random = Str::random(6);
            } while (Customer::where('invitationCode', $random)->exists());
            \App\Models\Customer::create([
                'firstname' => $faker->name,
                'lastname' => $faker->name,
                'gender' => 'male',
                'State' => $cities[array_rand($cities)],
                'email' => $faker->email,
                'password' => bcrypt('Hdr@2132'),
                'phone' => $phone,
                'birthDate' => '1999/10/10',
                'invitationCode' => $random,
            ]);
        }
    }
}
