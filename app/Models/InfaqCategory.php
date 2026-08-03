<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\LogsAdminActivity;

class InfaqCategory extends Model
{
    use LogsAdminActivity;
    protected $fillable = ['name', 'slug'];

    protected static function booted(): void
    {
        static::creating(function (InfaqCategory $category) {
            $category->slug = $category->slug ?: Str::slug($category->name);
        });
    }

    public function campaigns()
    {
        return $this->hasMany(InfaqCampaign::class, 'category_id');
    }

    public function infaqs()
    {
        return $this->hasMany(Infaq::class, 'category_id');
    }
}
