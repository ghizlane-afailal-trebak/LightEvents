<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReadyOffer extends Model
{
    use HasFactory;
    protected $fillable = ['ready_offer_id','title', 'description', 'image','original_price','discounted_price',  'categorie', 'admin_id'];


    // Définir la relation many-to-many avec le modèle Servic
      public function services()
    {

        return $this->belongsToMany(Service::class, 'ready_offer_services', 'ready_offer_id', 'service_id');
    }

    public function bookings()
    {
        return $this->hasMany(ReadyOfferBooking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviews()
    {
        return $this->hasMany(ReadyOfferReview::class, 'ready_offer_id');
    }
}
