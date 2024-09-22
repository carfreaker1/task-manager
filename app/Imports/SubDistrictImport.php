<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Models\SubDistrict;
use Maatwebsite\Excel\Concerns\ToModel;

class SubDistrictImport implements ToModel
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        return new SubDistrict([
            'district_id' => $row[1],
            'state_id' => $row[2],
            'sub_district_code' => $row[3],
            'sub_district_name' => $row[4],
        ]);
    }
}
