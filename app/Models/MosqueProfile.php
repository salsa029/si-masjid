<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\LogsAdminActivity;

class MosqueProfile extends Model
{
    use HasFactory, LogsAdminActivity;
    protected $fillable = [
        'name',
        'history',
        'vision',
        'mission',
        'address',
        'contact',
        'hero_image',
        'bank_account_number',
        'latitude',
        'longitude',
        'facebook_url',
        'instagram_url',
        'youtube_url',
        'whatsapp_url',
    ];

    public function galleryImages()
    {
        return $this->hasMany(MosqueGalleryImage::class)->orderBy('sort_order');
    }
}
