<div class="container">
    <div class="row">
        <x-card>
            @slot('title')
                Most Commented
            @endslot
            @slot('subtitle')
                What people are currently talking about
            @endslot
            @slot('items')
                @foreach ($mostCommented as $post)
                    <li class="list-group-item">
                        <a href="{{ route('posts.show', ['post' => $post->id]) }}">
                            {{ $post->title }}
                        </a>
                    </li>
                @endforeach
            @endslot
        </x-card>
    </div>

    <div class="row mt-4">
        <x-card>
            @slot('title')
                Most Active
            @endslot
            @slot('subtitle')
                Users with most posts written
            @endslot
            @slot('items', collect($mostActive)->pluck('name'))
        </x-card>
    </div>

    <div class="row mt-4">
        <x-card>
            @slot('title')
                Most Active Last Month
            @endslot
            @slot('subtitle')
                Users with most posts written in the Last Month
            @endslot
            @slot('items', collect($mostActiveLastMonth)->pluck('name'))
        </x-card>
    </div>
</div>