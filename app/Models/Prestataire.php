<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prestataire extends Model
{
    public function categories() {
        return $this->hasMany(Category::class);
    }
}
