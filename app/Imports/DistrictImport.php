<?php

namespace App\Imports;

use App\Models\District;
use Maatwebsite\Excel\Concerns\ToModel;

class DistrictImport implements ToModel
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        return new District([
            'state_id' => $row[1],
            'district_code' => $row[2],
            'district_name' => $row[3],
        ]);
    }
}
