<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $hidden = [
        'updated_at',
        'item_category_id',
        'item_invoice_id',
        'promotion_id'
    ];

    public $timestamps = false;

    public function order()
    {
        return $this->belongsToMany(Order::class);
    }

    public function itemCategory()
    {
        return $this->belongsTo(ItemCategory::class);
    }

    public function itemInvoice()
    {
        return $this->belongsTo(ItemInvoice::class);
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }
}
