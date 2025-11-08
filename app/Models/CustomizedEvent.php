<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomizedEvent extends Model
{
    protected $fillable = [
        'user_id', 'event_type', 'date', 'person_count',
        'final_price', 'status', 'capacity'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'customized_event_categories');
    }

    public function services()
    {
        return $this->hasMany(CustomizedEventService::class);
    }
}
