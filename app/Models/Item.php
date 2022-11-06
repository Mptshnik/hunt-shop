<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    public function orders()
    {
        return $this->belongsTo(Order::class);
    }

    public function category()
    {
        return $this->hasOne(ItemCategory::class);
    }

    public function itemInvoice()
    {
        return $this->hasOne(ItemInvoice::class);
    }

    public function promotion()
    {
        return $this->hasOne(Promotion::class);
    }
}
