<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Allow mass assignment for these fields
    protected $fillable = ['name', 'prestataire_id'];

    public function prestataire() {
        return $this->belongsTo(Prestataire::class);
    }

    public function services() {
        return $this->hasMany(Service::class);
    }
}
