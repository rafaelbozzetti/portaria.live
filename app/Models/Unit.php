<?php

namespace Rapid\Models;

use Illuminate\Database\Eloquent\Model;
use Rapid\Models\Block;
use Rapid\Models\Person;

class Unit extends Model
{
	
	protected $table = 'units';

    protected $fillable = [
        'name',
        'number'
    ];

	
    public function block()
    {
        return $this->belongsTo('Rapid\Models\Block');
    }
	

    public function people()
    {
        return $this->hasMany('Rapid\Person');
    }

}
