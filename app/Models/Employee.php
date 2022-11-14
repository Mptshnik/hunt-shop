<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    protected $hidden = [
        'post_id'
    ];

    protected $fillable =
    [
        'last_name',
        'first_name',
        'middle_name',
        'passport_series',
        'passport_number',
        'birthday_date',
        'post_id'
    ];


    protected $casts = [
        'birthday_date' => 'datetime:d-m-Y'
    ];
}
