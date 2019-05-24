<?php

namespace Rapid\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorLog extends Model
{
	
    protected $table = 'visitor_log';

    protected $fillable = [
        'type',
        'people_id',
        'visitor_id',
        'vehicle_id',
    ];
}
