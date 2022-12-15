<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static int $SELLER_ID = 1;

    public $timestamps = false;

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function itemInvoice()
    {
        return $this->hasOne(ItemInvoice::class);
    }
}
