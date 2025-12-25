<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }

    public function dishLogs(): HasMany
    {
        return $this->hasMany(DishLog::class);
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }
}
