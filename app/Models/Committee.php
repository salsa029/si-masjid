<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\LogsAdminActivity;

class Committee extends Model
{
    use SoftDeletes, LogsAdminActivity;
    use HasFactory;

    protected $fillable = ['name', 'position', 'photo', 'bio', 'term_start', 'term_end'];

    protected function casts(): array
    {
        return [
            'term_start' => 'datetime',
            'term_end' => 'datetime',
        ];
    }
}
