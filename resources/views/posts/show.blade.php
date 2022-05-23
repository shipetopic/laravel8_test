@extends('layouts.app')

@section('title', $post->title)

@section('content')
    <h1>
        {{ $post->title }}
        
        <x-badge type="primary" show="{{ (now()->diffInMinutes($post->created_at) < 30) }}">
            Brand new Post!
        </x-badge>
    </h1>
    <p>{{ $post->content }}</p>

    {{-- <p>Added {{ $post->created_at->diffForHumans() }}</p> --}}
    <x-updated :date='$post->created_at' :name='$post->user->name'>
    </x-updated>

    <x-updated :date='$post->updated_at' :name='$post->user->name'>
        Updated
    </x-updated>

    <x-tags :tags='$post->tags'></x-tags>

    <p>Currently read by {{ $counter }} people</p>
    
    <h4>Comments</h4>

    @forelse ($post->comments as $comment)
        <p>
            {{ $comment->content }}
        </p>
        <p class="text-muted">
            {{-- added {{ $comment->created_at->diffForHumans() }} --}}
            <x-updated :date='$post->created_at'>
            </x-updated>
        </p>
    @empty
        <p>No comments yet!</p>
    @endforelse
@endsection