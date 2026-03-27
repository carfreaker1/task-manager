<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserRegister;
use Illuminate\Http\Request;
use App\Models\EmployeeDepartment;
use App\Models\EmployeeDesignation;
use App\Models\Project;
use App\Models\Role;
use App\Models\Task;
use App\Models\TaskStatus;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    public function registerUser(){
        $departments = EmployeeDepartment::get();
        $designations = EmployeeDesignation::get();
        $roles = Role::get();
        return view('users.CreatUsers',compact('departments','designations','roles'));
    }
    
    public function createUser(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'designation' => 'required',
            'department' => 'required',
            'role' => 'required',
            'password' => 'required',
        ]);
    // dd($request->all());
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'department_id' => $request->department,
            'designation_id' => $request->designation,
            'role_id' => $request->role,
            'password' => Hash::make($request->password),            
        ]);
        return redirect()->back()->with('success','User Created Successfull');
    }

    public function editUser($id){
        $id = decrypt($id);
        $departments = EmployeeDepartment::get();
        $desginations = EmployeeDesignation::get();
        $roles = Role::get();
        $edit = User::where('id',$id)->first();
        // $decryptedPassword = Hash::make(decrypt($edit->password));
        // dd($edit,$departments,$desginations);
        return view('users.EditUser',compact('edit','departments','desginations','roles'));

    }
    public function updateUser(Request $request,$id){
        
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'department' => 'required',
            'designation' => 'required',
            'role' => 'required',
            
        ]); 

        User::where('id',$id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'department_id' => $request->department,
            'designation_id' => $request->designation,
            'role_id' => $request->role,            
        ]);
        if ($request->filled('password')) {
            // Validate password if provided
            $request->validate([
                'password' => 'required|confirmed',
            ]);
            User::where('id',$id)->update([
            'password' => $request->password,            
            ]);
        }
        return redirect()->route('userslist')->with('update','User Updated Successfull');

    }

    public function deleteUser($id){
        $id = decrypt($id);
        User::where('id',$id)->delete();  
        return redirect()->back()->with('delete','User Updated Successfull');
    }

    public function usersList(){
        $users = User::all();
        // $users= DB::table('users')
        // ->select('users.*','employee_departments.name as dept_name','employee_designations.designation_name')
        // ->join('employee_departments','users.department_id','=','employee_departments.id')
        // ->join('employee_designations','users.designation_id','=','employee_designations.id')
        // ->get();
        // dd(Auth::id(f));
        return view('users.UserList', compact('users'));
    }
    public function updateStatus(Request $request)
    {   
        try {
            // User::where('id',auth()->user())->update([
            //     'status' => $request->status
            // ]);
            $user = auth()->user();
            $user->status = $request->status;
            $user->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function getStatuses()
    {
        return User::select('id', 'status')->get();
    }
}
