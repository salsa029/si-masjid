<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventDocumentation extends Model
{

    protected $fillable = ['event_id', 'photo', 'caption'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
