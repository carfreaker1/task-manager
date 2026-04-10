@extends('layouts.masterlayout')

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Permission List</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Permission</a></li>
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
                  <h3 class="card-title">Permission Table</h3>
                </div>
                  @if (session('update'))
                    {{-- <p class="alert alert-success text-center alert-dismissible">{{ session('update') }}</p> --}}
                    <script>
                      $(document).ready(function() {
                        $(document).Toasts('create', {
                          title: 'Permission',
                          body: 'Updated Succesfully'
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
                          title: 'Permission',
                          body: 'Permission Deleted Succesfully'
                      })
                      $(".toast").addClass('bg-danger')
                      });    
                    </script>
                  @endif
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped project-table">
                    <thead>
                    <tr>
                      <th>Sr.NO</th>
                      <th>Name</th>
                      <th>Slug</th>
                      <th>Edit</th>
                      <th>Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($permissions as $permission)
                            <tr>
                              <td>{{ $loop->iteration }}</td>
                              <td>{{ $permission->name ?? '' }}</td>
                              <td>{{ $permission->slug ?? '' }}</td>
                              <td><a href="{{ url('/edit/permission/') . '/' . encrypt($permission->id) }}"><button type="button" class="btn btn-primary btn-sm">Edit </button></a></td>
                              <td><a href="{{ url('/delete/permission/') . '/' . encrypt($permission->id) }}"><button type="button" class="btn btn-danger btn-sm">Delete</button></a></td>
                            </tr>
                        @endforeach
                    </tbody>
                  </table>
                  {{-- Model  Start From Here --}}
                  
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