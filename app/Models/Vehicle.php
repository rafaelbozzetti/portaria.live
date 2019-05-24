<?php

namespace Rapid\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
	
	protected $table = 'vehicles';

    protected $fillable = [
        'model',
        'color',
        'plate',
        'type'
    ];

    public function person()
    {
        return $this->belongsTo('Rapid\Models\Person');
    }

    public function visitor()
    {
        return $this->belongsTo('Rapid\Models\Visitor');
    }
}