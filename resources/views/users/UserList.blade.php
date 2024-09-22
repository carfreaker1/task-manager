@extends('layouts.masterlayout')

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>User List</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">User List</li>
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
                  <h3 class="card-title">Users</h3>
                </div>
                @if (session('update'))
                {{-- <p class="alert alert-success text-center alert-dismissible">{{ session('update') }}</p> --}}
                  <script>
                    $(document).ready(function() {
                      $(document).Toasts('create', {
                        title: 'User',
                        body: 'User Created Succesfully'
                    })
                    $(".toast").addClass('bg-success')
                    });    
                  </script>
                @elseif(session('delete'))
                {{-- <p class="alert alert-success text-center alert-dismissible">{{ session('delete') }}</p> --}}
                  <script>
                    $(document).ready(function() {
                      $(document).Toasts('create', {
                        title: 'User',
                        body: 'User Deleted Succesfully'
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
                      <th>Name</th>
                      <th>E-Mail</th>
                      <th>Department</th>
                      <th>Designation</th>
                      <th>Role</th>
                      @if (Auth::user()->role_id == 1)
                        <th>Edit User</th>
                        <th>Delete User</th>
                      @endif
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                              <td>{{ $loop->iteration }}</td>
                              <td>{{ $user->name ?? '' }}</td>
                              <td>{{ $user->email ?? ''}}</td>
                              <td>{{ $user->department ? $user->department->name : 'No Department Selected' }}</td>
                              <td>{{ $user->designation ? $user->designation->designation_name : 'No Designation Selected' }}</td>
                              <td>{{ $user->role ? $user->role->name : 'No Role Selected' }}</td>
                              @if (Auth::user()->role_id == 1)
                                <td><a href="{{ url('/edit/user/') . '/' . encrypt($user->id) }}"><button type="button" class="btn btn-primary btn-sm">Edit</button></a></td>
                                <td><a href="{{ url('/delete/user/') . '/' . encrypt($user->id) }}"><button type="button" class="btn btn-danger btn-sm">Delete</button></a></td>
                              @endif
                            </tr>
                        @endforeach
                    </tbody>
                  </table>
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