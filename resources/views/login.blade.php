<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="{{ asset('assets/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/dist/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/dist/css/style.css') }}" rel="stylesheet">
    <style> 
    .form-cover{
      background-image:url("{{ asset('/img/photo1.png') }}");
      background-size: cover; 
      background-position: center; 
    }
    </style>
    <title>Login For Admin</title>
  </head>
  <body>

    <section class="form-01-main">
      <div class="form-cover">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="form-sub-main">
              <div class="_main_head_as">
                <a href="#">
                  <img src="{{ asset('assets/dist/img/vector.png') }}">
                </a>
              </div>
              <form action="{{ route('Auth') }}" enctype="multipart/form-data" method="post" id="quickForm">
                @csrf
              <div class="form-group">
                  <input id="email" name="email" class="form-control _ge_de_ol" type="text" placeholder="Enter Email" required="" aria-required="true">
              </div>

              <div class="form-group">                                              
                <input id="password" type="password" class="form-control" name="password" placeholder="********" required="required">
                <i toggle="#password" class="fa fa-fw fa-eye toggle-password field-icon"></i>
              </div>

              <div class="form-group">
                <div class="check_box_main">
                  <a href="#" class="pas-text">Forgot Password</a>
                </div>
              </div>

              <div class="form-group" type="submit">
                <div class="btn_uy">
                  <button type="submit"
                  class="btn btn-primary mb-3">Submit</button>
                </div>
              </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      </div>
    </section>
  </body>
</html>