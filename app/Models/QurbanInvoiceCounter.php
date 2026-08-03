<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QurbanInvoiceCounter extends Model
{
    protected $fillable = ['type', 'year', 'last_number'];
}
