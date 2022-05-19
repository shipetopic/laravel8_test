<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Comment;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // # Add 1 specific user
        // \App\Models\User::factory(1)->create(
        //     [
        //         'name' => 'John Doe',
        //         'email' => 'john@laravel.tesz',
        //         'email_verified_at' => now(),
        //         'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        //         'remember_token' => Str::random(10),
        //     ]
        // );

        # Add 1 specific user - but using 'state'
        $doe = \App\Models\User::factory()->newJohnDoeUser()->create();

        # Add 10 random users
        $else = \App\Models\User::factory(20)->create();

        $users = $else->concat([$doe]);

        $posts = BlogPost::factory(50)->make()->each(function($post) use ($users){
            $post->user_id = $users->random()->id;
            $post->save();
        });

        $comments = Comment::factory(150)->make()->each(function($comment) use ($posts){
            $comment->blog_post_id = $posts->random()->id;
            $comment->save();
        });
    }
}
