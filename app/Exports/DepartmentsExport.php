<?php

namespace App\Exports;

use App\Models\EmployeeDepartment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DepartmentsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings():array
    {
        return[
            'Department Id',
            'Department Name'
        ];
    }


    public function collection()
    {
        return EmployeeDepartment::select('id','name')->get();
    }
}
