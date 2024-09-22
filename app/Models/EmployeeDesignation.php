<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EmployeeDepartment;
class EmployeeDesignation extends Model
{
    use HasFactory;
    protected $fillable = [
        'designation_name',
        'department_id',
    ];

    public function departmentlist()
{
    return $this->belongsTo(EmployeeDepartment::class,'department_id');
}
}
