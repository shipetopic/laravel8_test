@extends('layout')

@section('content')
    <div class="row">
        <div class="col-4">
            <img src="{{ $user->image ? $user->image->url() : '' }}" alt="" class="img-thumbnail avatar">
        </div>

        <div class="col-8">
            <h3>{{ $user->name }}</h3>

            <x-comment-Form :route=" route('users.comments.store', ['user' => $user->id]) "></x-comment-Form>

            <x-comment-List :comments="$user->commentsOn"></x-comment-List>
        </div>
    </div>
@endsection