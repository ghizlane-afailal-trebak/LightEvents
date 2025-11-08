<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReadyOfferBooking extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'ready_offer_id', 'status'];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with ReadyOffer
    public function readyOffer()
    {
        return $this->belongsTo(ReadyOffer::class);
    }
}
