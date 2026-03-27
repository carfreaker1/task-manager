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
                        title: 'Role',
                        body: 'Role Deleted Succesfully'
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
                      <th>Edit User</th>
                      <th>Delete User</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $roles)
                            <tr>
                              <td>{{ $loop->iteration }}</td>
                              <td>{{ $roles->name ?? '' }}</td>
                              <td><a href="{{ url('/edit/role/') . '/' . encrypt($roles->id)}}"><button type="button" class="btn btn-primary btn-sm">Edit Role</button></a></td>
                              <td><a href="{{ url('/delete/role/') . '/' . encrypt($roles->id)}}"><button type="button" class="btn btn-danger btn-sm">Delete Role</button></a></td>
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