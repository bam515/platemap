<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Visit extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'place_id',
        'visited_at',
        'visibility',
        'memo',
        'published_at',
        'is_hidden'
    ];

    protected $casts = [
        'visited_at' => 'datetime',
        'published_at' => 'datetime',
        'is_hidden' => 'boolean',
        'deleted_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    public function dishLogs()
    {
        return $this->hasMany(DishLog::class);
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
