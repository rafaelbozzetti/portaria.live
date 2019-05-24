<?php

namespace Rapid\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
	
	protected $table = 'people';

    protected $fillable = [
        'name',
        'email',
        'active'
    ];

    public function unit()
    {
        return $this->belongsTo('Rapid\Models\Unit');
    }

    public function vehicles()
    {
        return $this->hasMany('Rapid\Models\Vehicle', 'people_id');
    }

    public function phones()
    {
        return $this->hasMany('Rapid\Models\Phone', 'people_id');
    }
}
