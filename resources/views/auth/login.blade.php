<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Smart Management | Login</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,600,700&display=fallback">
  <link rel="stylesheet" href="{{asset('assets/plugins/fontawesome-free/css/all.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/dist/css/adminlte.min.css')}}">

  <style>
    body {
      /* Dynamic Background with Overlay */
      background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), 
                  url("{{ asset('assets/images/login-background.png') }}");
      background-repeat: no-repeat;
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      font-family: 'Inter', sans-serif;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .register-box {
      width: 450px; /* Slightly wider for a modern feel */
      margin: 0 auto;
    }

    /* Glassmorphism Card Effect */
    .card {
      background: rgba(255, 255, 255, 0.15) !important;
      backdrop-filter: blur(15px);
      -webkit-backdrop-filter: blur(15px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 20px;
      box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
      overflow: hidden;
    }

    .register-card-body {
      background: transparent !important;
      color: #fff;
      padding: 2.5rem;
    }

    .register-logo a {
      color: #ffffff !important;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1px;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
      font-size: 1.5rem;
    }

    /* Modernizing Inputs */
    .form-control {
      background: rgba(255, 255, 255, 0.1) !important;
      border: 1px solid rgba(255, 255, 255, 0.3) !important;
      color: #fff !important;
      border-radius: 10px !important;
      transition: all 0.3s ease;
    }

    .form-control::placeholder {
      color: #ddd;
    }

    .form-control:focus {
      background: rgba(255, 255, 255, 0.2) !important;
      border-color: #fff !important;
      box-shadow: none;
    }

    .input-group-text {
      background: transparent !important;
      border: 1px solid rgba(255, 255, 255, 0.3) !important;
      border-left: none !important;
      color: #fff !important;
      border-radius: 0 10px 10px 0 !important;
    }

    /* Modern Buttons */
    .btn-primary {
      background: #6366f1 !important; /* Trendy Indigo color */
      border: none !important;
      border-radius: 10px !important;
      font-weight: 600;
      padding: 10px;
      transition: transform 0.2s;
    }

    .btn-primary:hover {
      background: #4f46e5 !important;
      transform: translateY(-2px);
    }

    .social-auth-links .btn {
      border-radius: 10px !important;
      margin-bottom: 10px;
    }

    /* Typography Adjustments */
    .login-box-msg {
      font-size: 1.2rem;
      font-weight: 500;
      padding-bottom: 20px;
    }

    a {
      color: #a5b4fc !important; /* Soft indigo for links */
      transition: 0.3s;
    }

    a:hover {
      color: #fff !important;
      text-decoration: none;
    }

    @media (max-width: 576px) {
      .register-box {
        width: 90%;
      }
    }
  </style>
</head>

<body class="hold-transition">

<div class="register-box">
  <div class="register-logo mb-4 text-center">
    <a href="#"><b>SMART TASK & PROJECT MANAGEMENT SYSTEM</b></a>
  </div>

  <div class="card shadow-none">
    <div class="card-body register-card-body">
      <p class="login-box-msg">Welcome Back</p>

      @if (session('succes'))
        <div class="alert alert-success bg-success border-0 text-center">{{ session('succes') }}</div>
      @endif

      <form action="{{ url('auth/login') }}" method="POST">
        @csrf
        
        <div class="mb-3">
          <div class="input-group">
            <input type="email" class="form-control" name="email" value="{{old('email')}}" placeholder="Email">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          @if ($errors->has('email'))
            <small class="text-warning font-weight-bold">{{ $errors->first('email') }}</small>
          @endif
        </div>

        <div class="mb-3">
          <div class="input-group">
            <input type="password" name="password" class="form-control" placeholder="Password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          @if ($errors->has('password'))
            <small class="text-warning font-weight-bold">{{ $errors->first('password') }}</small>
          @endif
        </div>

        <div class="row mt-4">
          <div class="col-7">
            <div class="icheck-primary">
              <input type="checkbox" id="remember" name="remember">
              <label for="remember" class="text-sm">Remember Me</label>
            </div>
          </div>
          <div class="col-5">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
        </div>
      </form>

      <div class="social-auth-links text-center mt-4">
        <p class="text-sm text-muted">OR</p>
        <a href="#" class="btn btn-block btn-outline-light text-sm">
          <i class="fab fa-google mr-2"></i> Login with Google
        </a>
      </div>

      <div class="mt-4 text-center text-sm">
        <a href="{{route('forgetpasswordview')}}" class="d-block mb-1">Forgot password?</a>
        <span>Don't have an account? </span><a href="{{ route('registeruser')}}">Register</a>
      </div>
    </div>
  </div>
</div>

<script src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/dist/js/adminlte.min.js')}}"></script>

</body>
</html>