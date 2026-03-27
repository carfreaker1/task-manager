<h1>Forget Password Email</h1>
    {{-- @dd($token); --}}
You can reset password from bellow link:
<a href="{{ route('passwordreset', $token) }}">Reset Password</a>