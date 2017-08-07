<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot() {}
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //V1
        $this->app->bind('App\Repositories\V1\ExampleInterface', 'App\Repositories\V1\ExampleImplement');
        $this->app->bind('App\Repositories\V1\QueryInterface', 'App\Repositories\V1\QueryImplement');
        $this->app->bind('App\Repositories\V1\PagesInterface', 'App\Repositories\V1\PagesImplement');
        $this->app->bind('App\Repositories\V1\GlobalInterface', 'App\Repositories\V1\GlobalImplement');
        $this->app->bind('App\Repositories\V1\ChannelInterface', 'App\Repositories\V1\ChannelImplement');
        $this->app->bind('App\Repositories\V1\ArticleInterface', 'App\Repositories\V1\ArticleImplement');
        //V2
        $this->app->bind('App\Repositories\V2\ExampleInterface', 'App\Repositories\V2\ExampleImplement');
        $this->app->bind('App\Repositories\V2\ChannelInterface', 'App\Repositories\V2\ChannelImplement');
        $this->app->bind('App\Repositories\V2\BangumiInterface', 'App\Repositories\V2\BangumiImplement');

    }
}
