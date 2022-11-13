<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function user()
    {
        return $this->hasOne(User::class);
    }

    protected $fillable =
    [
        'last_name',
        'first_name',
        'middle_name',
        'passport_series',
        'passport_number',
        'birthday_date'
    ];


    protected $casts = [
        'birthday_date' => 'datetime:d-m-Y'
    ];
}
