@extends('layouts.app')

@section('title', 'Blog Post')

@section('content')
    {{-- @each('posts.partials.post', $posts, 'post') --}}
    <div class="row">
        <div class="col-8">
            @forelse ($posts as $key => $post)
                @include('posts.partials.post',[])
            @empty
                No Posts found!
            @endforelse
        </div>
        <div class="col-4">
            @include('posts._activity')
        </div>
    </div>
@endsection