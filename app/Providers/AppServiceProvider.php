<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    // public function boot(): void
    // {
    //     //
    //     if ($this->app->isLocal()) {
    //         DB::connection()->enableQueryLog();
    //     }
    // }
//     public function boot()
// {
//     $user = auth()->user();
    
//     // Retrieve the authenticated user
//     View::share('user', $user);
// }

public function boot()
{
    View::composer('*', function ($view) {
        $user = auth()->user();
        $layoutFile = 'layouts.layout'; // Default layout

        if ($user) {
            if ($user->type === 'admin') {
                $layoutFile = 'layouts.layoutAdmin';
            } elseif ($user->type === 'service_ced') {
                $layoutFile = 'layouts.layout';
            } elseif ($user->type === 'directeur') {
                $layoutFile = 'layouts.layoutDirecteur';
            }
        }

        $view->with('layoutFile', $layoutFile);
    });
}
}
