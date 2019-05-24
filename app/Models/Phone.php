<?php

namespace Rapid\Models;

use Illuminate\Database\Eloquent\Model;
use Rapid\Models\Person;

class Phone extends Model
{
	
    protected $table = 'phones';

    protected $fillable = [
        'type',
        'number'
    ];

    public function person()
    {
        return $this->belongsTo('Rapid\Models\Person');
    }

}
