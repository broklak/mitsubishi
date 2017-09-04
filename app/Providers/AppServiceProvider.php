<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        View::composer('*', function($view){
            $this->getViewName($view);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    protected function getViewName($view) {
        View::share('view_name', $view->getName());
        View::share('js_name', 'sites/js.'.$view->getName());
        View::share('user', Auth::user());
        View::share('css_name', 'sites/css.'.$view->getName());
    }
}
