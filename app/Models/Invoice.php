<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $hidden = [
        'seller_id',
        'order_id',
        'updated_at',
    ];

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    protected $casts = [
        'created_at' => 'datetime:d-m-Y H:m:s',
        'date' => 'datetime:d-m-Y H:m:s'
    ];
}
