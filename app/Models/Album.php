<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Album extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($album) {
            if (empty($album->slug)) {
                $album->slug = Str::slug($album->name);
            }
        });
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }
}
