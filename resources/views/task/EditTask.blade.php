@extends('layouts.masterlayout')

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Create Project</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Create Project</a></li>
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
                  <h3 class="card-title">Create Task</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                @if (session('success'))
                   <p class="alert alert-success text-center">{{ session('success') }}</p>
                @endif
                <form action="{{ route('updatetask', $edittask->id ) }}" enctype="multipart/form-data" method="post" id="quickForm">
                  @csrf
                  @method('PATCH')
                  <div class="card-body">
                    <div class="form-group">
                      <label>Select Your Project</label>
                      <select name="project_id[]" id="project_id" multiple="multiple" class="select2" data-placeholder="Select a Project" style="width: 100%;" required>
                          @foreach ($projects as $project)
                              <option value="{{ $project->id}}" {{$project->id == $edittask->project_id ? 'selected' : ''}}>{{ $project->name }}</option>
                          @endforeach
                      </select>
                      @error('department')
                        {{ $message }}
                      @enderror
                      <div id="departmentError" class="text-danger" style="display: none;">Please select your department.</div>
                    </div>
                    <div class="form-group">
                      <label for="department" class="form-label">Module Name</label>
                      <input type="text" value="{{ $edittask->name }}" class="form-control" id="module" name="module" aria-describedby="emailHelp"
                          pattern="[A-Z a-z\s]{3,30}" placeholder="Enter Your Module Name" required>
                          @error('module')
                            {{ $message }}
                          @enderror
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