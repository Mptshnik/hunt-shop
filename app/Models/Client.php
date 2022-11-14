<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Client extends Model
{
    use HasFactory;

    protected $guarded = [];

   /* protected $fillable = [
        'juridical_status_id',
        'last_name',
        'first_name',
        'middle_name',
        'organisation_name',
        'phone_number',
        'organisation_id',
        'taxpayer_number',
        'juridical_address',
        'physical_address',
    ];*/

    protected $hidden = [
        'organisation_id',
        'person_id',
        'juridical_status_id'
    ];

    public $timestamps = false;

    public function juridicalStatus()
    {
        return $this->belongsTo(JuridicalStatus::class);
    }

    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}
