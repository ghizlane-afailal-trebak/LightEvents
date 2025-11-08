<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'pricing_type', 'price',
        'price_per_person', 'price_per_table', 'is_active',
        'image', 'prestataire_id', 'category_id',

    ];

    public function user()
{
    return $this->belongsTo(User::class, 'prestataire_id');
}

    public function readyOffers()
    {
        return $this->belongsToMany(ReadyOffer::class, 'ready_offer_services', 'service_id', 'ready_offer_id');
    }

    public function prestataire()
    {
        return $this->belongsTo(User::class, 'prestataire_id'); // Correction ici
    }

    public function reservations()
    {
        return $this->hasMany(BookingService::class, 'service_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'service_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
