<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    protected $table = "time_slots";
    protected $fillable = ['opening_time', 'closing_time', 'day', 'branch_id'];
    protected $casts = [
        'id'           => 'integer',
        'branch_id'    => 'integer',
        'opening_time' => 'string',
        'closing_time' => 'string',
        'day'          => 'integer',
    ];
}
