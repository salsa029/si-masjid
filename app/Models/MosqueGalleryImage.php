<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MosqueGalleryImage extends Model
{
    protected $fillable = ['mosque_profile_id', 'photo', 'caption', 'sort_order'];

    public function mosqueProfile()
    {
        return $this->belongsTo(MosqueProfile::class);
    }
}
