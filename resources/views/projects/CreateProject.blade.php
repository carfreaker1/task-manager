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
                  <h3 class="card-title">Create Project</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                @if (session('success'))
                   {{-- <p class="alert alert-success text-center">{{ session('success') }}</p> --}}
                   <script>
                    $(document).ready(function() {
                      $(document).Toasts('create', {
                        title: 'Project',
                        body: 'Project Created Succesfully'
                    })
                    $(".toast").addClass('bg-success')
                    });    
                  </script>
                @endif
                <form action="{{ route('storeproject') }}" enctype="multipart/form-data" method="post" id="quickForm">
                  @csrf
                  <div class="card-body">
                    <div class="form-group">
                      <label for="project" class="form-label">Project Name</label>
                      <input type="text" value="{{ old('project') }}" class="form-control" id="project" name="project" aria-describedby="emailHelp"
                          pattern="[A-Z a-z\s]{3,30}" placeholder="Enter Your Project" required>
                          @error('project')
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