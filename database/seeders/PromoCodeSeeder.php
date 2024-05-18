<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Faker\Factory;
use App\Models\Customer;
use App\Models\Promocode;
use App\Models\Restaurant;
use Illuminate\Support\Str;
use App\Models\res_prompcodes;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PromoCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        $restaurants = Restaurant::all();
        $customers = Customer::all();
        $userIds = $customers->pluck('id');
        for ($i = 0; $i < 5; $i++) {
            $code = Str::random(10); // توليد رمز عشوائي
            $startDate = Carbon::tomorrow();
            $endDate = Carbon::tomorrow()->addDays(rand(1, 30)); // تاريخ نهاية عشوائي
            $directoryPath = 'seeder/Promocodes/';
            $randomString = Str::random(4);
            $fileName = $randomString . '.jpg';
          $filePath = Storage::disk('upload_images')->put($directoryPath . $fileName, file_get_contents(public_path('seeder/axOZ.jpg')));
            Promocode::create([
                'code' => $code,
                'discount' => rand(10, 50), // خصم عشوائي بين 10% و 50%
                'limit' => rand(1, 10),
                'start_date' => $startDate,
                'end_date' => $endDate,
                'is_reservaions' => '0',
                'image'=>$directoryPath.$fileName,
                'is_followings' =>'0',
                'num_us' => '100',
                'num_res' => '100',
                'description'=>'Promo Code Promo Code Promo CodePromo CodePromo CodePromo CodePromo CodePromo Code',
                'users_ids' => $userIds,
            ]);
        }
        $promocodes = Promocode::all();
        foreach ($promocodes as $promocode) {
            foreach ($restaurants as $restaurant) {
                DB::table('restaurantpromoCodes')->insert([
                    'restaurant_id' => $restaurant->id,
                    'promocode_id' => $promocode->id,
                ]);
            }
            foreach ($customers as $customer) {
                $customerPromocodes = $customer->promocodes ?? [];
                $customerPromocodes[] = $promocode->id;
                $customer->promocodes = $customerPromocodes;
                $customer->save();
            }
        }
    }
}
