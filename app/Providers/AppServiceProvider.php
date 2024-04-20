<?php

namespace App\Providers;

use App\Models\Post;
use App\Observers\PostObserver;
use App\Repository\Interfaces\PostRepositoryInterface;
use App\Repository\Interfaces\WebsiteSubscriberRepositoryInterface;
use App\Repository\PostRepository;
use App\Repository\WebsiteSubscriberRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PostRepositoryInterface::class, PostRepository::class);
        $this->app->bind(WebsiteSubscriberRepositoryInterface::class,WebsiteSubscriberRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Post::observe(PostObserver::class);
    }
}
