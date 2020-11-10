@extends('layouts.guest')

@section('title')
Register
@endsection

@section('content')
<div class="login">
    <h1>Register</h1>
    <form method="post" action="/register">
        @csrf
        @error ('email')
            <p class="error-mseg"> {{ $message }} </p>
        @enderror
        <input type="text" name="email" placeholder="Username or Email" required="required" />

        <input type="text" name="name" placeholder="Name" required="required" />
        @error ('password')
            <p class="error-mseg"> {{ $message }} </p>
        @enderror
        <input type="password" name="password" placeholder="Password" value="123456" required="required" />
        @error ('password_confirmation')
            <p class="error-mseg"> {{ $message }} </p>
        @enderror
        <input type="password" name="password_confirmation" placeholder="Confirm password" value="123456" required="required" />
        <button type="submit" class="btn btn-primary btn-block btn-large">Let's go</button>
    </form>

    <p class="dont-have">Go to <a href="/">Login page</a> </p>
</div>
@endsection





