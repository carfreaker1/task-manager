<?php

namespace App\Http\Controllers;

use App\Models\AssignedTask;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\TaskTimeline;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Psy\CodeCleaner\ReturnTypePass;

class TaskController extends Controller
{
    public function createTask(){
        $projects = Project::get();
        return view('task.CreateTask', compact('projects'));
    }

    public function storeTask(Request $request){
        $request->validate([
            'module' => 'required',
            'project_id' => 'required',
        ]);
        // $projects = explode(',', $request->project_id);dd($projects);
        foreach($request->project_id as $project){
        Task::create([
            'name' => $request->module,
            'project_id' => $project
        ]);}
        return redirect()->back()->with('success', 'Task Created Succesfully');

    }

    public function viewTask(){
        $tasks = Task::get();
        $employees = User::get(['id','name']); 
        return view('task.TaskList',compact('tasks','employees'));
    }

    public function editTask($id){
        $id = decrypt($id);
        $edittask = Task::where('id',$id)->first();
        $projects = Project::get();
        return view('task.EditTask',compact('edittask','projects'));
        
    }

    public function updateTask(Request $request,$id){
        $request->validate([
            'module' => 'required',
            'project_id' => 'required',
        ]);
        foreach($request->project_id as $project){
            Task::where('id', $id)->update([
                'name' => $request->module,
                'project_id' => $project
            ]);}
        return redirect()->route('tasklist')->with('update', 'Task Updated Succesfully');
    }

    public function deleteTask($id){
        $id = decrypt($id);
        Task::where('id',$id)->delete();
        return redirect()->back()->with('delete', 'Task Deleted Succesfully');;   
    }

    //////////Assigned Task
    public function assignedTask(Request $request){
        $request->validate([
            'assigned_user' => 'required',
            'assigned_task' => 'required',
            'note' => 'required',
        ]);
        $tasks  = explode(',', $request->assigned_task);
        // dd($tasks);  

        foreach($tasks as $task_ids){
            foreach($request->assigned_user as $user_ids){
                AssignedTask::create([
                    'assigned_user' => $user_ids,
                    'assigned_task' => $task_ids,
                    'note' => $request->note,
                ]);
            }
        }
        return redirect()->back()->with('succes', 'Task Assigned Succesfully');
    }
                                                                                                                             
    public function listAssignedTask(){
        $userlogged = Auth::user()->role_id;
        //   $TaskStatus = [];
            if($userlogged == 3){
                $userAuth  = Auth::user()->id;
                $AssignedTasks = AssignedTask::where('assigned_user', $userAuth)->orderBy('assigned_user' ,'asc')->get();
                $TaskStatus = TaskStatus::get(); 
            }elseif($userlogged == 1)
            {
                $AssignedTasks = AssignedTask::orderBy('assigned_user' ,'asc')->get();
                $TaskStatus = TaskStatus::get(); 

            }
            
        ///Task Status for employees
        return view('task.AssignedTaskList' , compact('AssignedTasks','TaskStatus'));
    }

    public function editAssignedTask($id){ 
        $id = decrypt($id);
        $editAssigneTasks = AssignedTask::where('id' , $id)->first();
        $employees = User::get(['id as emp_id','name']); 
        $tasks = Task::get(['id as task_id', 'name']); 
        // dd($editAssignedTasks, $employees);
        return view('task.EditAssignedTask',compact('editAssigneTasks', 'employees' , 'tasks'));
    }

    public function updateAssignedTask(Request $request, $id){
        $request->validate([
            'assigned_user' => 'required',
            'assigned_task' => 'required',
            'note' => 'required',
        ]);
        $tasks  = $request->assigned_task;
        // dd($tasks);  
        foreach($tasks as $task_ids){
            foreach($request->assigned_user as $user_ids){
                AssignedTask::where('id' , $id)->update([
                    'assigned_user' => $user_ids,
                    'assigned_task' => $task_ids,
                    'note' => $request->note,
                ]);
            }
        }
        return redirect()->route('listassignedtask')->with('update', 'Task Assigned Succesfully');
    }

    public function deleteAssignedTask($id){
        $id = decrypt($id);
        AssignedTask::where('id',$id)->delete();
        return redirect()->back()->with('delete', 'Task Deleted Succesfully');;   
    }

    //////////////////Task Operations for the Employee////////////////////////

    public function storeTimeline(Request $request){
        $request->validate([
            'assigned_task' => 'required',
            'start_date' => 'required',
            'sub_module' => 'required',
            'summary' => 'required', 
            'functionality' => 'required',
            'task_status' => 'required',
        ]); 
        TaskStatus::create([
            'assigned_task' => $request->assigned_task,
            'start_date' => $request->start_date,
            'sub_module' => $request->sub_module,
            'summary' => $request->summary,
            'functionality' => $request->functionality,
            'task_status' => $request->task_status,
        ]);
        return redirect()->back()->with('successtime', 'Time Line Created Succesfully');

    }

    public function updateTimeline(Request $request){
        // dd($request->all());
        $request->validate([
            'assigned_task' => 'required',
            'start_date' => 'required',
            'sub_module' => 'required',
            'summary' => 'required',
            'functionality' => 'required',
            'task_status' => 'required',
        ]); 
        TaskStatus::where('id' , $request->task_status_id)->update([
            'assigned_task' => $request->assigned_task,
            'start_date' => $request->start_date,
            'sub_module' => $request->sub_module,
            'summary' => $request->summary,
            'functionality' => $request->functionality,
            'task_status' => $request->task_status,
        ]);
        return redirect()->back()->with('successtime', 'Time Line Created Succesfully');

    }

    public function deleteTimeline($id){
        $id = decrypt($id);
        TaskStatus::where('id',$id)->delete();
        return  redirect()->back()->with('deletetime', 'Time Line Deleted Succesfully');
    }

    
    
}

