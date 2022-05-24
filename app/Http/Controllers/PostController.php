<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePost;
use App\Models\BlogPost;
use App\Models\Image;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')
            ->only(['create', 'store', 'edit', 'update', 'destroy']) // methods that Authentication will be required for
        ;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $mostCommented = Cache::tags(['blog-post'])->remember('blog-post-commented', 60, function (){
        //     return BlogPost::mostCommented()->take(5)->get();
        // });
        
        // $mostActive = Cache::remember('users-most-active', 60, function (){ // now()->addSeconds(10)
        //     return User::withMostBlogPosts()->take(5)->get();
        // });

        // $mostActiveLastMonth = Cache::remember('users-most-active-last-month', 60, function (){
        //     return User::withMostBlogPostsLastMonth()->take(5)->get();
        // });



        // DB::connection()->enableQueryLog();

        // $posts = BlogPost::with('comments')->get(); // eager loading
        // $posts = BlogPost::all(); // lazy loading
        
        // foreach($posts as $post){
        //     foreach($post->comments as $comment){
        //         echo $comment->content;
        //     }
        // }

        // dd(DB::getQueryLog());


        
        // return view('posts.index', ['posts' => BlogPost::all()]);

        // comments_count
        return view(
            'posts.index', 
            [
                'posts' => BlogPost::latestWithRelations()->get(),
                // 'mostCommented' => $mostCommented,
                // 'mostActive' => $mostActive,
                // 'mostActiveLastMonth' => $mostActiveLastMonth,
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $this->authorize('posts.create');
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    public function store(StorePost $request)
    {
        // dd($request);

        // $request->validate([
        //     'title' => 'bail|required|min:5|max:100',
        //     'content' => 'required|min:10',
        // ]);
        
        // $post = new BlogPost();
        // $post->title = $request->input('title');
        // $post->content = $request->input('content');
        // $post->save();

        // return redirect()->route('posts.show', ['post' => $post->id]);

        $validatedData = $request->validated();
        $validatedData['user_id'] = $request->user()->id;

        // $post = new BlogPost();
        // $post->title = $validated['title'];
        // $post->content = $validated['content'];        
        // $post->save();

        $post = BlogPost::create($validatedData); // fillout and try to save
        // $post2 = BlogPost::make(); // just fillout
        // $post2->save();

        if ($request->hasFile('thumbnail')){
            $path = $request->file('thumbnail')->store('thumbnails');

            $post->image()->save(
                Image::create(['path' => $path])
            );
        }

        // dump($request->hasFile('thumbnail'));        
        // $hasFile = $request->hasFile('thumbnail');
        // dump($hasFile);
        // if ($hasFile){            
        //     $file = $request->file('thumbnail');
        //     dump($file);
        //     dump($file->getClientMimeType());
        //     dump($file->getClientOriginalExtension());

        //     // dump($file->store('thumbnails'));
        //     // dump(Storage::disk('public')->put('thumbnails', $file));

        //     $name1 = dump($file->storeAs('thumbnails', $post->id .'.'. $file->guessExtension()));
        //     $name2 = dump(Storage::disk('local')->putFileAs('thumbnails', $file, $post->id .'.'. $file->guessExtension()));

        //     dump(Storage::url($name1));
        //     dump(Storage::disk('local')->url($name2));
        // }
        // die;

        $request->session()->flash('status', 'The blog post was created!');

        return redirect()->route('posts.show', ['post' => $post->id]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // abort_if(!isset($this->posts[$id]), 404);
    
        // return view('posts.show', ['post' => BlogPost::findOrFail($id)]);

        $blogPost = Cache::tags(['blog-post'])->remember("blog-post-{$id}", 60, function () use ($id){
            return BlogPost::with('comments', 'tags', 'user', 'comments.user')
                // ->with('tags')
                // ->with('user')
                // ->with('comments.user') // nested relationship
                ->findOrFail($id);
        });


        if (true){
            $sessionId = session()->getId();
            $usersKey = "blog-post-{$id}-users";
            $users = Cache::tags(['blog-post'])->get($usersKey, []);
            $now = now();
            $users[$sessionId] = $now; // add/update current user & time
            $currentUsers = [];
     
            foreach($users as $session => $lastVisit) {
                if ($now->diffInMinutes($lastVisit) < 1) {
                    $currentUsers[$session] = $lastVisit; 
                }
            }
     
            Cache::put($usersKey, $currentUsers);
            $counter = count($currentUsers);
        } else {
            # alternative way
            $sessionId = session()->getId();
            $counterKey = "blog-post-{$id}-counter";
            $usersKey = "blog-post-{$id}-users";
    
            $users = Cache::tags(['blog-post'])->get($usersKey, []);
            $usersUpdate = [];
            $difference = 0;
            $now = now();
    
            foreach ($users as $session => $lastVisit) {
                if ($now->diffInMinutes($lastVisit) >= 1){
                    $difference--;
                } else {
                    $userUpdate[$session] = $lastVisit;
                }
            }
    
            if (!array_key_exists($sessionId, $users)
                || $now->diffInMinutes($users[$sessionId]) >= 1
            ){
                $difference++;
            }
    
            $userUpdate[$sessionId] = $now;
            Cache::tags(['blog-post'])->forever($usersKey, $usersUpdate);
            if (Cache::tags(['blog-post'])->has($counterKey)){
                Cache::tags(['blog-post'])->forever($counterKey, 1);
            } else {
                Cache::tags(['blog-post'])->increment($counterKey, $difference);
            }
    
            $counter = Cache::tags(['blog-post'])->get($counterKey);
        }


        return view('posts.show', [
            'post' => $blogPost,
            'counter' => $counter
        ]);

        // return view('posts.show', ['post' => BlogPost::with(['comments' => function ($query){
        //     return $query->latest();
        // }])->findOrFail($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = BlogPost::findOrFail($id);

        // $this->authorize('update', $post);
        $this->authorize($post);

        // if (Gate::denies('posts.update', $post)){ // user object passed automatically by laravel 
        //     abort(403, "You can't edit this blog post!");
        // }; 

        return view('posts.edit', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StorePost $request, $id)
    {
        $post = BlogPost::findOrFail($id);

        // $this->authorize('update', $post);
        $this->authorize($post);
        // if (Gate::denies('posts.update', $post)){ // user object passed automatically by laravel 
        //     abort(403, "You can't edit this blog post!");
        // }; 

        $validated = $request->validated();
        $post->fill($validated);
        $post->save();

        $request->session()->flash('status', 'Blog post was updated!');

        return redirect()->route('posts.show', ['post' => $post->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = BlogPost::findOrFail($id);

        // $this->authorize('delete', $post);
        $this->authorize($post);
        // if (Gate::denies('posts.delete', $post)){ // user object passed automatically by laravel 
        //     abort(403, "You can't delete this blog post!");
        // }; 

        $post->delete();

        session()->flash('status', 'Blog post was deleted!');

        return redirect()->route('posts.index');
    }
}
