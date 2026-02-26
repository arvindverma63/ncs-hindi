<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\CoachRepositoryInterface;
use App\Repositories\Contracts\InteractionRepositoryInterface;
use App\Repositories\Contracts\PageRepositoryInterface;
use App\Repositories\Contracts\SeekerRepositoryInterface;
use App\Repositories\Eloquent\EloquentCategoryRepository;
use App\Repositories\Eloquent\EloquentCoachRepository;
use App\Repositories\Eloquent\EloquentInteractionRepository;
use App\Repositories\Eloquent\EloquentSeekerRepository;
use App\Repositories\Eloquent\PageRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Repositories\Contracts\BlogCommentRepositoryInterface;
use App\Repositories\Contracts\BlogRepositoryInterface;
use App\Repositories\Contracts\CoachBlogRepositoryInterface;
use App\Repositories\Contracts\CoachDashboardRepositoryInterface;
use App\Repositories\Contracts\CoachProfileRepositoryInterface;
use App\Repositories\Contracts\ForumRepositoryInterface;
use App\Repositories\Contracts\MediaGalleryInterface;
use App\Repositories\Contracts\MessageRequestInterface;
use App\Repositories\Contracts\ProfileRepositoryInterface as ContractsProfileRepositoryInterface;
use App\Repositories\Contracts\SeekerDashboardInterface;
use App\Repositories\Contracts\SeekerProfileInterface;
use App\Repositories\Contracts\StemRepositoryInterface as ContractsStemRepositoryInterface;
use App\Repositories\Eloquent\EloquentBlogCommentRepository;
use App\Repositories\Eloquent\EloquentBlogRepository;
use App\Repositories\Eloquent\EloquentCoachBlogRepository;
use App\Repositories\Eloquent\EloquentCoachDashboardRepository;
use App\Repositories\Eloquent\EloquentCoachProfileRepository;
use App\Repositories\Eloquent\EloquentMediaGalleryRepository;
use App\Repositories\Eloquent\EloquentMessageRequestRepository;
use App\Repositories\Eloquent\EloquentSeekerDashboardRepository;
use App\Repositories\Eloquent\EloquentSeekerProfileRepository;
use App\Repositories\Eloquent\ForumRepository;
use App\Repositories\Eloquent\ProfileRepository;
use App\Repositories\Eloquent\StemRepository;
use App\Repositories\Interfaces\ProfileRepositoryInterface;
use App\Repositories\Interfaces\StemRepositoryInterface;
use App\View\Components\WebAppLayout;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PageRepositoryInterface::class, PageRepository::class);
        $this->app->bind(CoachRepositoryInterface::class, EloquentCoachRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, EloquentCategoryRepository::class);
        $this->app->bind(SeekerRepositoryInterface::class, EloquentSeekerRepository::class);
        $this->app->bind(InteractionRepositoryInterface::class, EloquentInteractionRepository::class);
        $this->app->bind(BlogRepositoryInterface::class, EloquentBlogRepository::class);
        $this->app->bind(BlogCommentRepositoryInterface::class, EloquentBlogCommentRepository::class);
        $this->app->bind(CoachDashboardRepositoryInterface::class, EloquentCoachDashboardRepository::class);
        $this->app->bind(CoachBlogRepositoryInterface::class, EloquentCoachBlogRepository::class);
        $this->app->bind(CoachProfileRepositoryInterface::class, EloquentCoachProfileRepository::class);
        $this->app->bind(MediaGalleryInterface::class, EloquentMediaGalleryRepository::class);
        $this->app->bind(MessageRequestInterface::class, EloquentMessageRequestRepository::class);
        $this->app->bind(SeekerDashboardInterface::class, EloquentSeekerDashboardRepository::class);
        $this->app->bind(SeekerProfileInterface::class, EloquentSeekerProfileRepository::class);
        $this->app->bind(ForumRepositoryInterface::class, ForumRepository::class);
        $this->app->bind(ContractsStemRepositoryInterface::class, StemRepository::class);
        $this->app->bind(ContractsProfileRepositoryInterface::class, ProfileRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        Gate::before(function ($user, $ability) {
            if ($user->user_type === 0) {
                return true;
            }
        });

        Blade::component('webapp-layout', WebAppLayout::class);
    }
}
