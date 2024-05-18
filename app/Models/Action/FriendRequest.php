<?php

namespace App\Models\Action;

use App\Models\User\MobileUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FriendRequest extends Model
{
    use HasFactory;
    protected $table='friend_requests';

    protected $fillable=[
        'sender_id' ,
        'receiver_id' ,
        'status'
    ] ;


    public function sender()
    {
        return $this->belongsTo(MobileUser::class, 'sender_id');
    }

    /**
     * Get the mobile user that received the friend request.
     */
    public function receiver()
    {
        return $this->belongsTo(MobileUser::class, 'receiver_id');
    }
}
