<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomizedEventService extends Model
{
    protected $fillable = [
        'customized_event_id', 'service_id',
        'calculated_price', 'calculation_note'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function event()
    {
        return $this->belongsTo(CustomizedEvent::class, 'customized_event_id');
    }
}
