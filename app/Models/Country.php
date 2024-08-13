<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    public function holidays()  // každá země má svoje svátky
    {
        return $this->hasMany(Holiday::class);
    }
}
