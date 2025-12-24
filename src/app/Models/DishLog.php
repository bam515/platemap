<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DishLog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'visit_id',
        'place_id',
        'place_dish_id',
        'dish_name',
        'memo',
        'would_reorder',
        'taste_salty',
        'taste_bland',
        'taste_sweet',
        'taste_spicy',
        'taste_umami',
        'taste_texture',
        'photo_url',
        'sort_order',
        'is_hidden'
    ];

    protected $casts = [
        'would_reorder' => 'boolean',
        'is_hidden' => 'boolean',
        'sort_order' => 'integer',
        'deleted_at' => 'datetime'
    ];

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    public function placeDish()
    {
        return $this->belongsTo(PlaceDish::class);
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }
}
