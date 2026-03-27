<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            padding: 20px;
        }
        .container {
            max-width: 650px;
            margin: auto;
            background: #ffffff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.08);
        }
        .header {
            background: #198754;
            color: #ffffff;
            padding: 15px;
            border-radius: 6px 6px 0 0;
            text-align: center;
        }
        .content {
            padding: 20px;
        }
        .label {
            font-weight: bold;
            color: #333;
        }
        .footer {
            text-align: center;
            font-size: 13px;
            color: #777;
            margin-top: 20px;
        }
        .badge {
            background: #198754;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 13px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h2>Task Completion Alert</h2>
    </div>

    <div class="content">
        <p>Dear <strong>Sir</strong>,</p>

        <p>The following task has been <span class="badge">Completed</span>.</p>

        <p><span class="label">Project Name:</span> {{ $assignedTask->taskList->projectlists->name ?? 'N/A' }}</p>
        <p><span class="label">Assigned Task:</span> {{ $assignedTask->taskList->name }}</p>
        <p><span class="label">Sub Module:</span> {{ $task->sub_module }}</p>
        <p><span class="label">Summary:</span> {{ $task->summary }}</p>
        <p><span class="label">Functionality:</span> {{ $task->functionality }}</p>
        <p><span class="label">Start Date:</span> {{ \Carbon\Carbon::parse($task->start_date)->format('d M Y') }}</p>
        <p><span class="label">Completion:</span> {{ $task->completion_precentage }}%</p>

        <p>Please review the task if required.</p>
    </div>

    <div class="footer">
        <p>This is an automated notification from the Task Management System.</p>
    </div>
</div>

</body>
</html>
