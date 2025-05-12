<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkLog extends Model
{
    protected $fillable = [
        'job_type',
        'job_name',
        'start_time',
        'end_time',
        'status',
        'work_date',
    ];
}
