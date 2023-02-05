<?php

namespace App\Providers;

use App\Repositories\Articles\EloquentSearchRepository;
use App\Repositories\SearchRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(SearchRepositoryInterface::class, EloquentSearchRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
