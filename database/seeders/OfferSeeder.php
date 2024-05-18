<?php

namespace Database\Seeders;

use Faker\Factory;
use App\Models\offer;
use Illuminate\Support\Str;
use App\Models\images_offer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        for ($i = 0; $i < 10; $i++) {
            $type = $i < 5 ? 'offer' : 'new_opening';
            $offer = offer::create([
                'Restaurant_id' => $faker->numberBetween(1, 10), // تأكد من وجود هذه الـ IDs في جدول restaurants
                'price_old' => $faker->randomNumber(2),
                'price_new' => $faker->randomNumber(2),
                'description' => $faker->sentence,
                'name' => $faker->word,
                'type' => $type,
                'start_date' => $faker->date,
                'status' => 'active',
            ]);
            $directoryPath = 'seeder/Offers/covers/';
            $randomString = Str::random(4);
            $fileName = $randomString . '.jpg';
            $filePath = Storage::disk('upload_images')->put($directoryPath . $fileName, file_get_contents(public_path('seeder/axOZ.jpg')));
            images_offer::create([
                'filename' =>$directoryPath.$fileName,
                'type' => 'cover',
                'imageable_id' => $offer->id,
                'imageable_type' => Offer::class
            ]);
            for ($j = 0; $j < 2; $j++) {
                $directoryPath = 'seeder/Offers/gallery/';
                $randomString = Str::random(4);
                $fileName = $randomString . '.jpg';
                $filePath = Storage::disk('upload_images')->put($directoryPath . $fileName, file_get_contents(public_path('seeder/axOZ.jpg')));
                images_offer::create([
                    'filename' =>$directoryPath.$fileName,
                    'type' => 'gallery',
                    'imageable_id' => $offer->id,
                    'imageable_type' => Offer::class
                ]);
            }
        }
    }
}
