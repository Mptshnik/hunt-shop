<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public $timestamps = false;
    public const IS_ADMIN = 1;
    public const IS_HR = 2;
    public const IS_MARKETING_MANAGER = 3;
    public const IS_SALES_MANAGER = 4;
    public const IS_PURCHASING_MANAGER = 5;
    public const IS_ACCOUNTANT = 6;
}
