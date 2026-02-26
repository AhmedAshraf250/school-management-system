<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Contracts\TeacherRepositoryInterface::class,
            \App\Repositories\Eloquent\TeacherEloRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\StudentRepositoryInterface::class,
            \App\Repositories\Eloquent\StudentEloRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\StaticDataRepositoryInterface::class,
            \App\Repositories\Eloquent\StaticDataEloRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
