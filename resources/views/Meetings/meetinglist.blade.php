@extends('layouts.masterlayout')

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Meeting List</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Meeting</a></li>
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
                  <h3 class="card-title">Meeting Table</h3>
                </div>
                  @if (session('update'))
                    {{-- <p class="alert alert-  text-center alert-dismissible">{{ session('update') }}</p> --}}
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
                          title: 'Project',
                          body: 'Project Deleted Succesfully'
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
                  <table id="example1" class="table table-bordered table-striped project-table">
                    <thead>
                    <tr>
                      <th>Sr.NO</th>
                      <th>Meeting ID</th>
                      <th>Link</th>
                      <th>Note</th>
                      <th>Time</th>
                      <th>Edit Time</th>
                      <th>Delete Meeting</th>
                    </tr>
                    </thead>
                    <tbody>
                      
                        @foreach ($taskMeetings as $taskMeeting)
                            <tr>
                              <td>{{ $loop->iteration }} </td>
                              <td>{{ $taskMeeting->meeting_no ?? '' }}</td>
                              <td>Join now:- <a target="_blank" href="{{ $taskMeeting->meet_link ?? '' }}"><img src="{{ asset('img\gmeet_image.png') }}" alt=""></a></td>
                              <td>{{ $taskMeeting->meeting_message ?? '' }}</td>
                              <td>{{ $taskMeeting->start_time ? \Carbon\Carbon::parse($taskMeeting->start_time)->format('d-m-Y H:i:s') : '' }}</td>
                              <td><a href="{{ url('/edit/task/') . '/' . encrypt($taskMeeting->id) }}"><button type="button" class="btn btn-primary btn-sm">Edit task</button></a></td>
                              <td><a href="{{ url('/delete/task/') . '/' . encrypt($taskMeeting->id) }}"><button type="button" class="btn btn-danger btn-sm">Delete task</button></a></td>
                            </tr>
                        @endforeach
                    </tbody>
                  </table>
                  {{-- Model  Start From Here --}}
                  <p><b style="color: red">Please First Authenticated by the google <u><a href="{{url('/google-auth')}}">Link</a></u></b></p>
                  <button type="button" class="btn btn-primary" data-target="#modal-lg" data-toggle="modal">Create Meetings</button>
                    <div class="modal fade"  id="modal-lg" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h4 class="modal-title">Assign Your Project</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <div class="modal-body" >
                              <form action="{{route('schedulegooglemeetings')}}" enctype="multipart/form-data" method="post" id="quickForm" >
                                @csrf
                                <p id="taks_id" ></p>
                                <div class="form-group">
                                    <label>Select Your Employee</label>
                                    <select name="assigned_user[]" id="assigned_user" multiple="multiple" class="select2" data-placeholder="Select a Team Member" style="width: 100%;" required>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id}}">{{ $employee->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('assigned_user')
                                      {{ $message }}
                                    @enderror
                                </div>
                                <div class="form-group"  id="message_field" style="">
                                  <label>Messsage for meeting</label>
                                  <textarea class="form-control" rows="3" name="meeting_message" placeholder="Enter Message Here" style="height: 116px;"></textarea>
                                </div>
                                <div  class="form-group" id="datetime_field">
                                  <label>Set Time for meeting</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                          <label>Start Time</label>
                                            <input type="datetime-local" name="start" class="form-control" placeholder="Start Time">
                                        </div>
                                        <div class="col-md-6">
                                          <label >End Time</label>
                                            <input type="datetime-local" name="end" class="form-control" placeholder="End Time">
                                        </div>
                                    </div>
                                </div>
                                 <button type="submit" class="btn btn-primary">Save changes</button>
                              </form>
                            </div>
                            <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            
                            </div>
                        </div>
                        <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    {{-- Modal Ends Here --}}
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
    $("#meeting_user").change(function(){
        if($(this).val() == "1"){
            $("#message_field").show();
            $("#datetime_field").show();
        }else{
            $("#message_field").hide();
            $("#datetime_field").hide();
        }
    });
});
</script>
@endpush
@endsection