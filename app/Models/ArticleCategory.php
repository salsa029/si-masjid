<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ArticleCategory extends Model
{
    protected $fillable = ['name', 'slug'];

    protected static function booted(): void
    {
        static::creating(function (ArticleCategory $category) {
            $category->slug = $category->slug ?: Str::slug($category->name);
        });
    }

    public function articles()
    {
        return $this->hasMany(Article::class, 'category_id');
    }
}
