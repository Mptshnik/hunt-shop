<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    public function seller()
    {
        return $this->hasOne(Seller::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }

    protected $casts = [
        'created_at' => 'date:d-m-Y'
    ];
}
