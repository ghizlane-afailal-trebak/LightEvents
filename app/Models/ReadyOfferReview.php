<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReadyOfferReview extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'ready_offer_id', 'rating', 'comment'];

    // Relation avec l'utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec l'offre prête
    public function readyOffer()
    {
        return $this->belongsTo(ReadyOffer::class);
    }
}
