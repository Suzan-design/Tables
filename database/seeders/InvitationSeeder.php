<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Faker\Factory;
use App\Models\Invitation;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class InvitationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = ['invitations', 'reviews', 'reservations'];
        $faker = Factory::create();
        for ($i = 0; $i < 5; $i++) {
            Invitation::create([
                'target' => rand(1,5),
                'type'=>$faker->randomElement($types),
                'expire' => '30',
                'discount' => rand(10, 50),
                'title' => $faker->title(),
                'description' => $faker->title(),
                'coupons' => 'Get'. rand(10, 50).'disscont',
                'limit'=>'1000',
            ]);
        }
    }
}
