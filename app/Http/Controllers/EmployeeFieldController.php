<?php

namespace App\Http\Controllers;

use App\Exports\DepartmentsExport;
use App\Imports\DistrictImport;
use App\Imports\StateImport;
use App\Imports\SubDistrictImport;
use App\Models\EmployeeDepartment;
use App\Models\EmployeeDesignation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeFieldController extends Controller
{
    public function createDepartment(){
        return view('employeefield.CreateDepartment');
    }

    public function storeDepartment(Request $request){

        $request->validate([
            'department' => 'required',
        ]);
        // dd($request->all());
        EmployeeDepartment::create([
            'name' => $request->department,
            ]);
        return redirect()->back()->with('success','Department Created Successfull');

    }

    public function  editDepartment($id){
        
        $id = decrypt($id);
        // $edit = DB::table('employee_departments')->find($id);dd($edit->name);
        $edit = EmployeeDepartment::where('id',$id)->first();
       return view('employeefield.EditDepartment', compact('edit'));
       
    }

    public function updateDepartment(Request $request,$id){
        // dd($request->all());
        EmployeeDepartment::where('id',$id)->update([
            'name' => $request->department,
            ]);
        return redirect()->route('viewlist')->with('update','Department Updated Successfull');
  
    }
    
    public function deleteDepartment($id){
        
        $id = decrypt($id);
        EmployeeDepartment::where('id', $id)->delete();
        return redirect()->route('viewlist')->with('delete', 'Department Deleted Successfully');
    }
    

    public function createDesignation(){
        $departments = EmployeeDepartment::get();
        // dd($deigantion);
        return view('employeefield.CreateDesignation', compact('departments'));
    }

    public function storeDesignation(Request $request){
        // dd($request->all());
        $request->validate([
            'designation' => 'required',
            'department' => 'required'
        ]);
        EmployeeDesignation::create([
            'designation_name' => $request->designation,
            'department_id' => $request->department,
        ]);
        return redirect()->back()->with('successdesig','Designation Created Successfull');

        
    }

    public function  editDesignation($id){
        
        // $edit = DB::table('employee_departments')->find($id);dd($edit->name);
        $id = decrypt($id);
        $departments = EmployeeDepartment::get();
        $edit = EmployeeDesignation::where('id',$id)->first();  
        return view('employeefield.EditDesignation', compact('edit','departments'));
       
    }
    public function  updateDesignation(Request $request,$id){
        
        $request->validate([
            'designation' => 'required',    
            'department' => 'required'
        ]);
        EmployeeDesignation::where('id',$id)->update([
            'designation_name' => $request->designation,
            'department_id' => $request->department,
        ]);
        return redirect()->route('viewlist')->with('updatedesig','Designation Created Successfull');
    }

    public function deleteDesignation($id){
        
        $id = decrypt($id);
        EmployeeDesignation::where('id', $id)->delete();
        return redirect()->route('viewlist')->with('deletedesig', 'Designation Deleted Successfully');
    }

    public function viewList(){
        $departmentDesignations = EmployeeDesignation::all();
        // dd($departmentDesignations->departmentlist->name);
        return view('employeefield.fieldList', compact('departmentDesignations'));
        
    }

    public function DepartmentExport(Request $request){
        Excel::import(new StateImport, $request->file('file'));

        return redirect()->back()->with('success', 'Data imported successfully!');

    }

    public function fetchDesignations(Request $request, $departmentId)
    {
        // Fetch designations related to the selected department
        $designations = EmployeeDesignation::where('department_id', $departmentId)->get();
        // dd($designations);
        // Return designations as JSON response
        return response()->json($designations);
    }
}
