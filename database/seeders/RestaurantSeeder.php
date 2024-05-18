<?php

namespace Database\Seeders;

use Faker\Factory;
use App\Models\Menu;
use App\Models\Image;
use App\Models\Reviews;
use App\Models\Customer;
use App\Models\Restaurant;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();

        //Staff
        $role = Role::where('name', 'staff')->first();
        for ($i = 0; $i < 10; $i++) {
            $userData = [
                'name' => $faker->name,
                'password' => bcrypt('Hdr@2132'),
                'roleName' => 'staff',
                'phone' => '09' . random_int(11111111, 99999999),
                'email'=>'HDR_staff12'.$i.'@gmail.com',
                'status' => 'active',
            ];

            $user = \App\Models\User::create($userData);
            $user->assignRole([$role->id]);
        }
        //Restaurant
        $start = ['Damascus', 'Homs', 'Lattakia', 'Aleppo', 'Tartus', 'As-suwayda'];
        for ($i = 2; $i < 12; $i++) {
            $restaurant = Restaurant::create([
                'user_id' => $i,
                'cuisine_id' => random_int(1, 4),
                'category_id' => random_int(1, 7),
                'description' => $faker->paragraph,
                'ar_description' => $faker->paragraph,
                'name' => $faker->name,
                'Activation_start' => '2023-09-10',
                'Activation_end' => '2023-09-27',
                'taxes' => 10,
                'phone_number' => '9639' . random_int(10000000, 99999999),
                'age_range' => serialize(['start_age' => 18, 'end_age' => 30]),
                'services' => json_encode(['wifi' => 'attachments\\Restaurants\\images\\wifi.png', 'call' => 'attachments\\Restaurants\\images\\wifi.png']),
                'ar_services' => json_encode(['wifi' => 'attachments\\Restaurants\\images\\wifi.png', 'call' => 'attachments\\Restaurants\\images\\wifi.png']),
                'Deposite_value' => 50,
                'Deposite_desc'=> 'deposite per persone is required',
                'refund_policy' => '(24,refund until 1 day)',
                'change_policy' => '(3,booking change until 3 hours)',
                'cancellition_policy' => '(4,cancellation allowed until 4 hours)',
                'ar_Deposite_desc'=> 'deposite per persone is required',
                'ar_refund_policy' => '(24,refund until 1 day)',
                'ar_change_policy' => '(3,booking change until 3 hours)',
                'ar_cancellition_policy' => '(4,cancellation allowed until 4 hours)',
                'time_start' => '11:00',
                'time_end' => '23:00',
            ]);
            $directoryPath = 'seeder/Restaurants/';
            $randomString = Str::random(4);
            $fileName = $randomString . '.jpg';
            $filePath = Storage::disk('upload_images')->put($directoryPath . $fileName, file_get_contents(public_path('seeder/axOZ.jpg')));
            Image::create([
                'filename' =>$directoryPath.$fileName,
                'imageable_id' => $restaurant->id,
                'type' => 'logo',
                'imageable_type' => 'App\Models\Restaurant',
            ]);
            $randomString = Str::random(4);
            $fileName = $randomString . '.jpg';
            $filePath = Storage::disk('upload_images')->put($directoryPath . $fileName, file_get_contents(public_path('seeder/axOZ.jpg')));
            Image::create([
                'filename' =>$directoryPath.$fileName,
                'imageable_id' => $restaurant->id,
                'type' => 'cover',
                'imageable_type' => 'App\Models\Restaurant',
            ]);
            for ($j = 0; $j < 3; $j++) {
                $randomString = Str::random(4);
                $fileName = $randomString . '.jpg';
                $filePath = Storage::disk('upload_images')->put($directoryPath . $fileName, file_get_contents(public_path('seeder/axOZ.jpg')));
                Image::create([
                    'filename' =>$directoryPath.$fileName,
                    'imageable_id' => $restaurant->id,
                    'type' => 'craousal',
                    'imageable_type' => 'App\Models\Restaurant',
                ]);
            }
            for ($k = 0; $k < 2; $k++) {
                $randomString = Str::random(4);
                $fileName = $randomString . '.jpg';
                $filePath = Storage::disk('upload_images')->put($directoryPath . $fileName, file_get_contents(public_path('seeder/axOZ.jpg')));
                Image::create([
                    'filename' => $directoryPath.$fileName,
                    'imageable_id' => $restaurant->id,
                    'type' => 'gallery',
                    'imageable_type' => 'App\Models\Restaurant',
                ]);
            }
            $foodNames = ['Pizza', 'Burger', 'Sushi', 'Falafel', 'Shawarma', 'Pasta', 'Taco', 'Salad'];
            for ($k = 0; $k < 8; $k++) {
                Menu::create([
                    'restaurant_id' => $restaurant->id,
                    'type_id' => random_int(1, 5),
                    'name' => $faker->randomElement($foodNames),
                    'ar_name' => $faker->randomElement($foodNames),
                    'price' => random_int(10000, 50000),
                    'icon' => $faker->imageUrl(100, 100, 'food'),
                ]);
            }
            $customerIds = Customer::pluck('id')->toArray();
            for ($k = 0; $k < 3; $k++) {
                Reviews::create([
                    'customer_id' => $faker->randomElement($customerIds),
                    'Restaurant_id' => $restaurant->id,
                    'rating' => $faker->numberBetween(1, 5),
                    'comment' => $faker->sentence,
                ]);
            }
            $randomStartIndex = array_rand($start);
            $randomStart = $start[$randomStartIndex];
            \App\Models\Location::create([
                'Restaurant_id' => $restaurant->id,
                'latitude' => $faker->latitude($min = 22, $max = 31),
                'longitude' => $faker->longitude($min = 25, $max = 40),
                'state' => $randomStart,
                'text' => 'details location',
                'ar_text' => 'details location',
            ]);
        }
    }
}
