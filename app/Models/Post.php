<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    protected $fillable = [
        'name',
        'description'
    ];
}
