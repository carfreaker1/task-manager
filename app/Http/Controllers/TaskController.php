<?php

namespace App\Http\Controllers;

use App\Mail\TaskCompletedMail;
use App\Models\AssignedTask;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\TaskTimeline;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Psy\CodeCleaner\ReturnTypePass;
use App\Mail\MeetingAssignedMail;
use App\Models\TaskMeeting;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    public function createTask()
    {
        $projects = Project::get();
        return view('task.CreateTask', compact('projects'));
    }

    public function storeTask(Request $request)
    {
        $request->validate([
            'module' => 'required',
            'project_id' => 'required',
        ]);
        // $projects = explode(',', $request->project_id);dd($projects);
        foreach ($request->project_id as $project) {
            Task::create([
                'name' => $request->module,
                'project_id' => $project
            ]);
        }
        return redirect()->back()->with('success', 'Task Created Succesfully');
    }

    public function viewTask()
    {

        $tasks = Task::get();
        $employees = User::get(['id', 'name']);
        return view('task.TaskList', compact('tasks', 'employees'));
    }

    public function editTask($id)
    {
        $id = decrypt($id);
        $edittask = Task::where('id', $id)->first();
        $projects = Project::get();
        return view('task.EditTask', compact('edittask', 'projects'));
    }

    public function updateTask(Request $request, $id)
    {
        $request->validate([
            'module' => 'required',
            'project_id' => 'required',
        ]);
        foreach ($request->project_id as $project) {
            Task::where('id', $id)->update([
                'name' => $request->module,
                'project_id' => $project
            ]);
        }
        return redirect()->route('tasklist')->with('update', 'Task Updated Succesfully');
    }

    public function deleteTask($id)
    {
        $id = decrypt($id);
        Task::where('id', $id)->delete();
        return redirect()->back()->with('delete', 'Task Deleted Succesfully');;
    }

    private function generateMeetingNo()
    {
        do {
            $number = 'GMEET' . rand(100000, 999999);
        } while (TaskMeeting::where('meeting_no', $number)->exists());

        return $number;
    }
    //////////Assigned Task
    public function assignedTask(Request $request)
    {
        try {
            DB::beginTransaction();


            $request->validate([
                'assigned_user' => 'required',
                'assigned_task' => 'required',
                'note' => 'required',
            ]);

            $tasks  = explode(',', $request->assigned_task);

            $duplicate = AssignedTask::whereIn('assigned_task', $tasks)
                ->whereIn('assigned_user', $request->assigned_user)
                ->exists();

            if ($duplicate) {
                return back()->with('errorDuplicate', 'Task already assigned to this user.');
            }

            foreach ($tasks as $task_ids) {

                $meetLink = null;
                $meetingNo = null;

                // ✅ Create meeting ONLY ONCE per task
                if ($request->meeting_option == 1) {

                    $meetController = new GoogleMeetController();

                    $start = Carbon::parse($request->start)->format('Y-m-d\TH:i:sP');
                    $end   = Carbon::parse($request->end)->format('Y-m-d\TH:i:sP');

                    $meetLink = $meetController->createMeetingDynamic(
                        $start,
                        $end,
                        "Task Discussion"
                    );

                    $meetingNo = $this->generateMeetingNo();
                }

                foreach ($request->assigned_user as $user_ids) {

                    $assignedTask = AssignedTask::create([
                        'assigned_user' => $user_ids,
                        'assigned_task' => $task_ids,
                        'note' => $request->note,
                    ]);

                    // ✅ Save SAME meeting for all users
                    if ($request->meeting_option == 1) {

                        TaskMeeting::create([
                            'meeting_no' => $meetingNo,
                            'assigned_task_id' => $assignedTask->id,
                            'users' => json_encode($request->assigned_user), // better
                            'meet_link' => $meetLink,
                            'meeting_message' => $request->meeting_message,
                            'start_time' => $request->start,
                            'end_time' => $request->end
                        ]);

                        // SEND MAIL
                        $user = User::find($user_ids);

                        Mail::to($user->email)->send(
                            new MeetingAssignedMail(
                                $user,
                                $meetLink,
                                $request->meeting_message,
                                $request->start,
                                $request->end
                            )
                        );
                    }
                }
            }
            DB::commit();
            return redirect()->back()->with('success', 'Task Assigned Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            Log::error("Creating this", ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'some error occur');
        }
    }

    public function scheduleGoogleMeetings(Request $request){
        try{
            // dd($request->all()) ;
            DB::beginTransaction();
            $request->validate([
                'assigned_user' => 'required',
                'start' => 'required',
                'meeting_message' => 'required',
                'end' => 'required',
            ]);
                               
            $meetController = new GoogleMeetController();

            $start = Carbon::parse($request->start)->format('Y-m-d\TH:i:sP');
            $end   = Carbon::parse($request->end)->format('Y-m-d\TH:i:sP');
            $meetingMessage = $request->meeting_message;
            $meetLink = $meetController->createMeetingDynamic(
                $start,
                $end,
                $meetingMessage
            );
            $meetingNo = $this->generateMeetingNo();

            TaskMeeting::create([
                'meeting_no' => $meetingNo,
                // 'assigned_task_id' => $assignedTask->id,
                'users' => json_encode($request->assigned_user), // better
                'meet_link' => $meetLink,
                'meeting_message' => $request->meeting_message,
                'start_time' => $request->start,
                'end_time' => $request->end
            ]);
            $users = User::whereIn('id', $request->assigned_user)->get();
            foreach ($users as $user) {
                Mail::to($user->email)->queue(
                    new MeetingAssignedMail(
                        $user,
                        $meetLink,
                        $request->meeting_message,
                        $request->start,
                        $request->end
                    )
                );
            }
        DB::commit();
            return redirect()->back()->with('success', 'Meeting Created Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            Log::error("Creating this", ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'some error occur');
        }

    }
    public function listAssignedTask()
    {
        $userlogged = Auth::user()->role_id;
        //   $TaskStatus = [];
        if ($userlogged == 3) {
            $userAuth  = Auth::user()->id;
            $AssignedTasks = AssignedTask::where('assigned_user', $userAuth)->orderBy('assigned_user', 'asc')->get();
            $TaskStatus = TaskStatus::get();
        } elseif ($userlogged == 1) {
            $AssignedTasks = AssignedTask::orderBy('assigned_user', 'asc')->get();
            $TaskStatus = TaskStatus::get();
        }

        ///Task Status for employees
        return view('task.AssignedTaskList', compact('AssignedTasks', 'TaskStatus'));
    }

    public function editAssignedTask($id)
    {
        $id = decrypt($id);
        $editAssigneTasks = AssignedTask::where('id', $id)->first();
        $employees = User::get(['id as emp_id', 'name']);
        $tasks = Task::get(['id as task_id', 'name']);
        // dd($editAssignedTasks, $employees);
        return view('task.EditAssignedTask', compact('editAssigneTasks', 'employees', 'tasks'));
    }

    public function updateAssignedTask(Request $request, $id)
    {
        $request->validate([
            'assigned_user' => 'required',
            'assigned_task' => 'required',
            'note' => 'required',
        ]);
        $tasks  = $request->assigned_task;
        // dd($tasks);  
        $duplicate = AssignedTask::whereIn('assigned_task', $tasks)
            ->whereIn('assigned_user', $request->assigned_user)
            ->exists();

        if ($duplicate) {
            return back()->with('errorDuplicate', 'Task already assigned to this user.');
        }
        foreach ($tasks as $task_ids) {
            foreach ($request->assigned_user as $user_ids) {
                AssignedTask::where('id', $id)->update([
                    'assigned_user' => $user_ids,
                    'assigned_task' => $task_ids,
                    'note' => $request->note,
                ]);
            }
        }
        return redirect()->route('listassignedtask')->with('update', 'Task Assigned Succesfully');
    }

    public function deleteAssignedTask($id)
    {
        $id = decrypt($id);
        AssignedTask::where('id', $id)->delete();
        return redirect()->back()->with('delete', 'Task Deleted Succesfully');;
    }

    //////////////////Task Operations for the Employee////////////////////////

    public function storeTimeline(Request $request)
    {
        $request->validate([
            'assigned_task' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'sub_module' => 'required',
            'summary' => 'required',
            'functionality' => 'required',
            'task_status' => 'required',
        ]);
        TaskStatus::create([
            'assigned_task' => $request->assigned_task,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'sub_module' => $request->sub_module,
            'summary' => $request->summary,
            'functionality' => $request->functionality,
            'task_status' => $request->task_status,
            // 'completion_precentage' => $request->completion_precentage,
        ]);
        return redirect()->back()->with('successtime', 'Time Line Created Succesfully');
    }

    public function updateTimeline(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'assigned_task' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'sub_module' => 'required',
            'summary' => 'required',
            'functionality' => 'required',
            'task_status' => 'required',
            'completion_precentage' => 'required|numeric|min:0|max:100',

        ]);
        $task = TaskStatus::where('id', $request->task_status_id)->first();

        $task->update([
            'assigned_task' => $request->assigned_task,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'sub_module' => $request->sub_module,
            'summary' => $request->summary,
            'functionality' => $request->functionality,
            'task_status' => $request->task_status,
            'completion_precentage' => $request->completion_precentage,
        ]);
        if ($request->task_status == 3) {

            $superAdmins = User::where('role_id', 1)->pluck('email');
            $assignedTask = AssignedTask::with(['taskList.projectlists', 'userList'])
                ->where('id', $task->assigned_task)
                ->first();

            if ($superAdmins->count() > 0 && $assignedTask) {
                Mail::to($superAdmins)->send(new TaskCompletedMail($task, $assignedTask));
            }
        }
        return redirect()->back()->with('successtime', 'Time Line Created Succesfully');
    }

    public function deleteTimeline($id)
    {
        $id = decrypt($id);
        TaskStatus::where('id', $id)->delete();
        return  redirect()->back()->with('deletetime', 'Time Line Deleted Succesfully');
    }

    public function viewGoogleMeetings(){
        $taskMeetings = TaskMeeting::get();
        $employees = User::get();
        // dd($taskMeeting);   
        return view('Meetings.meetinglist',compact('taskMeetings','employees'));
    }
    public function deleteGoogleMeetings(){
        $id = decrypt($id);
        TaskMeeting::where('id', $id)->delete();
        return  redirect()->back()->with('deletetime', 'Time Line Deleted Succesfully');
    }
}
