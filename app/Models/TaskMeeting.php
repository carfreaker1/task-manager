<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskMeeting extends Model
{
    use HasFactory;
    protected $fillable = [
        'meeting_no',
        'assigned_task_id',
        'users',
        'meet_link',
        'meeting_message',
        'start_time',
        'end_time'
    ];

    protected $casts = [
        'users' => 'array'
    ];
}
