<?php

namespace App\Providers;

use App\Models\PlannerCategory;
use App\Models\SiteConfig;
use App\Models\User;
use App\Observers\PlannerCategoryObserver;
use App\Observers\UserObserver;
use App\Policies\ArticlePolicy;
use App\Policies\OfferCategoryPolicy;
use App\Policies\OfferPolicy;
use App\Policies\PlannerCategoryPolicy;
use App\Policies\PlannerSectionPolicy;
use App\Policies\ProductCategoryPolicy;
use App\Policies\ProductPolicy;
use App\Policies\ProductReviewPolicy;
use App\Policies\SettingsPolicy;
use App\Policies\TopicPolicy;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
    public function boot(): void
    {
        // Only force HTTPS in production environment
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
        JsonResource::withoutWrapping();
        User::observe(UserObserver::class);
        PlannerCategory::observe(PlannerCategoryObserver::class);

        Gate::define('updateAuthor', [ArticlePolicy::class, 'updateAuthor']);
        Gate::define('createOfferCategory', [OfferCategoryPolicy::class, 'create']);
        Gate::define('viewOfferCategories', [OfferCategoryPolicy::class, 'viewAll']);
        Gate::define('createOffer', [OfferPolicy::class, 'create']);
        Gate::define('viewOffers', [OfferPolicy::class, 'viewAll']);
        Gate::define('viewSettings', [SettingsPolicy::class, 'viewSettings']);
        Gate::define('editMenu', [SettingsPolicy::class, 'editMenu']);
        Gate::define('createTopic', [TopicPolicy::class, 'create']);
        Gate::define('viewTopics', [TopicPolicy::class, 'viewAll']);
        Gate::define('createPlannerCategory', [PlannerCategoryPolicy::class, 'create']);
        Gate::define('viewPlannerCategories', [PlannerCategoryPolicy::class, 'viewAll']);
        Gate::define('createPlannerSection', [PlannerSectionPolicy::class, 'create']);
        Gate::define('viewPlannerSections', [PlannerSectionPolicy::class, 'viewAll']);
        Gate::define('createProductCategory', [ProductCategoryPolicy::class, 'create']);
        Gate::define('viewProductCategories', [ProductCategoryPolicy::class, 'viewAll']);
        Gate::define('createProduct', [ProductPolicy::class, 'create']);
        Gate::define('viewProducts', [ProductPolicy::class, 'viewAll']);
        Gate::define('viewProductReviews', [ProductReviewPolicy::class, 'viewAll']);


        View::share('codeInjections', SiteConfig::getCachedCodeInjections());
    }
}
