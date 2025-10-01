<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends Model
{
    use HasFactory;

    protected $fillable = ['country_id', 'name', 'slug'];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
