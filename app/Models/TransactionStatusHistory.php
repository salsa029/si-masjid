<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionStatusHistory extends Model
{
    public $timestamps = false;

    protected $fillable = ['from_status', 'to_status', 'note', 'changed_by'];

    protected function casts(): array
    {
        return ['created_at' => 'datetime'];
    }

    public function transactionable()
    {
        return $this->morphTo();
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (TransactionStatusHistory $history) {
            $history->created_at = now();
        });
    }
}
