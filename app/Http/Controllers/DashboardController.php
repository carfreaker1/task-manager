<?php

namespace App\Http\Controllers;

use App\Models\AssignedTask;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {   
        $totalProject = Project::count();
        $totalTask = Task::count();
        $completedTask = TaskStatus::where('task_status', 3)->count();
        $inProgressTask = TaskStatus::where('task_status', 1)->where('task_status', 2)->count();
        $pendingTask = TaskStatus::where('task_status',  2)->count();
        $overDueTask = TaskStatus::where('end_date', '<', now())
            ->where('task_status', '!=', 3)
            ->count();
        $activeUsers = User::where('status', '=', 1)->count();
        // dd($overDueProject);
        $data = [
            'totalProject' => $totalProject,
            'totalTask' => $totalTask,
            'inProgressTask' => $inProgressTask,
            'completedTask' => $completedTask,
            'pendingTask' => $pendingTask,
            'overDueTask' => $overDueTask,
            'activeUsers' => $activeUsers,
        ];
        return view('index',compact('data'));
    }

    public function taskStatus()
    {
        $pendingTask = TaskStatus::where('task_status', 2)->count();
        $inProgressTask = TaskStatus::whereIn('task_status', [1, 2])->count();
        $completedTask = TaskStatus::where('task_status', 3)->count();
        $overDueTask = TaskStatus::where('end_date', '<', now())
                        ->where('task_status', '!=', 3)
                        ->count();

        return response()->json([
            'pendingTask' => $pendingTask,
            'inProgressTask' => $inProgressTask,
            'completedTask' => $completedTask,
            'overDueTask' => $overDueTask,
        ]);
    }

    public function projectProgres()
{
    $projects = Project::with(['tasks.assignedTasks.taskStatuses'])->get();

    $result = [];

    foreach ($projects as $project) {

        $totalPercentage = 0;
        $totalCount = 0;

        foreach ($project->tasks as $task) {

            foreach ($task->assignedTasks as $assignedTask) {

                foreach ($assignedTask->taskStatuses as $status) {

                    $totalPercentage += $status->completion_precentage;
                    $totalCount++;
                }
            }
        }

        $average = $totalCount > 0 
            ? round($totalPercentage / $totalCount, 2) 
            : 0;

        $result[] = [
            'project' => $project->name,
            'percentage' => $average
        ];
    }

    return response()->json($result);
}
}
