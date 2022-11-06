<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function items()
    {
        return $this->belongsTo(Item::class);
    }

    protected $casts = [
        'created_at' => 'date:d-m-Y'
    ];
}
