<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    public function createRole(){
        return view('roles.CreateRoles');
    }

    public function storeRole(Request $request){
        $request->validate([
            'name' => 'required',
        ]);
        Role::create([
            'name' => $request->name
        ]);
        return redirect()->back()->with('success','Role Created Successfull');
    }
    public function viewRoles(){
        $roles = Role::get()->all();
        // dd($roles);
        return view('roles.RolesList',compact('roles'));
    }

    public function editRole($id){
        $id = decrypt($id);
        $editRole = Role::where('id', $id)->first();
        // dd($editRole);
        return view('roles.EditRoles', compact('editRole'));
    }

    public function updateRole(Request $request, $id){ 
        $request->validate([
            'name' => 'required',
        ]);
        $editRole = Role::where('id', $id)->update([
            'name' => $request->name
        ]);
        // dd($editRole);
        return redirect()->route('viewroles')->with('update', 'Role Updated Succesfully');
    }

    public function deleteRole($id){ 
        $id = decrypt($id);
        $editRole = Role::where('id', $id)->delete();
        return redirect()->route('viewroles')->with('delete', 'Role Deleted Succesfully');
    }
}
