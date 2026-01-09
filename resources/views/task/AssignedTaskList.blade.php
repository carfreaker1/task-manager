@extends('layouts.masterlayout')

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Assigned Task List</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Assigned Task</a></li>
              <li class="breadcrumb-item active">Table</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Task Table</h3>
                </div>
                @if (session('update'))
                  {{-- <p class="alert alert-success text-center alert-dismissible">{{ session('update') }}</p> --}}
                  <script>
                    $(document).ready(function() {
                      $(document).Toasts('create', {
                        title: 'Role',
                        body: 'Role Created Succesfully'
                    })
                    $(".toast").addClass('bg-success')
                    });    
                  </script>
                @elseif(session('delete'))
                 {{-- <p class="alert alert-success text-center alert-dismissible">{{ session('delete') }}</p> --}}
                  <script>
                    $(document).ready(function() {
                      $(document).Toasts('create', {
                        title: 'Assigned Task',
                        body: 'Assigned Task Deleted Succesfully'
                    })
                    $(".toast").addClass('bg-danger')
                    });    
                  </script>
                @elseif(session('successtime'))
                 {{-- <p class="alert alert-success text-center alert-dismissible">{{ session('delete') }}</p> --}}
                  <script>
                    $(document).ready(function() {
                      $(document).Toasts('create', {
                        title: 'Assigned Task Status',
                        body: 'Assigned Task Status Done Succesfully'
                    })
                    $(".toast").addClass('bg-success')
                    });    
                  </script>
                @elseif(session('deletetime'))
                 {{-- <p class="alert alert-success text-center alert-dismissible">{{ session('delete') }}</p> --}}
                  <script>
                    $(document).ready(function() {
                      $(document).Toasts('create', {
                        title: 'Assigned Task Status',
                        body: 'Assigned Task Status Delete Succesfully'
                    })
                    $(".toast").addClass('bg-danger')
                    });    
                  </script>
                @endif
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <th>Sr.NO</th>
                      <th>User Name</th>
                      <th>Project Name</th>
                      <th>Assigned Task</th>
                      <th>Note</th>
                      @if(isset($TaskStatus) && count($TaskStatus) > 0)
                        <th>Start Date</th>
                        <th>Sub Module</th>
                        <th>Summary</th>
                        <th>Functionality</th>
                        <th>Status</th>
                      @endif
                      @if(Auth::user()->role_id == 3 || Auth::user()->role_id == 1)
                        <th>Create Time Line for Task</th>
                        <th>Delete Time Line for Task</th>
                      @endif
                      @if (Auth::user()->role_id == 1)           
                        <th>Edit Assigned Task</th>
                        <th>Edit Delete Task</th>
                      @endif
                    </tr>
                    </thead>
                    <tbody>
                      @foreach ($AssignedTasks as $AssignedTask)
                      <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $AssignedTask->userList ? $AssignedTask->userList->name : '' }}</td>
                          <td>{{ $AssignedTask->taskList ? $AssignedTask->taskList->projectlists->name : '' }}</td>
                          <td>{{ $AssignedTask->taskList ? $AssignedTask->taskList->name : '' }}</td>
                          <td>{{ $AssignedTask->note ?? '' }}</td>
                  
                          @php
                              $taskFound = false;
                          @endphp
                  
                          @if(isset($TaskStatus) && count($TaskStatus) > 0)
                              @foreach ($TaskStatus as $task)
                                  @if($AssignedTask->id == $task->assigned_task)
                                      <td>{{ date('d-m-y', strtotime($task->start_date)) }}</td>
                                      <td>{{ $task->sub_module ?? '' }}</td>
                                      <td>{{ $task->summary ?? '' }}</td>
                                      <td>{{ $task->functionality ?? '' }}</td>
                                      @if($task->task_status == 1)
                                        <td class="btn-warning">Working </td>
                                      @elseif($task->task_status == 2)
                                        <td class="btn-danger">Pending <br> @if(isset($task->completion_precentage)) {{ $task->completion_precentage }}% Complete @endif</td>
                                      @elseif($task->task_status == 3)
                                        <td class="btn-success">Complete<br>{{date('d-m-y', strtotime($task->updated_at))}}</td>
                                      @else
                                        <td>>NA</td>
                                      @endif
                                      <td>
                                        @if ($task->task_status == 3 && Auth::user()->role_id == 3)
                                          Contact To Admin For Edit Task Status
                                        @else
                                          <button type="button" class="btn btn-primary btn-sm edit_task_timeline" data-task='@json($task)' value="{{ $AssignedTask->id }}">Edit Your Task Status</button>
                                          {{-- <button type="button" class="btn btn-danger btn-sm edit_task_timeline" data-task='@json($task)' value="{{ $AssignedTask->id }}">Edit Your Task Status</button> --}}
                                      @endif
                                        </td>
                                      <td>
                                          <a href="{{route('deletetimeline' , encrypt($task->id))}}"><button type="button" class="btn btn-danger btn-sm " >Delete Your Task Status</button></a>
                                      </td>
                                      @php
                                          $taskFound = true;
                                          break;
                                      @endphp
                                  @endif
                              @endforeach
                          @endif
                  
                          @if(!$taskFound)
                              <td>NA</td>
                              <td>NA</td>
                              <td>NA</td>
                              <td>NA</td>
                              <td style="color: red">Project Not Started Yet</td>
                              <td><button type="button" class="btn btn-primary btn-sm task_timeline" value="{{ $AssignedTask->id }}">Make Assigned Task Timeline</button></td>
                              <td>NA</td>
                          @endif

                          @if (Auth::user()->role_id == 1)
                          <td>
                              <a href="{{ url('edit/assigned/task/' . encrypt($AssignedTask->id)) }}">
                                  <button type="button" class="btn btn-primary btn-sm">Edit Assigned Task</button>
                              </a>
                          </td>
                          <td>
                              <a href="{{ url('/delete/assigned/task/' . encrypt($AssignedTask->id)) }}">
                                  <button type="button" class="btn btn-danger btn-sm">Delete Assigned Task</button>
                              </a>
                          </td>
                      @endif
                      </tr>
                  @endforeach
                  
                    </tbody>
                  </table>
                </div>
                <!-- Modal for Create Task Status -->
                <div class="modal fade" id="modal-create" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                      <div class="modal-content">
                          <div class="modal-header">
                              <h4 class="modal-title">Create Your Status For Project</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                              </button>
                          </div>
                          <div class="modal-body">
                              <form action="{{ route('storetimeline') }}" enctype="multipart/form-data" method="post" id="quickFormCreate">
                                  @csrf
                                  <p id="assigned_task_create"></p>
                                  <div class="form-group">
                                      <label>Date:</label>
                                      <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                          <input type="date" name="start_date" class="form-control">
                                      </div>
                                      @error('start_date')
                                          {{ $message }}
                                      @enderror
                                  </div>
                                  <div class="form-group">
                                      <label for="sub_module" class="form-label">Sub Module Name</label>
                                      <input type="text" value="{{ old('sub_module') }}" class="form-control" id="sub_module" name="sub_module" aria-describedby="emailHelp" pattern="[A-Z a-z\s]{3,30}" placeholder="Enter Your Sub Module Name" required>
                                      @error('sub_module')
                                          {{ $message }}
                                      @enderror
                                  </div>
                                  <div class="form-group">
                                      <label for="summary" class="form-label">Summary</label>
                                      <textarea type="text" value="{{ old('summary') }}" class="form-control" id="summary" name="summary" aria-describedby="emailHelp" pattern="[A-Z a-z\s]{3,30}" placeholder="Enter Your Summary Name" required></textarea>
                                      @error('summary')
                                          {{ $message }}
                                      @enderror
                                  </div>
                                  <div class="form-group">
                                      <label for="functionality" class="form-label">On Which Functionality Working</label>
                                      <input type="text" value="{{ old('functionality') }}" class="form-control" id="functionality" name="functionality" aria-describedby="emailHelp" pattern="[A-Z a-z\s]{3,30}" placeholder="Enter Your Functionality Name" required>
                                      @error('functionality')
                                          {{ $message }}
                                      @enderror
                                  </div>
                                  <div class="form-group">
                                      <label class="form-label">Status of The Task</label>
                                      <select name="task_status" id="task_status" class="form-control" required>
                                          <option value="">--- Select Your Status For Task ---</option>
                                          <option value="1">Working</option>
                                          <option value="2">Pending</option>
                                          <option value="3">Complete</option>
                                      </select>
                                      @error('task_status')
                                          {{ $message }}
                                      @enderror
                                  </div>
                                  {{-- <div class="form-group">
                                      <label class="form-label">Completion Precentage of The Task</label>
                                      <input id="range_5" type="text" name="completion_precentage" value="">
                                  </div> --}}
                                  <button type="submit" class="btn btn-primary">Save changes</button>
                              </form>
                          </div>
                          <div class="modal-footer justify-content-between">
                              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          </div>
                      </div>
                  </div>
                </div>

                <!-- Modal for Edit Task Status -->
                <div class="modal fade" id="modal-edit" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                      <div class="modal-content">
                          <div class="modal-header">
                              <h4 class="modal-title">Edit Your Task Status</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                              </button>
                          </div>
                          <div class="modal-body">
                              <form action="{{ route('updatetimeline') }}" enctype="multipart/form-data" method="post" id="quickFormEdit">
                                  @csrf
                                  @method('PATCH')
                                  <input type="hidden" id="task_status_id" name="task_status_id">
                                  <p id="assigned_task_edit"></p>
                                  <div class="form-group">
                                      <label>Date:</label>
                                      <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                          <input type="date" name="start_date" class="form-control">
                                      </div>
                                      @error('start_date')
                                          {{ $message }}
                                      @enderror
                                  </div>
                                  <div class="form-group">
                                      <label for="sub_module" class="form-label">Sub Module Name</label>
                                      <input type="text" value="{{ old('sub_module') }}" class="form-control" id="sub_module_edit" name="sub_module" aria-describedby="emailHelp" pattern="[A-Z a-z\s]{3,30}" placeholder="Enter Your Sub Module Name" required>
                                      @error('sub_module')
                                          {{ $message }}
                                      @enderror
                                  </div>
                                  <div class="form-group">
                                      <label for="summary" class="form-label">Summary</label>
                                      <textarea type="text" value="{{ old('summary') }}" class="form-control" id="summary_edit" name="summary" aria-describedby="emailHelp" pattern="[A-Z a-z\s]{3,30}" placeholder="Enter Your Summary Name" required></textarea>
                                      @error('summary')
                                          {{ $message }}
                                      @enderror
                                  </div>
                                  <div class="form-group">
                                      <label for="functionality" class="form-label">On Which Functionality Working</label>
                                      <input type="text" value="{{ old('functionality') }}" class="form-control" id="functionality_edit" name="functionality" aria-describedby="emailHelp" pattern="[A-Z a-z\s]{3,30}" placeholder="Enter Your Functionality Name" required>
                                      @error('functionality')
                                          {{ $message }}
                                      @enderror
                                  </div>
                                  <div class="form-group">
                                      <label class="form-label">Status of The Task</label>
                                      <select name="task_status" id="task_status_edit" class="form-control" required>
                                          <option value="">--- Select Your Status For Task ---</option>
                                          <option value="1">Working</option>
                                          <option value="2">Pending</option>
                                          <option value="3">Complete</option>
                                      </select>
                                      @error('task_status')
                                          {{ $message }}
                                      @enderror
                                  </div>
                                  <div class="form-group">
                                    <label class="form-label">Completion Precentage of The Task</label>
                                    <input id="range_5" type="text" name="completion_precentage" value="">
                                </div>
                                  <button type="submit" class="btn btn-primary">Save changes</button>
                              </form>
                          </div>
                          <div class="modal-footer justify-content-between">
                              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          </div>
                      </div>
                  </div>
                </div>

                <!-- /.modal-dialog -->
              </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
      </section>

@endsection