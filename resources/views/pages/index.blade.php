@extends('layouts.app')

@section('content')
    <div class="jumbotron text-center">
        <h1>{{$title}}</h1>
        <p>Start posting on our blog today </p>
        <p><a href="{{ route('login') }}" class="btn btn-primary btn-lg" role="button">LogIn</a> <a href="{{ route('register') }}" class="btn btn-success btn-lg" role="button">Register</a></p>
    </div>
    
@endsection
