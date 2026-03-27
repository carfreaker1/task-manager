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
    public function taskCompletionTrend()
    {
        // Sirf completed tasks
        $tasks = TaskStatus::where('task_status', 3)
            ->orderBy('updated_at', 'asc')
            ->get()
            ->groupBy(function ($task) {
                return $task->updated_at->format('Y-m-d');
            });

        $labels = [];
        $values = [];

        foreach ($tasks as $date => $taskGroup) {
            $labels[] = $date;
            $values[] = $taskGroup->count();
        }

        return response()->json([
            'labels' => $labels,
            'values' => $values,
        ]);
    }
    public function employeePerformance()
    {
        $users = User::get();

        $result = [];

        foreach ($users as $user) {

            $assignedCount = AssignedTask::where('assigned_user', $user->id)->count();

            $completedCount = TaskStatus::whereHas('AssignTaskName', function ($q) use ($user) {
                $q->where('assigned_user', $user->id);
            })->where('task_status', 3)->count();

            $pendingCount = $assignedCount - $completedCount;

            $result[] = [
                'employee'  => $user->name,
                'assigned'  => $assignedCount,
                'completed' => $completedCount,
                'pending'   => max($pendingCount, 0),
            ];
        }

        return response()->json($result);
    }

    public function taskStatusStacked()
    {
        $projects = Project::with([
            'tasks.assignedTasks.taskStatuses'
        ])->get();

        $labels = [];
        $pending = [];
        $inProgress = [];
        $completed = [];

        foreach ($projects as $project) {

            $labels[] = $project->name;

            $pendingCount = 0;
            $inProgressCount = 0;
            $completedCount = 0;

            foreach ($project->tasks as $task) {
                foreach ($task->assignedTasks as $assignedTask) {
                    foreach ($assignedTask->taskStatuses as $status) {

                        if ($status->task_status === '2') {
                            $pendingCount++;
                        }

                        if ($status->task_status === '1') {
                            $inProgressCount++;
                        }

                        if ($status->task_status === '3') {
                            $completedCount++;
                        }
                    }
                }
            }

            $pending[] = $pendingCount;
            $inProgress[] = $inProgressCount;
            $completed[] = $completedCount;
        }

        return response()->json([
            'labels'       => $labels,
            'pending'      => $pending,
            'in_progress'  => $inProgress,
            'completed'    => $completed,
        ]);
    }

}
