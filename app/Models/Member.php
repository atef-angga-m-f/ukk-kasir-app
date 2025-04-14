<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'phone',
        'name',
        'point',
    ];

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
