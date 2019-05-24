<?php

namespace Rapid\Models;

use Illuminate\Database\Eloquent\Model;

use Rapid\Models\Registry;

class Registry extends Model
{
	
    protected $table = 'registry';

    protected $fillable = [
        'key',
        'value',
    ];
}
