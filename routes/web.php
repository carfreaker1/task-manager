<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeFieldController;
use App\Http\Controllers\ExcelPreviewController;
use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\GoogleMeetController;
use App\Http\Controllers\LoginCotroller;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Models\Permission;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Broadcast;
use PhpParser\Node\Expr\FuncCall;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
//Routes For Login

Route::group(['middleware'=>'guest'],function(){
    
    Route::controller(LoginCotroller::class)->group(function(){
        Route::get('/','showLoginForm')->name('login');
        Route::post('auth/login','login')->name('Auth');
    });

    Route::controller(ForgetPasswordController::class)->group(function(){
        Route::get('forget/password', 'CreateForgetPassword')->name('forgetpasswordview');
        Route::post('store/forget/password', 'storeForgotPassword')->name('storeforgetpassword');
        Route::get('password/reset/{token}','showResetPasswordForm')->name('passwordreset');
        Route::post('reset-password', 'submitResetPasswordForm')->name('resetpasswordpost');
    
    });
});


Broadcast::routes(['middleware' => ['auth']]);
Route::group(['middleware'=>'auth'],function(){
    
    /** * Logout Route */
    Route::get('/logout',[LoginCotroller::class,'logout'])->name('logout');
    
    // Routes For Roles
    
    Route::controller(RolesController::class)->group(function(){
        Route::get('create/role', 'createRole')->name('createrole')->middleware('permission:create_role');
        Route::post('store/role', 'storeRole')->name('storerole');
        Route::get('view/roles', 'viewRoles')->name('viewroles')->middleware('permission:view_role');
        Route::get('edit/role/{id}', 'editRole')->name('editrole');
        Route::patch('update/role/{id}', 'updateRole')->name('updaterole');
        Route::get('delete/role/{id}', 'deleteRole')->name('deleterole');
        
    });
    Route::controller(PermissionController::class)->group(function(){
        Route::get('create/permission', 'create')->name('createpermission')->middleware('permission:create_permission');
        Route::post('store/permission', 'store')->name('storepermission');
        Route::get('view/permission', 'view')->name('viewpermissions')->middleware('permission:view_permission');
        Route::get('edit/permission/{id}', 'edit')->name('editpermission');
        Route::patch('update/permission/{id}', 'update')->name('updatepermission');
        Route::get('delete/permission/{id}', 'delete')->name('deletepermission');
        Route::get('roles/permission/', 'rolesPermissionView')->name('rolespermissionview')->middleware('permission:manage_permission');
        Route::get('manage/permission/{id}', 'managePermission')->name('managepermission');
        Route::post('store/user/permission', 'storeUserPermission')->name('storeuserpermission');
        
    });
    
    //Routes For User
    Route::controller(UserController::class)->group(function(){
        // Route::get('user','registerUser')->name('registeruser');+
        Route::get('index', 'index')->name('index');
        Route::get('user', 'registerUser')->name('registeruser')->middleware('permission:create_user');
        Route::post('user/create', 'createUser')->name('createuser');
        Route::get('edit/user/{id}', 'editUser')->name('edituser');
        Route::put('update/user/{id}', 'updateUser')->name('updateuser');
        Route::put('delete/user/{id}', 'deleteUser')->name('deleteuser');
        Route::get('user/list', 'usersList')->name('userslist')->middleware('permission:view_user');
        Route::post('/user/status', 'updateStatus');
        Route::get('/users/status', 'getStatuses');
    });

    // Route For Department & Designation
    Route::controller(EmployeeFieldController::class)->group(function(){
        Route::get('create/department', 'createDepartment')->name('createdepartment')->middleware('permission:create_department');
        Route::post('store/department', 'storeDepartment')->name('storedepartment');
        Route::get('edit/department/{id}', 'editDepartment')->name('editdepartment');
        Route::put('update/department/{id}', 'updateDepartment')->name('updatedepartment');
        Route::get('delete/department/{id}', 'deleteDepartment')->name('deletedepartment');
        Route::get('create/designation', 'createDesignation')->name('createdesignation')->middleware('permission:create_designation');
        Route::post('store/designation', 'storeDesignation')->name('storedesignation');
        Route::get('edit/designation/{id}', 'editDesignation')->name('editdesignation');
        Route::put('update/designation/{id}', 'updateDesignation')->name('updatedesignation');
        Route::get('delete/designation/{id}', 'deleteDesignation')->name('deletedesignation');
        Route::get('department/designation/list', 'viewList')->name('viewlist')->middleware('permission:view_department_designation');
        Route::get('/fetch-designations/{department}', 'fetchDesignations');

        ///Export Import Routes
        Route::post('import/department/', 'DepartmentExport')->name('importstate');
    });

    //Route For Project Management
    Route::controller(ProjectController::class)->group(function(){
        Route::get('create/project', 'createProject')->name('createproject')->middleware('permission:create_project');
        Route::post('store/project', 'storeProject')->name('storeproject');
        Route::get('edit/project/{id}', 'editProject')->name('editproject');
        Route::patch('update/project/{id}', 'updateProject')->name('updateproject');
        Route::get('delete/project/{id}', 'deleteProject')->name('deleteproject');
        Route::get('project/list', 'viewProject')->name('projectlist')->middleware('permission:view_project');

    });
    //Routes for Task
    Route::controller(TaskController::class)->group(function(){
        Route::get('create/task' ,'createTask')->name('createtask')->middleware('permission:createtask');
        Route::post('store/task' ,'storeTask')->name('storetask');
        Route::get('task/list' ,'viewTask')->name('tasklist')->middleware('permission:view_task');
        Route::get('edit/task/{id}' ,'editTask')->name('edittask');
        Route::patch('update/task/{id}' ,'updateTask')->name('updatetask');
        Route::get('delete/task/{id}' ,'deleteTask')->name('deletetask');
        Route::post('assigned/task/' ,'assignedTask')->name('assignedtask');

        //Route for Assigned Tasks
        Route::get('view/assigned/task/' ,'listAssignedTask')->name('listassignedtask')->middleware('permission:view_assigned_task');
        Route::get('edit/assigned/task/{id}' ,'editAssignedTask')->name('editassignedtask');
        Route::patch('update/assigned/task/{id}' ,'updateassignedtask')->name('updateassignedtask');
        Route::get('delete/assigned/task/{id}' ,'deleteAssignedTask')->name('deletelistassignedtask');

        //Route for Employee for update timeline
        Route::get('create/timleline' ,'createTimeline')->name('createtimlenine');
        Route::post('store/timleline' ,'storeTimeline')->name('storetimeline');
        Route::patch('update/timleline' ,'updateTimeline')->name('updatetimeline');
        Route::get('delete/timleline/{id}' ,'deleteTimeline')->name('deletetimeline');
        Route::get('view/google-meetings' ,'viewGoogleMeetings')->name('listgooglemeetings')->middleware('permission:manage_meeting');
        Route::post('schedule/google-meetings' ,'scheduleGoogleMeetings')->name('schedulegooglemeetings');
        Route::get('delete/google-meetings/{id}' ,'deleteGoogleMeetings')->name('deleteGoogleMeetings');
    });

    Route::controller(ExcelPreviewController::class)->group(function(){
        Route::get('excelpreview', 'excelPreview')->name('excelpreview')->middleware('permission:excel_preview');
        Route::post('import', 'import')->name('import');
        Route::post('export', 'export')->name('export');
        Route::get('/excel/import', 'importForm')->name('excelImport');
    });
    Route::controller(ChatController::class)->group(function(){
        Route::get('/messages','index')->name('chat.home');
        Route::get('/chat/{id}/messages', 'fetchMessages');
        Route::post('/send-message', 'sendMessage')->name('chat.send');
    });

    // Dashboard Controller
    Route::controller(DashboardController::class)->group(function(){
        Route::get('/dashboard/task-status', 'taskStatus')->name('dashboard.task.status');
        Route::get('index', 'index')->name('index');
        Route::get('/dashboard/project-progress', 'projectProgres')->name('project.progres');
        Route::get('/dashboard/task-completion-trend', 'taskCompletionTrend')->name('task-completion-trend');
        Route::get('/dashboard/employee-performance', 'employeePerformance')->name('employee.performance');
        Route::get('/dashboard/task-status-stacked', 'taskStatusStacked')->name('task.status-stacked');
    });

    Route::get('/create-meeting', [GoogleMeetController::class, 'createMeeting']);
    Route::get('/google-callback', [GoogleMeetController::class, 'handleGoogleCallback']);
    Route::get('/google-auth', [GoogleMeetController::class, 'redirectToGoogle']);
    
    // AI Chatbot Route
    Route::post('/api/chatbot', [App\Http\Controllers\ChatBotController::class, 'handleChat'])->name('chatbot.send');
    
    Route::get('/test-gemini-models', function() {
        $response = Illuminate\Support\Facades\Http::get('https://generativelanguage.googleapis.com/v1beta/models?key=' . config('services.gemini.key'));
        return response()->json($response->json());
    });
    Route::get('/write-models-to-file', function() {
        $response = Illuminate\Support\Facades\Http::get('https://generativelanguage.googleapis.com/v1beta/models?key=' . config('services.gemini.key'));
        file_put_contents(public_path('models.json'), $response->body());
        return "written";
    });
});