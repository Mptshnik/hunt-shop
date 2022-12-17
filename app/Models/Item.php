<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'promotion_id',
        'pivot'
    ];

    public $timestamps = false;

    public function getPriceForCount()
    {
        return $this->price * $this->pivot->count;
    }

    public function itemsCount():Attribute
    {
        if(!is_null($this->pivot))
        {
            return Attribute::make(fn()=>$this->pivot->count);
        }

        return Attribute::make(fn()=>$this->count);
    }

    public function priceForCount(): Attribute
    {
        if(!is_null($this->pivot))
        {
            return Attribute::make(fn () => $this->price * $this->pivot->count);
        }

        return Attribute::make(fn () => $this->price * $this->count);
    }

    protected $appends = [
        'price_for_count',
        'items_count'
    ];

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
