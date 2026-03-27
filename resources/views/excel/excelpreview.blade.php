@extends('layouts.masterlayout')

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Field List</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Fields</li>
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
                <h3 class="card-title">Employee Field List</h3>
              </div>
              @if (session('success'))
                  {{-- <p class="alert alert-success text-center alert-dismissible">{{ session('success') }}</p> --}}
                  <script>
                    $(document).ready(function() {
                      $(document).Toasts('create', {
                        title: 'Department',
                        body: 'Department Created Succesfully'
                    })
                    $(".toast").addClass('bg-success')
                    });    
                  </script>
              @elseif (session('update'))
                  {{-- <p class="alert alert-success text-center alert-dismissible">{{ session('update') }}</p> --}}
                  <script>
                    $(document).ready(function() {
                      $(document).Toasts('create', {
                        title: 'Department',
                        body: 'Department Updated Succesfully'
                    })
                    $(".toast").addClass('bg-success')
                    });    
                  </script>
              @elseif (session('delete'))
                  {{-- <p class="alert alert-success text-center alert-dismissible">{{ session('delete') }}</p> --}}
                  <script>
                    $(document).ready(function() {
                      $(document).Toasts('create', {
                        title: 'Department',
                        body: 'Department Deleted Succesfully'
                    })
                    $(".toast").addClass('bg-danger')
                    });    
                  </script>
              @elseif (session('successdesig'))
                  {{-- <p class="alert alert-success text-center alert-dismissible">{{ session('successdesig') }}</p> --}}
                  <script>
                    $(document).ready(function() {
                      $(document).Toasts('create', {
                        title: 'Designation',
                        body: 'Designation Created Succesfully'
                    })
                    $(".toast").addClass('bg-success')
                    });    
                  </script>
              @elseif (session('updatedesig'))
                  {{-- <p class="alert alert-success text-center alert-dismissible">{{ session('updatedesig') }}</p> --}}
                  <script>
                    $(document).ready(function() {
                      $(document).Toasts('create', {
                        title: 'Designation',
                        body: 'Designation Created Succesfully'
                    })
                    $(".toast").addClass('bg-success')
                    });    
                  </script>
              @elseif (session('deletedesig'))
                  {{-- <p class="alert alert-success text-center alert-dismissible">{{ session('deletedesig') }}</p> --}}
                  <script>
                    $(document).ready(function() {
                      $(document).Toasts('create', {
                        title: 'Designation',
                        body: 'Designation Deleted Succesfully'
                    })
                    $(".toast").addClass('bg-danger')
                    });    
                  </script>
              @endif
              <!-- /.card-header -->
              <div class="card-body">
                  <form action="{{ route('export') }}" method="POST">
                      @csrf
                      <table id="example1" class="table table-bordered table-striped">
                          <thead>
                              <tr>
                                  @foreach($data[0] as $index => $heading)
                                  <th class="sorting_asc_disabled sorting_desc_disabled sorting_disabled">
                                      <input type="text" name="headings[{{ $index }}]" placeholder="Enter heading" value="{{ old('headings.' . $index) }}" class="form-control">
                                  </th>
                                  @endforeach
                              </tr>
                          </thead>
                          <tbody>
                              @foreach($data as $rowIndex => $row)
                              @if ($rowIndex > 0) <!-- Skip the first row (header) -->
                              <tr>
                                  @foreach($row as $cell)
                                  <td sorting_>{{ $cell }}</td>
                                  @endforeach
                              </tr>
                              @endif
                              @endforeach
                          </tbody>
                      </table>
                      <button type="submit">Export with Inputs</button>
                  </form>
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