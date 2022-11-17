<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseApplication extends Model
{
    use HasFactory;

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    protected $hidden = [
        'updated_at',
        'employee_id'
    ];

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'created_at' => 'date:d-m-Y'
    ];
}
