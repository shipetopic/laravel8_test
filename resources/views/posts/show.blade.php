@extends('layouts.app')

@section('title', $post->title)

@section('content')
<div class="row">
    <div class="col-8">
        <h1>
            {{ $post->title }}
            
            <x-badge type="primary" show="{{ (now()->diffInMinutes($post->created_at) < 30) }}">
                Brand new Post!
            </x-badge>
        </h1>
        <p>{{ $post->content }}</p>

        <img src="{{ $post->image->url() }}"/>

        {{-- <p>Added {{ $post->created_at->diffForHumans() }}</p> --}}
        <x-updated :date='$post->created_at' :name='$post->user->name'>
        </x-updated>

        <x-updated :date='$post->updated_at' :name='$post->user->name'>
            Updated
        </x-updated>

        <x-tags :tags='$post->tags'></x-tags>

        <p>Currently read by {{ $counter }} people</p>
        
        <h4>Comments</h4>

        @include('comments._form')

        @forelse ($post->comments as $comment)
            <p>
                {{ $comment->content }}
            </p>
            <p class="text-muted">
                {{-- added {{ $comment->created_at->diffForHumans() }} --}}
                <x-updated :date='$comment->created_at' :name='$comment->user->name'>
                </x-updated>
            </p>
        @empty
            <p>No comments yet!</p>
        @endforelse
    </div>
    <div class="col-4">
        @include('posts._activity')
    </div>
@endsection