@extends('layout')

@section('title', 'Content Page')

@section('content')
    <h1>Contact Page</h1>
    <p>Hello this is contact!</p>
    
    @can('home.secret')
        <a href="{{ route('home.secret') }}">Go to Special contact details</a>
    @endcan
@endsection