<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class QurbanParticipant extends Model
{
    use SoftDeletes;

    protected $fillable = ['qurban_order_id', 'user_id', 'slot_number', 'share_amount'];

    public function order()
    {
        return $this->belongsTo(QurbanOrder::class, 'qurban_order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
