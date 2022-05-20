@extends('layouts.app')

@section('title', $post->title)

@section('content')
    <h1>{{ $post->title }}</h1>
    <p>{{ $post->content }}</p>
    <p>Added {{ $post->created_at->diffForHumans() }}</p>

    @if(now()->diffInMinutes($post->created_at) < 20)
        {{-- <div class="badge badge-success">New!</div> --}}
        @component('badge', ['type' => 'primary'])
            Brand new Post!
        @endcomponent
    @endif

    <h4>Comments</h4>

    @forelse ($post->comments as $comment)
        <p>
            {{ $comment->content }}
        </p>
        <p class="text-muted">
            added {{ $comment->created_at->diffForHumans() }}
        </p>
    @empty
        <p>No comments yet!</p>
    @endforelse
@endsection