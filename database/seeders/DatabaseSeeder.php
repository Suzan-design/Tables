<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
       $this->call(PermissionSeeder::class);
       $this->call(AdminSeeder::class);
      // $this->call(CustomerSeeder::class);
       //$this->call(BasicSeeder::class);
        //$this->call(RestaurantSeeder::class);
        //$this->call(TableSeeder::class);
       //$this->call(OfferSeeder::class);
       //$this->call(PromoCodeSeeder::class);
      //$this->call(InvitationSeeder::class);
       // $this->call(ServiceSeeder::class);
    }
}


