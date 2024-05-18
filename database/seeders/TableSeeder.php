<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Table;
use App\Models\Restaurant;
use Faker\Generator as Faker;

class TableSeeder extends Seeder
{
    public function run(Faker $faker)
    {
        for ($i = 1; $i <= 10; $i++) { // لكل مطعم
            for ($j = 1; $j <= 10; $j++) { // إنشاء 10 طاولات
                Table::create([
                    'number' => $faker->unique()->randomNumber(),
                    'Restaurant_id' => $i,
                    'seating_configuration' => 'empty',
                    'capacity' => $faker->numberBetween(1, 20),
                    'size' => $faker->randomElement(['Single Table', 'Family Table', 'Couple Table', 'Group Table']),
                    'location' => $faker->randomElement(['indoor table', 'outdoor table', 'window table', 'private dining table']),
                    'type' => $faker->randomElement(['Chairs', 'bar stool', 'Couches']),
                ]);
            }
        }
    }
}
