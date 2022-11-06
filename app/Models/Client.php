<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Client extends Model
{
    use HasFactory;

    public function organisation()
    {
        return $this->hasOne(Organisation::class);
    }

    public function person()
    {
        return $this->hasOne(Person::class);
    }
}
