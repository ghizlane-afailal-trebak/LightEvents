<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingService extends Model
{
    protected $fillable = ['user_id',
     'user_name',
    'email',
    'phone','service_id', 'reservation_date', 'status'];

     // Define the relationship to User
     public function user()
     {
        return $this->belongsTo(User::class, 'user_id');
     }

     // Define the relationship to Service (if it exists)
     public function service()
     {
        return $this->belongsTo(Service::class, 'service_id');
     }
}
