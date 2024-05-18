<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Restaurant;
use Carbon\Carbon;

class CheckSubscriptions extends Command
{
    protected $signature = 'subscriptions:check';
    protected $description = 'Check if any restaurant subscriptions have ended and update their status accordingly';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $today = Carbon::today();
        $restaurants = Restaurant::where('Activation_end', '<', $today)->get();

        foreach ($restaurants as $restaurant) {
            // Assuming there's a 'status' field to update
            $restaurant->status = 'inactive';
            $restaurant->save();
            // Optionally send notification or email to the restaurant about their subscription status
        }

        $this->info('Subscriptions checked and updated successfully.');
    }
}
