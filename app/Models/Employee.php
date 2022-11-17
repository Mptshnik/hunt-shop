<?php

namespace App\Models;

use Carbon\Carbon;
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

    public function clientApplicationForm()
    {
        return $this->hasOne(ClientApplicationForm::class);
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

    public function setBirthdayDateAttribute($value)
    {
        $this->attributes['birthday_date'] = Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');
    }
}
