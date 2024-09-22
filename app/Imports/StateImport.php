<?php

namespace App\Imports;
use App\Models\State;
use App\Models\District;
use App\Models\SubDistrict;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StateImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $adressType = strtoupper($row['adress_type']);
            
            if ($adressType === 'STATE') {
                $state = State::updateOrCreate(
                    ['state_code' => $row['state_code']],
                    ['state_name' => $row['name']]
                )->id;
            }

            if ($adressType === 'DISTRICT' && isset($state)) {
                $district = District::updateOrCreate(
                    ['district_code' => $row['district_code'], 'state_id' => $state],
                    ['district_name' => $row['name']]
                )->id;
            }

            if ($adressType === 'SUB-DISTRICT' && isset($state) && isset($district)) {
                SubDistrict::updateOrCreate(
                    ['sub_district_code' => $row['sub_district_code'], 'district_id' => $district, 'state_id' => $state],
                    ['sub_district_name' => $row['name']]
                );
            }
        }
    }
}



// $adressType = strtoupper($row['adress_type']);
        
// if ($adressType === 'STATE') {
//     State::updateOrCreate(
//         ['state_code' => $row['state_code']],
//         ['state_name' => $row['name']]
//     );
// } elseif ($adressType === 'DISTRICT') {
//     $state = State::where('state_code', $row['state_code'])->first();
//     if ($state) {
//         District::updateOrCreate(
//             ['district_code' => $row['district_code'], 'state_id' => $state->id],
//             ['district_name' => $row['name']]
//         );
//     }
// } elseif ($adressType === 'SUB-DISTRICT') {
//     $state = State::where('state_code', $row['state_code'])->first();
//     $district = District::where('district_code', $row['district_code'])->where('state_id', $state->id)->first();
//     if ($state && $district) {
//         SubDistrict::updateOrCreate(
//             ['sub_district_code' => $row['sub_district_code'], 'district_id' => $district->id, 'state_id' => $state->id],
//             ['sub_district_name' => $row['name']]
//         );
//     }
// }
// } 