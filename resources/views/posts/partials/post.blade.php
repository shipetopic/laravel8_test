{{-- @break($key == 2) --}}
{{-- @continue($key == 1) --}}

<h3>
    @if ($post->trashed())
        <del>
    @endif
    <a class="{{ $post->trashed() ? 'text-muted' : '' }}" href="{{ route('posts.show', ['post' => $post->id]) }}">{{ $post['title'] }}</a>
    @if ($post->trashed())
        </del>
    @endif
</h3>

<x-updated :date='$post->created_at' :name='$post->user->name'>
</x-updated>

@if($post->comments_count)
    <p>{{ $post->comments_count }} comments</p>
@else
    <p>No comments yet!</p>
@endif

{{-- <div>{{ $key }}.{{ $post['title'] }}</div> --}}
<div class="mb-3">
    @can('update', $post)
        <a href="{{ route('posts.edit', ['post' => $post->id]) }}" class="btn btn-primary">EDIT</a>
    @endcan

    {{-- @cannot('delete', $post)
        <p>You can't delete this post!</p>
    @endcannot --}}

    @if (!$post->trashed())
        @can('delete', $post)
            <form class="d-inline" action="{{ route('posts.destroy', ['post' => $post->id]) }}" method="POST">
                @csrf
                @method('DELETE')
                <input type="submit" value="Delete!" class="btn btn-primary">
            </form>
        @endcan
    @endif
</div>