<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollectionItem extends Model
{
    protected $fillable = [
        'collection_id',
        'place_id',
        'sort_order',
        'note'
    ];

    protected $casts = [
        'sort_order' => 'integer'
    ];

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }

    public function place()
    {
        return $this->belongsTo(Place::class);
    }
}
