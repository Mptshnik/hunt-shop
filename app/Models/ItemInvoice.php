<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemInvoice extends Model
{
    use HasFactory;

    public function seller()
    {
        return $this->hasOne(Seller::class);
    }

    public function provider()
    {
        return $this->hasOne(Provider::class);
    }

    protected $casts = [
        'created_at' => 'date:d-m-Y'
    ];
}
