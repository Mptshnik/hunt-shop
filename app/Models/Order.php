<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function items()
    {
        return $this->belongsToMany(Item::class)->withPivot('count');
    }

    protected $casts = [
        'created_at' => 'date:d-m-Y'
    ];
}
