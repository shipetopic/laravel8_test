<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Comment;
use Illuminate\Database\Seeder;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts = \App\Models\BlogPost::all();
        $users = \App\Models\User::all();

        if ($posts->count() === 0 || $users->count() === 0){
            $this->command->info('There are no blog posts or users, so no comments will be added');
            return;
        }

        $commentCount = (int) $this->command->ask('How many comments would you like?', 150);

        $commentsOnPosts = Comment::factory($commentCount)->make()->each(function($comment) use ($posts, $users){
            $comment->commentable_id = $posts->random()->id;
            $comment->commentable_type = 'App\Models\BlogPost';
            $comment->user_id = $users->random()->id;
            $comment->save();
        });

        $commentsOnUsers = Comment::factory($commentCount)->make()->each(function($comment) use ($users){
            $comment->commentable_id = $users->random()->id;
            $comment->commentable_type = 'App\Models\User';
            $comment->user_id = $users->random()->id;
            $comment->save();
        });
    }
}
