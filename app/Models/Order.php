<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $hidden = [
        'updated_at',
    ];

    public function items()
    {
        return $this->belongsToMany(Item::class)->withPivot('count');
    }

    public function totalSum() : Attribute
    {
        $sum = 0;
        foreach ($this->items as $item)
        {
            $sum+=$item->getPriceForCount();
        }

        return new Attribute(
            get: fn() => $sum
        );
    }

    protected $appends = ['total_sum'];

    protected $casts = [
        'created_at' => 'datetime:d-m-Y H-m-s',
        'order_time' => 'datetime:d-m-Y H-m-s'
    ];
}
