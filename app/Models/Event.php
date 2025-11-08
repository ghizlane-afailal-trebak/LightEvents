<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Event extends Model
{
    use HasFactory;

    // Ajoute ici tous les champs que tu veux autoriser pour l'assignation de masse
    protected $fillable = [
        'title',
        'description',
        'date',
        'location',
        'category'
    ];

    public function services()
    {
        return $this->belongsToMany(Service::class, 'event_services', 'event_id', 'service_id');
    }
}
