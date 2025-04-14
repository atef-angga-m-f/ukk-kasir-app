<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'invoice',
        'member_id',
        'kasir_id',
        'products',
        'payment_amount',
        'total_amount',
        'return_amount',
        'discount_amount',
    ];

    protected $casts = [
        'products' => 'array',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function kasir()
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }
}
