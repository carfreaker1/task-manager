@extends('layouts.masterlayout')

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>General Form</h1>
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
                  <h3 class="card-title">Register User</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                @if (session('success'))
                    {{-- <p class="alert alert-success text-center alert-dismissible">{{ session('success') }}</p> --}}
                    <script>
                      $(document).ready(function() {
                        $(document).Toasts('create', {
                          title: 'User',
                          body: 'User Created Succesfully'
                      })
                      $(".toast").addClass('bg-success')
                      });    
                    </script>
                @endif
                <form action="{{ route('createuser') }}" enctype="multipart/form-data" method="post" id="quickForm">
                  @csrf
                  <div class="card-body">
                    <div class="form-group">
                      <label for="name" class="form-label">Name</label>
                      <input type="text" class="form-control" id="name" value="{{ old('name') }}" name="name" aria-describedby="emailHelp"
                          pattern="[A-Z a-z\s]{3,30}" placeholder="Enter Your Name" required>
                          @error('name')
                            {{ $message }}  
                          @enderror
                    </div>
                    <div class="form-group">
                      <label for="email" class="form-label">Email address</label>
                      <input type="email" required value="{{ old('email') }}" class="form-control" name="email" id="email"
                        placeholder="Enter Your Email">
                        @error('email')
                         {{ $message }}  
                        @enderror
                      <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                    </div>
                    <div class="form-group">
                      <label class="form-label">Select Your Department</label>
                      <select name="department" id="department" class="form-control"  style="width: 100%;" required>
                          <option value="">--- Select Your Department ---</option>
                          @foreach ($departments as $department)
                              <option value="{{ $department->id }}">{{ $department->name }}</option>
                          @endforeach
                      </select>
                      @error('department')
                        {{ $message }}
                      @enderror
                      <div id="departmentError" class="text-danger" style="display: none;">Please select your department.</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Select Your Designation</label>
                        <select name="designation" id="designation" class="form-control" required>
                            <option value="">--- Select Your Designation ---</option>
                        </select>
                        @error('designation')
                         {{ $message }}
                        @enderror
                        <div id="designationError" class="text-danger" style="display: none;">Please select your designation.</div>
                    </div>
                    <div class="form-group">
                      <label class="form-label">Select Role</label>
                      <select name="role" id="role" class="form-control"  style="width: 100%;" required>
                          <option value="">--- Select Your role ---</option>
                          @foreach ($roles as $role)
                              <option value="{{ $role->id }}">{{ $role->name }}</option>
                          @endforeach
                      </select>
                      @error('role')
                        {{ $message }}
                      @enderror
                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword1">Password</label>
                      <input type="password" name="password" value="{{ old('password') }}" class="form-control" id="exampleInputPassword1" placeholder="Password">
                      @error('password')
                        {{ $message }}        
                      @enderror
                    </div>
                    <div class="form-group mb-0">
                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="terms" class="custom-control-input" id="exampleCheck1">
                        <label class="custom-control-label" for="exampleCheck1">I agree to the <a href="#">terms of service</a>.</label>
                      </div>
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