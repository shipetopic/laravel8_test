@extends('layouts.app')

@section('title', $post->title)

@section('content')
<div class="row">
    <div class="col-8">
        @if ($post->image)
            <div style="
                background-image: url('{{ $post->image->url() }}'); 
                min-height: 500px; 
                color: white; 
                text-align: center; 
                background-attachment: fixed;">
                <h1 style="padding-top: 100px; text-shadow: 1px 2px #000;">
        @else
            <h1>    
        @endif
        


            {{ $post->title }}
            
            <x-badge type="primary" show="{{ (now()->diffInMinutes($post->created_at) < 30) }}">
                Brand new Post!
            </x-badge>

        @if ($post->image)
                </h1>
            </div>
        @else
            </h1>    
        @endif

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

        <x-comment-Form :route=" route('posts.comments.store', ['post' => $post->id]) "></x-comment-Form>

        <x-comment-List :comments="$post->comments"></x-comment-List>
    </div>
    <div class="col-4">
        @include('posts._activity')
    </div>
@endsection