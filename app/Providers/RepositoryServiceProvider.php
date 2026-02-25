<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\ExerciseRepositoryInterface;
use App\Repositories\Eloquent\ExerciseRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ExerciseRepositoryInterface::class, ExerciseRepository::class);
    }

    public function boot(): void
    {
        //
    }
}