<?php

namespace App\Events;


use Carbon\Carbon;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $title;
    public $date;
    public $description;
    public $id;
    /**
     * Create a new event instance.
     */
    public function __construct($title, $description ,$id)
    {
        $this->title = $title;
        $this->date = Carbon::now();
        $this->description= $description ;
        $this->id = $id ;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return  ['notification'.$this->id] ;
    }

    public function broadcastAs()
    {
        return 'notification' ;
    }
}
