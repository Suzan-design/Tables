<?php

namespace App\Listeners;

use App\Events\NotificationEvent;
use Illuminate\Support\Facades\Log;

class NotificationListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }
    /**
     * Handle the event.
     *
     * @param NotificationEvent $event
     */
    public function handle(NotificationEvent $event): void
    {
        $title = $event->title; // Access the Booking model from the event
        Log::info('New booking received:', [
            'booking' => $title,
        ]);
    }
}

