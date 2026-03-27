@extends('layouts.masterlayout')

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Edit Assigned Task</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Assigne Task</a></li>
              <li class="breadcrumb-item active">General Form</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="container-fluid">
          <div class="row">
            <!-- left column -->
            <div class="col-md-12">
              <!-- jquery validation -->
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Assigne Task</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                @if (session('success'))
                   <p class="alert alert-success text-center">{{ session('success') }}</p>
                @endif 
                <form action="{{ url('/update/assigned/task/') . '/' . $editAssigneTasks->id }}" enctype="multipart/form-data" method="post" id="quickForm">
                  @csrf 
                  @method('PATCH')
                  <div class="card-body">
                    <div class="form-group">
                        <label>Select Your Employee</label>
                        <select name="assigned_user[]" id="assigned_user" multiple="multiple" class="select2" data-placeholder="Select a Project" style="width: 100%;" required>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->emp_id}}" {{$employee->emp_id == $editAssigneTasks->assigned_user ? 'selected' : ''}}>{{ $employee->name }}</option>
                            @endforeach
                        </select>
                        @error('department')
                          {{ $message }}
                        @enderror
                        <div id="departmentError" class="text-danger" style="display: none;">Please select your department.</div>
                      </div>
                    <div class="form-group">
                        <label>Select Your Task</label>
                        <select name="assigned_task[]" id="assigned_task" value multiple="multiple" class="select2" data-placeholder="Select a Project" style="width: 100%;" required>
                            @foreach ($tasks as $task)
                                <option value="{{ $task->task_id}}" {{$task->task_id == $editAssigneTasks->assigned_task ? 'selected' : ''}}>{{ $task->name }}</option>
                            @endforeach
                        </select>
                        @error('user_id')
                          {{ $message }}
                        @enderror
                        <div id="departmentError" class="text-danger" style="display: none;">Please select your department.</div>
                    </div>
                    <div class="form-group">
                        <label>Note</label>
                        <textarea class="form-control" rows="3" name="note" placeholder="Enter Note Here" style="height: 116px;">{{ $editAssigneTasks->note }}</textarea>
                    </div>
                  </div>
                  <!-- /.card-body -->
                  <div class="card-footer">
                    <button type="submit"
                    class="btn btn-primary mb-3">Submit</button>
                  </div>
                </form>
              </div>
              <!-- /.card -->
              </div>
            <!--/.col (left) -->
            <!-- right column -->
            <div class="col-md-6">
  
            </div>
            <!--/.col (right) -->
          </div>
          <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>

@endsection