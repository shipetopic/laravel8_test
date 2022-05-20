<?php

namespace App\Providers;

use App\Policies\BlogPostPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\Model' => 'App\Policies\ModelPolicy',
        'App\Models\BlogPost' => 'App\Policies\BlogPostPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('home.secret', function ($user){
            return $user->is_admin;
        });

        // Gate::define('posts.update', function ($user, $post){
        //     return $user->id == $post->user_id;
        // });

        // Gate::define('posts.delete', function ($user, $post){
        //     return $user->id == $post->user_id;
        // });

        // Gate::define('posts.update', [BlogPostPolicy::class, 'update']);
        // Gate::define('posts.delete', [BlogPostPolicy::class, 'delete']);

        // Gate::resource('posts', BlogPostPolicy::class);
        // posts.create posts.update posts.delete posts.view
        // comments.create, comments.update etc.

        # 'before' gate is always called first - so it can intercept other gates
        // NOTE: this is global override for ALL models; if need specific model: use specific policy class
        Gate::before(function ($user, $ability){
            if ($user->is_admin && in_array($ability, ['update'/*, 'delete'*/])){
                return true;
            }
        });

        # 'after' gate is called AFTER and is final step to authorize action
        // Gate::after(function ($user, $ability, $result){
        //     if ($user->is_admin && in_array($ability, ['posts.update', 'posts.delete'])){
        //         return true;
        //     }
        // });
    }
}
