<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Album;

class Gallery extends Model
{
    protected $fillable = [
        'title',
        'album_id',
        'image_path',
        'description',
        'is_active',
        'sort_order'
    ];

    public function album()
    {
        return $this->belongsTo(Album::class);
    }
}
