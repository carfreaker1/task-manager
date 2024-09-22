<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Exports\CustomExport;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
class ExcelPreviewController extends Controller
{
    public function importForm(){
        return view('excel.ImportForm');
    }
    public function excelPreview(){
        return view('excel.excelpreview');
    }

    public function import(Request $request) {
        // Validate file 
        // dd($request->all());
        $request->validate([
            'file' => 'required|file|mimes:xls,xlsx'
        ]);
    
        $file = $request->file('file');
        // return an array of arrays for the rows
        $data = Excel::toArray([], $file); 
    
        if (!empty($data[0])) {
            // Store the Excel data in the session
            session()->put('excel_data', $data[0]);
        //view with the Excel data and headings
        return view('excel.excelpreview', [
            'data' => $data[0]
        ]);
        }
        return redirect()->back()->withErrors('Invalid or empty Excel file.');
    }

    public function export(Request $request) {
    // custom headi ngs from the form
    $headings = $request->input('headings');
    // data from the session
    $originalData = session()->get('excel_data'); 

    // custom headings as the first row
    array_unshift($originalData, $headings);

    // Export the data with custom headings
    return Excel::download(new class($originalData) implements FromArray {
        protected $data;
        public function __construct($data)
        {
            $this->data = $data;
        }

        public function array(): array
        {
            return $this->data;
        }
    }, 'export_with_custom_headings.xlsx');
    }
    
}
