<?php

namespace App\Providers;

use App\View\Components\Badge;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Blade::aliasComponent('badge', Badge::class);
        Blade::aliasComponent('components.badge', 'badge');
    }
}
