@extends('layouts.app')

@section('title', 'Blog Post')

@section('content')
    {{-- @each('posts.partials.post', $posts, 'post') --}}

    @forelse ($posts as $key => $post)
        @include('posts.partials.post',[])
    @empty
        No Posts found!
    @endforelse
@endsection