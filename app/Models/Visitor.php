<?php

namespace Rapid\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
	
    protected $table = 'visitors';

    protected $fillable = [
        'name',
        'document',
        'service_provider'
    ];

    public function phones()
    {
        return $this->hasMany('Rapid\Models\Phone', 'visitor_id');
    }

    public function vehicles()
    {
        return $this->hasMany('Rapid\Models\Vehicle', 'visitor_id');
    }

}
