@extends('layouts.guest')

@section('title')
Login
@endsection

@section('content')
<div class="login">
    <h1>Login</h1>
    <form method="post" action="/login">
        @csrf
        <p class="error-mseg">{{ Session::get('error')}}</p>
        <input type="text" name="email" value="asd" placeholder="Username" required="required" />
        <input type="password" name="password" value="123456" placeholder="Password" required="required" />
        <button type="submit" class="btn btn-primary btn-block btn-large">Let me in.</button>
    </form>

    <p class="dont-have">Don't have an account? <a href="/register">Register here</a> </p>
</div>
@endsection
