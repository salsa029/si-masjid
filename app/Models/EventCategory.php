<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EventCategory extends Model
{
    protected $fillable = ['name', 'slug'];

    protected static function booted(): void
    {
        static::creating(function (EventCategory $category) {
            $category->slug = $category->slug ?: Str::slug($category->name);
        });
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'category_id');
    }
}
