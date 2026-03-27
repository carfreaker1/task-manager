<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\User;
use Illuminate\Http\Request;

class PermissionController extends Controller
{   
    // create permission
    public function create(){
        return view('permission.CreatePermission');
    }

    // save permission
    public function store(Request $request){
        $request->validate([
            'name' => 'required',
            'slug' => 'required',
        ]);
        Permission::create([
            'name' => $request->name,
            'slug' => $request->slug
        ]);
        return redirect()->back()->with('success','Permission Created Successfull');
    }
    
    public function view(){
        $permissions = Permission::all();
        return view('permission.ViewPermission',compact('permissions'));
    }

    public function edit($id)
    {   
        $id=decrypt($id);
        $permission = Permission::findOrFail($id);
        return view('permission.EditPermission', compact('permission'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:permissions,slug,' . $id
        ]);

        $permission = Permission::findOrFail($id);

        $permission->update([
            'name' => $request->name,
            'slug' => $request->slug
        ]);

        return redirect()->route('viewpermissions')->with('update', 'Permission Updated Successfully');
    }

    public function delete($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return redirect()->back()->with('delete', 'Permission Deleted Successfully');
    }

    public function managePermission($id){
        $role = Role::findOrFail(decrypt($id));
        // dd($role);
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        $permissions = Permission::get(['id','name']);
        return view('permission.ManagePermission', compact('permissions','role','rolePermissions'));
    }

    public function storeUserPermission(Request $request){
        // dd($request->role_id);
        // dd($request->permission_id);
        // $request->validate([
        //     'role_id' => 'required|array',
        //     'permission_id' => 'required'
        // ]);
    
        // $permissions = $request->permission_id;
        // $roles = $request->role_id;
    
        // $insertData = [];
    
        // foreach ($roles as $role) {
        //     foreach ($permissions as $permission) {
        //         $insertData[] = [
        //             'role_id' => decrypt($role),
        //             'permission_id' => decrypt($permission),
        //         ];
        //     }
        // }
    
        // RolePermission::insert($insertData);
    
        // return back()->with('success', 'Permissions assigned successfully!');


        $role = Role::find(decrypt($request->role_id));

        $role->permissions()->sync(($request->permissions) ?? []);
    
        return back()->with('success','Permissions Updated Successfully');
    }

    public function rolesPermissionView(){
        $roles =  Role::where('name' ,'!=', 'superadmin')->get();
        return view('permission.RolesList', compact('roles'));

    }
}
