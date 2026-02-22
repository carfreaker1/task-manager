@extends('layouts.masterlayout')

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Project List</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Project</a></li>
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
                  <h3 class="card-title">Projects Table</h3>
                </div>
                  @if (session('update'))
                    {{-- <p class="alert alert-success text-center alert-dismissible">{{ session('update') }}</p> --}}
                    <script>
                      $(document).ready(function() {
                        $(document).Toasts('create', {
                          title: 'Project',
                          body: 'Project Created Succesfully'
                      })
                      $(".toast").addClass('bg-success')
                      // Toast.fire({
                      //   icon: 'success',
                      //   title: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
                      // })
                      });    
                    </script>
                  @elseif(session('delete'))
                   {{-- <p class="alert alert-success text-center alert-dismissible">{{ session('delete') }}</p> --}}
                    <script>
                      $(document).ready(function() {
                        $(document).Toasts('create', {
                          title: 'Project',
                          body: 'Project Deleted Succesfully'
                      })
                      $(".toast").addClass('bg-danger')
                      });    
                    </script>
                  @elseif(session('succes'))
                   {{-- <p class="alert alert-success text-center alert-dismissible">{{ session('delete') }}</p> --}}
                    <script>
                      $(document).ready(function() {
                        $(document).Toasts('create', {
                          title: 'Project',
                          body: 'Project Deleted Succesfully'
                      })
                      $(".toast").addClass('bg-success')
                      });    
                    </script>
                  @elseif(session('errorDuplicate'))
                   {{-- <p class="alert alert-success text-center alert-dismissible">{{ session('delete') }}</p> --}}
                    <script>
                      $(document).ready(function() {
                        $(document).Toasts('create', {
                          title: 'Project',
                          body: '{{ (session('errorDuplicate')) }}'
                      })
                      $(".toast").addClass('bg-warning')
                      });    
                    </script>
                  @endif
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped project-table">
                    <thead>
                    <tr>
                      <th>Sr.NO</th>
                      <th>Task Name</th>
                      <th>Project Name</th>
                      <th>Edit Task</th>
                      <th>Delete Task</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($tasks as $task)
                            <tr>
                              <td>{{ $loop->iteration }} <input class="form-check-input select-doctor" value="{{ $task->id }}" type="checkbox"></td>
                              <td>{{ $task->name ?? '' }}</td>
                              <td>{{ $task->projectlists->name ?? '' }}</td>
                              <td><a href="{{ url('/edit/task/') . '/' . encrypt($task->id) }}"><button type="button" class="btn btn-primary btn-sm">Edit task</button></a></td>
                              <td><a href="{{ url('/delete/task/') . '/' . encrypt($task->id) }}"><button type="button" class="btn btn-danger btn-sm">Delete task</button></a></td>
                            </tr>
                        @endforeach
                    </tbody>
                  </table>
                  {{-- Model  Start From Here --}}
                  <p><b><u>Please Select The Task for Assign The Task To Employees</u></b></p>
                  <button type="button" class="btn btn-primary" id="assign_employees">Assign Employee</button>
                    <div class="modal fade" id="modal-lg" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h4 class="modal-title">Assign Your Project</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <div class="modal-body" >
                              <form action="{{route('assignedtask')}}" enctype="multipart/form-data" method="post" id="quickForm" >
                                @csrf
                                <p id="taks_id" ></p>
                                <div class="form-group">
                                    <label>Select Your Employee</label>
                                    <select name="assigned_user[]" id="assigned_user" multiple="multiple" class="select2" data-placeholder="Select a Project" style="width: 100%;" required>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id}}">{{ $employee->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('assigned_user')
                                      {{ $message }}
                                    @enderror
                                    <div id="departmentError" class="text-danger" style="display: none;">Please select your department.</div>
                                </div>
                                <div class="form-group">
                                    <label>Note</label>
                                    <textarea class="form-control" rows="3" name="note" placeholder="Enter Note Here" style="height: 116px;"></textarea>
                                </div>
                                 <button type="submit" class="btn btn-primary">Save changes</button>
                              </form>
                            </div>
                            <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            
                            </div>
                        </div>
                        <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    {{-- Modal Ends Here --}}
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