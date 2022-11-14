<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Psy\Command\ClearCommand;

class JuridicalStatus extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function client()
    {
        return $this->hasOne(Client::class);
    }

    public const IS_ORGANISATION= 1;
    public const IS_PERSON = 2;
}
