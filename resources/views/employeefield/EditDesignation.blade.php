@extends('layouts.masterlayout')

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Designation</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
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
                  <h3 class="card-title">Edit Designation</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                @if (session('success'))
                   <p class="alert alert-success text-center">{{ session('success') }}</p>
                @endif
                <form action="{{ route('updatedesignation', $edit->id) }}" enctype="multipart/form-data" method="post" id="quickForm">
                  @csrf
                  @method('PUT')
                  <div class="card-body">
                    <div class="form-group">
                      <label for="designation" class="form-label">Designation Name</label>
                      <input type="text" class="form-control" id="designation" value="{{ $edit->designation_name}}" name="designation" aria-describedby="emailHelp"
                          pattern="[A-Z a-z\s]{3,30}" placeholder="Enter Your Department" required>
                          @error('designation')
                           {{ $message }}
                          @enderror
                    </div>
                    <div class="form-group">
                      <label class="form-label">Select Your Department</label>
                      <select name="department" id="department" class="form-control" required>
                        <option value="">--- Select Your Department ---</option>
                        @foreach ($departments as $department)
                          <option value="{{ $department->id }}" {{ $department->id == $edit->department_id ? 'selected' : '' }} >{{ $department->name }}</option>
                        @endforeach
                      </select>
                        @error('department')
                        <span>{{ $message }}</span>
                        @enderror
                        <div id="departmentError" class="text-danger" style="display: none;">Please select your department.</div>
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