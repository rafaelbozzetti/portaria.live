<?php

namespace Rapid\Models;

use Illuminate\Database\Eloquent\Model;

use Rapid\Models\Unit;

class Block extends Model
{
	
    protected $table = 'blocks';

    protected $fillable = [
        'name'
    ];

    public function unit()
    {
        return $this->hasMany('Rapid\Models\Unit');
    }

}
