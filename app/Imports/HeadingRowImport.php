<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithGroupedHeadingRow;

class HeadingRowImport implements ToArray, WithGroupedHeadingRow
{
    /**
    * @param Collection $collection
    */

    protected $headings;

    public function  array(array $array)
    {
        $this->headings = array_keys($array[0]);
    }

    public function getHeading()
    {
        return $this->headings;
    }
}
