<?php

namespace Database\Seeders;

use Faker\Factory;
use App\Models\Type;
use App\Models\Cuisine;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Models\restaurnats_categories;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class BasicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        $cuisine_types = ['America', 'Italy', 'Aerican', 'Arabic'];
        for ($i = 0; $i < 4; $i++) {
            Cuisine::create([
                'name' => $cuisine_types[array_rand($cuisine_types)],
                'description' => 'details test',
                'ar_name' => $cuisine_types[array_rand($cuisine_types)],
                'ar_description' => 'details test'
            ]);
        }
        $categories = ['Bar', 'Fast Food', 'Cafe', 'Seafood', 'Pizza', 'Barbecue', 'Sushi'];
        foreach ($categories as $category) {
            $directoryPath = 'seeder/Categories/icons/';
            $randomString = Str::random(4);
            $fileName = $randomString . '.jpg';
            $filePath = Storage::disk('upload_images')->put($directoryPath . $fileName, file_get_contents(public_path('seeder/axOZ.jpg')));
            restaurnats_categories::create([
                'name' => $category,
                'icon' => $directoryPath . $fileName,
                'description' => $faker->text,
                'ar_name' => $category,
                'ar_description' => $faker->text,
            ]);
        }
        $menu_types = ['Drink', 'Grill', 'Pizza', 'Dessert', 'Sea Food'];
        foreach ($menu_types as $menu_type) {
            $directoryPath = 'seeder/Menus/types/';
            $randomString = Str::random(4);
            $fileName = $randomString . '.jpg';
            $filePath = Storage::disk('upload_images')->put($directoryPath . $fileName, file_get_contents(public_path('seeder/axOZ.jpg')));
            Type::create([
                'name' => $menu_type,
                'ar_name' => $menu_type,
                'symbol' => $directoryPath . $fileName,
            ]);
        }
    }
}
