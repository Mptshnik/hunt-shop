<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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

    protected function personName():Attribute
    {
        if(!is_null($this->person))
        {
            return Attribute::make(fn()=> "{$this->person->last_name} {$this->person->first_name} {$this->person->midle_name}");
        }

        return Attribute::make(fn()=>'');
    }

    protected function organizationName():Attribute
    {
        if(!is_null($this->organisation))
        {
            return Attribute::make(fn()=> $this->organisation->name);
        }

        return Attribute::make(fn()=> '');
    }

    protected function juridicalAddressName():Attribute
    {
        if(is_null($this->juridical_address))
        {
            return Attribute::make(fn()=> '');
        }

        return Attribute::make(fn()=> $this->juridical_address);
    }

    protected $appends = ['person_name', 'organization_name', 'juridical_address_name'];


    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}
