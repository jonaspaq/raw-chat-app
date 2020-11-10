@extends('layouts.chat')

@section('title')
Home
@endsection

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-icons/3.0.1/iconfont/material-icons.min.css">
@endsection

@section('content')
<div id="main"></div>
@endsection

@section('scripts')
<script src=" {{ asset('js/app.js') }} "></script>
@endsection
