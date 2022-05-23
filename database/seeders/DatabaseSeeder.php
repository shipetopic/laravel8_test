<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Comment;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
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
        Cache::tags('blog-post')->flush();

        if ($this->command->confirm('Do you want to refrtesh database?'/*, true*/)){ // true - for default value 'yes'
            $this->command->call('migrate:refresh');
            $this->command->info('Database was regreshed');
        }

        # Calls specific seeders in order (specified in array)
        $this->call([
            UsersTableSeeder::class,
            BlogPostsTableSeeder::class,
            CommentsTableSeeder::class,
            TagsTableSeeder::class,
            BlogPostTagTableSeeder::class
        ]);
    }
}
