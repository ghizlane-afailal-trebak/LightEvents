<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomizedEventCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    // Relation avec CustomizedEvent (Relation plusieurs à plusieurs)
    public function events()
    {
        return $this->belongsToMany(CustomizedEvent::class, 'customized_event_categories');
    }
}
