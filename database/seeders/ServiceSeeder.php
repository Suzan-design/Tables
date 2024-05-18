<?php

namespace Database\Seeders;

use App\Models\icon;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $services = ['Wifi', 'Parking', 'Laundry', 'Pool', 'Gym'];
        foreach ($services as $service) {
            $directoryPath = 'seeder/services/';
            $randomString = Str::random(4);
            $fileName = $randomString . '.jpg';
            $filePath = Storage::disk('upload_images')->put($directoryPath . $fileName, file_get_contents(public_path('seeder/axOZ.jpg')));
            icon::create([
                'name' => $service,
                'image' => $directoryPath . $fileName,
            ]);
        }
    }
}
