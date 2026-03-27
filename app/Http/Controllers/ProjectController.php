<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Cache\RedisTagSet;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function createProject(){
        return view('projects.CreateProject');
    }

    public function storeProject(Request $request){

        $request->validate([
            'project'=>'required',
        ]);
           
    
        Project::create([
            'name' => $request->project,
        ]);
        return redirect()->back()->with('success','Project Created Successfull');
    }

    public function viewProject(){
        $projectlists = Project::all();
        // dd($projectlists);
        return view('projects.ProjectList', compact('projectlists'));
    }

    public function editProject($id){
        $id = decrypt($id);
        $editProject = Project::where('id', $id)->first();
        // dd($editProject);
        return view('projects.EditProject', compact('editProject'));
    }

    public function updateProject(Request $request,$id){

        $request->validate([
            'project' => 'required',
        ]);

        Project::where('id',$id)->update([
            'name' => $request->project
        ]);
        return redirect()->route('projectlist')->with('update','Project Updated Successfull');
    }

    public function deleteProject($id){
        $id = decrypt($id);
        Project::where('id',$id)->delete();
        return redirect()->back()->with('delete','Project Updated Successfull');;
    }

}
