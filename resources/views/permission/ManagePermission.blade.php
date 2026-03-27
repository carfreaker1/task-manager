@extends('layouts.masterlayout')

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1><b>{{ @$role->name }}</b></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Permissions</a></li>
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
                  <h3 class="card-title">Permissions Table</h3>
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
                  @elseif(session('success'))
                   {{-- <p class="alert alert-success text-center alert-dismissible">{{ session('delete') }}</p> --}}
                    <script>
                      $(document).ready(function() {
                        $(document).Toasts('create', {
                          title: 'Permissions',
                          body: 'Assigned successfully!'
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
                    <div class="form-group">

                        <div class="form-check mb-2">
                            <input type="checkbox" id="select_all">
                            <label for="select_all"><b>Select All</b></label>
                        </div>
                        <label><b>Assign Permissions</b></label>
                        
                        <form action="{{ route('storeuserpermission') }}" method="POST">
                          @csrf
                        <div class="row">
                          <input type="hidden" name="role_id" value="{{ encrypt($role->id) }}">

                            @foreach ($permissions as $permission)
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-check">
                                        <input class="form-check-input select-permission permission-checkbox"
                                               type="checkbox"
                                               name="permissions[]"
                                               value="{{ $permission->id  }}"
                                               id="perm_{{ $permission->id }}" {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="perm_{{ $permission->id }}">
                                            {{ $permission->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button  type="submit" class="btn btn-primary mt-4" id="">Assign</button>
                        </form>
                    
                    </div>
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
@push('scripts')
<script>
    $(document).ready(function(){
        $("#select_all").click(function(){
            $(".permission-checkbox").prop('checked', this.checked);
        });
    });
    </script>
@endpush
@endsection