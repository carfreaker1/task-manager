<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'assigned_task',
        'start_date',
        'sub_module',
        'summary',
        'functionality',
        'task_status'
    ];

    public function AssignTaskName(){
        return $this->belongsTo(AssignedTask::class, 'assigned_task');
    }
}
