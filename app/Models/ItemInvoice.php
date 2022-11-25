<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemInvoice extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $hidden = ['seller_id', 'provider_id', 'updated_at'];

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    protected $casts = [
        'created_at' => 'date:d-m-Y',
        'date' => 'datetime:d-m-Y'
    ];

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');
    }
}
