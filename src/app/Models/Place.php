<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Place extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'lat',
        'lng',
        'address',
        'road_address',
        'source',
        'source_place_id',
        'category',
        'phone'
    ];

    protected $casts = [
        'lat' => 'decimal:7',
        'lng' => 'decimal:7',
        'deleted_at' => 'datetime'
    ];

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public function placeDishes()
    {
        return $this->hasMany(PlaceDish::class);
    }

    public function dishLogs()
    {
        return $this->hasMany(DishLog::class);
    }
}
