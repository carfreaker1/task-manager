<?php

namespace App\Exports;

use App\Imports\HeadingRowImport;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomExport implements FromArray, WithHeadings
{
    use Exportable;

    /**
    * @return \Illuminate\Support\Collection
    */

    protected $file;
    protected $headings;
    public function __construct($file, $headings)
    {
        $this->file = $file;
        $this->headings = $headings;
    }

    public function array(): array
    {
        // Load the data and apply custom headings
        $import = new HeadingRowImport();
        $import->import($this->file);

        $data = $import->toArray($this->file)[0];
        
        // Return array data with custom headings
        return $data;
    }

    public function headings(): array
    {
        return $this->headings;
    }
}
