<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignedTask extends Model
{
    use HasFactory;
    
    protected $fillable = [
         'assigned_task',
         'assigned_user',
         'note',
    ];

    public function taskList(){
        return $this->belongsTo(Task::class,'assigned_task');
    }

    public function userList(){
        return $this->belongsTo(User::class,'assigned_user');
    }

}
