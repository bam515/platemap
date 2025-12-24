<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlaceDish extends Model
{
    protected $fillable = [
        'place_id',
        'name',
        'name_norm'
    ];

    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    public function dishLogs()
    {
        return $this->hasMany(DishLog::class);
    }
}
