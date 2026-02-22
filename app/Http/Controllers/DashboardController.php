<?php

namespace App\Http\Controllers;

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
}
