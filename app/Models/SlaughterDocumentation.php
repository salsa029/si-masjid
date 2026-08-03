<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SlaughterDocumentation extends Model
{
    use HasFactory;

    protected $fillable = ['sacrificial_animal_id', 'photo', 'description'];

    public function animal(): BelongsTo
    {
        return $this->belongsTo(SacrificialAnimal::class, 'sacrificial_animal_id');
    }
}
