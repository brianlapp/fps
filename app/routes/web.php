<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\NBPlannerController;
use App\Http\Controllers\OffersController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\PromptAuthorController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SweepsController;
use App\Http\Controllers\TagsController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\TYController;
use App\Services\BeeHiivService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [SearchController::class, 'index'])->name('home');

Route::get('/signup', function () {
    return Inertia::render('Signup/Page')->withViewData(['meta' => [
        'title' => 'Sign Up',
    ]]);
})->middleware('guest')->name('signup');

Route::get('/signup/embark', function () {
    return Redirect::route('signupEmbark', [], 301);
});
Route::get('/sweeps/embark', [TYController::class, 'embarkStandaloneForm'])->name('signupEmbark');

Route::group(['prefix' => '/sweeps', 'as' => 'sweeps.'], function (){
    Route::post('', [SweepsController::class, 'submit'])->name('submit');
    Route::get('/newborn', function () {
        return Inertia::render('Sweeps/Newborn')->withViewData(['meta' => [
            'title' => 'Sign Up',
        ]]);
    })->name('newborn');
    Route::get('/toddler', function () {
        return Inertia::render('Sweeps/Toddler')->withViewData(['meta' => [
            'title' => 'Sign Up',
        ]]);
    })->name('toddler');
    Route::get('/preschool', function () {
        return Inertia::render('Sweeps/PreSchool')->withViewData(['meta' => [
            'title' => 'Sign Up',
        ]]);
    })->name('preschool');
    Route::get('/school-age', function () {
        return Inertia::render('Sweeps/SchoolAge')->withViewData(['meta' => [
            'title' => 'Sign Up',
        ]]);
    })->name('school');
    Route::get('/family-night', function () {
        return Inertia::render('Sweeps/FamilyNight')->withViewData(['meta' => [
            'title' => 'Sign Up',
        ]]);
    })->name('family');
});


Route::get('/thank-you', [TYController::class, 'index'])->name('ty');
Route::post('/giveaway', [TYController::class, 'giveAway'])->name('giveaway-submit');

Route::group(['prefix' => 'contact-us', 'as' => 'contact-us.'], function () {
    Route::get('', [ContactController::class, 'showForm'])->name('show-form');
    Route::post('', [ContactController::class, 'submit'])->name('submit');
});

// Health Check
Route::get('health-check', [HealthController::class, 'check']);

Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::post('/search', [SearchController::class, 'submit'])->name('search.submit');
Route::get('/explore', [ArticleController::class, 'feed'])->name('articles.feed');
Route::group(['prefix' => 'a', 'as' => 'articles.'], function () {
    Route::get('load-feed', [ArticleController::class, 'loadFeed'])->name('load-feed');
    Route::get('saved', [ArticleController::class, 'favoritesFeed'])->name('favorites')->middleware('auth');
    Route::get('saved/feed', [ArticleController::class, 'loadFavoritesFeed'])->name('favoritesFeed')->middleware('auth');
    Route::get('{slug}', [ArticleController::class, 'show'])->name('show');
    Route::post('', [ArticleController::class, 'save'])->name('save');
    Route::post('{slug}/favorite', [ArticleController::class, 'addToFavorites'])->name('addToFavorites')->middleware('auth');
    Route::post('{slug}/vote', [ArticleController::class, 'vote'])->name('vote');

});

Route::group(['prefix' => 'offers', 'as' => 'offer_categories.'], function () {
    Route::get('{slug}', [OffersController::class, 'show'])->name('show');
});

Route::group(['prefix' => 'author', 'as' => 'author.'], function () {
    Route::get('{slug}', [AuthorController::class, 'index'])->name('show');
    Route::get('{slug}/feed', [AuthorController::class, 'loadFeed'])->name('feed');
});

Route::group(['prefix' => 'tags', 'as' => 'tags.'], function () {
    Route::get('', [TagsController::class, 'search'])->name('search');
});

Route::group(['prefix' => 'topics', 'as' => 'topics.'], function () {
    Route::get('{slug}', [TopicController::class, 'index'])->name('show');
    Route::get('{slug}/feed', [TopicController::class, 'loadFeed'])->name('feed');
});

//Route::get('test', function (){
//   $a = \App\Models\Article::find(348);
//   return view('compare', [
//       'content' => $a->content,
//       'improved' => $a->improve(false)
//   ]);
//});

Route::group(['prefix' => 'prompt-author', 'as' => 'prompt_author.'], function () {
    Route::get('{slug}', [PromptAuthorController::class, 'index'])->name('show');
    Route::get('{slug}/feed', [PromptAuthorController::class, 'loadFeed'])->name('feed');
});

Route::group(['prefix' => 'ultimate-baby-checklist-planner', 'as' => 'planner_categories.'], function () {
    Route::get('/', function () {
        $firstCategory = \App\Models\PlannerCategory::query()->orderBy('order', 'ASC')->first();
        if(empty($firstCategory)) {
            return Redirect::route('home');
        }
        return Redirect::route('planner_categories.show', $firstCategory->slug);
    })->name('index');

    Route::get('{slug}', [NBPlannerController::class, 'show'])->name('show');
    Route::get('/toggle/{uuid}', [NBPlannerController::class, 'toggleItem'])->name('toggleItem')->middleware('auth');
});

Route::group(['prefix' => 'activity-generator', 'as' => 'activity.'], function () {
    Route::get('', [ActivityController::class, 'index'])->name('index');
    Route::get('my', [ActivityController::class, 'my'])->name('my')->middleware('auth');
    Route::get('feed', [ActivityController::class, 'feed'])->name('feed');
    Route::get('new', [ActivityController::class, 'new'])->name('new');
    Route::post('create', [ActivityController::class, 'create'])->name('create')->middleware('auth');
    Route::get('{uuid}', [ActivityController::class, 'view'])->name('view');
    Route::get('{uuid}/status', [ActivityController::class, 'status'])->name('status')->middleware('auth');
});

Route::group(['prefix' => 'product-reviews', 'as' => 'product_categories.'], function () {
    Route::get('', [ProductCategoryController::class, 'index'])->name('index');
    Route::get('{slug}', [ProductCategoryController::class, 'show'])->name('show');
    Route::get('p/{slug}', [ProductController::class, 'show'])->name('showProduct');
    Route::get('{categorySlug}/{slug}', [ProductController::class, 'show'])->name('showCategoryProduct');
    Route::post('p/{slug}', [ProductReviewController::class, 'submit'])->name('submitReview')->middleware('auth');
    Route::get('p/{slug}/reviews', [ProductReviewController::class, 'feed'])->name('reviews');
});

Route::post('join-newsletter', [\App\Http\Controllers\NewsLetterController::class, 'join'])->name('joinNewsletter');
require __DIR__ . '/auth.php';
require __DIR__ . '/rss.php';
Route::get('{slug}', PagesController::class)->name('page');
