<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $casts = [
        'start_date' => 'dateTime:d-m-Y',
        'end_date' => 'dateTime:d-m-Y'
    ];
}
