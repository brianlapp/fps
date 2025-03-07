<?php

use App\Http\Controllers\Admin\AdminsController;
use App\Http\Controllers\Admin\AiSettingsController;
use App\Http\Controllers\Admin\ArticlesController;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\PasswordController;
use App\Http\Controllers\Admin\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\CodeInjectionsController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\OfferCategoriesController;
use App\Http\Controllers\Admin\OffersController;
use App\Http\Controllers\Admin\PlannerCategoriesController;
use App\Http\Controllers\Admin\PlannerSectionsController;
use App\Http\Controllers\Admin\ProductCategoriesController;
use App\Http\Controllers\Admin\ProductReviewsController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SocialSettingsController;
use App\Http\Controllers\Admin\TagsController;
use App\Http\Controllers\Admin\TopicsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('admin_guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

//    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

//    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
//                ->name('password.request');
//
//    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
//                ->name('password.email');
//
//    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
//                ->name('password.reset');
//
//    Route::post('reset-password', [NewPasswordController::class, 'store'])
//                ->name('password.store');
});

Route::middleware('admin')->group(function () {
//    Route::get('verify-email', EmailVerificationPromptController::class)
//                ->name('verification.notice');
//
//    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
//                ->middleware(['signed', 'throttle:6,1'])
//                ->name('verification.verify');
//
//    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
//                ->middleware('throttle:6,1')
//                ->name('verification.send');
//
//    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
//                ->name('password.confirm');
//
//    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
//
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::get('/', function () {
        return \redirect(\route('admin.dashboard'));
    })->name('dashboard');
    Route::get('/dashboard', function () {
        return Inertia::render('Admin/Dashboard');
    })->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');

    Route::prefix('content')->as('content.')->group(function () {
        Route::post('upload-image/{entityType}', [ContentController::class, 'uploadImage'])->name('uploadImage');
    });

    // Admins
    Route::group(['prefix' => 'team', 'as' => 'team.', 'middleware' => 'admin_super'], function () {
        Route::get('', [AdminsController::class, 'index'])->name('index');
        Route::get('/create', [AdminsController::class, 'create'])->name('create');
        Route::post('/', [AdminsController::class, 'store'])->name('store');
        Route::get('/{admin}', [AdminsController::class, 'edit'])->name('edit')->missing(function (Request $request) {
            return Redirect::route('admin.team.index')->with('error', 'Teammate Not Found!');
        });
        Route::patch('/{admin}', [AdminsController::class, 'update'])->name('update')->missing(function (Request $request) {
            return Redirect::route('admin.team.index')->with('error', 'Teammate Not Found!');
        });
        Route::delete('/{admin}', [AdminsController::class, 'destroy'])->name('destroy')->missing(function (Request $request) {
            return Redirect::route('admin.team.index')->with('error', 'Teammate Not Found!');
        });
        Route::post('/{admin}/avatar', [AdminsController::class, 'updateAvatar'])->name('avatar')->missing(function (Request $request) {
            return Redirect::route('admin.team.index')->with('error', 'Teammate Not Found!');
        });
        Route::put('/{admin}/password', [AdminsController::class, 'updatePassword'])->name('password')->missing(function (Request $request) {
            return Redirect::route('admin.team.index')->with('error', 'Teammate Not Found!');
        });
    });

    // Articles
    Route::group(['prefix' => 'articles', 'as' => 'articles.'], function () {
        Route::get('', [ArticlesController::class, 'index'])->name('index');
        Route::get('/create', [ArticlesController::class, 'create'])->name('create');
        Route::post('/', [ArticlesController::class, 'store'])->name('store');
        Route::get('/reset-featured', [ArticlesController::class, 'resetFeatured'])->name('resetFeatured');
        Route::get('/{article}', [ArticlesController::class, 'edit'])->name('edit')->missing(function (Request $request) {
            return Redirect::route('admin.articles.index')->with('error', 'Article Not Found!');
        });
        Route::patch('/{article}', [ArticlesController::class, 'update'])->name('update')->missing(function (Request $request) {
            return Redirect::route('admin.articles.index')->with('error', 'Article Not Found!');
        });
        Route::delete('/{article}', [ArticlesController::class, 'destroy'])->name('destroy')->missing(function (Request $request) {
            return Redirect::route('admin.articles.index')->with('error', 'Article Not Found!');
        });
        Route::get('/{article}/generate-image', [ArticlesController::class, 'generateImage'])->name('generateImage')->missing(function (Request $request) {
            return Redirect::route('admin.articles.index')->with('error', 'Article Not Found!');
        });
        Route::get('/{article}/improve', [ArticlesController::class, 'improve'])->name('improve')->missing(function (Request $request) {
            return Redirect::route('admin.articles.index')->with('error', 'Article Not Found!');
        });
    });

    // Offer Categories
    Route::group(['prefix' => 'offer_categories', 'as' => 'offer_categories.'], function () {
        Route::get('', [OfferCategoriesController::class, 'index'])->name('index');
        Route::get('/create', [OfferCategoriesController::class, 'create'])->name('create');
        Route::post('/', [OfferCategoriesController::class, 'store'])->name('store');
        Route::get('/{offerCategory}', [OfferCategoriesController::class, 'edit'])->name('edit')->missing(function (Request $request) {
            return Redirect::route('admin.offer_categories.index')->with('error', 'Offer Category Not Found!');
        });
        Route::patch('/{offerCategory}', [OfferCategoriesController::class, 'update'])->name('update')->missing(function (Request $request) {
            return Redirect::route('admin.offer_categories.index')->with('error', 'Offer Category Not Found!');
        });
        Route::delete('/{offerCategory}', [OfferCategoriesController::class, 'destroy'])->name('destroy')->missing(function (Request $request) {
            return Redirect::route('admin.offer_categories.index')->with('error', 'Offer Category Not Found!');
        });
        Route::delete('/{offerCategory}/recover', [OfferCategoriesController::class, 'recover'])->name('recover')->missing(function (Request $request) {
            return Redirect::route('admin.offer_categories.index')->with('error', 'Offer Category Not Found!');
        });
    });

    // Offers
    Route::group(['prefix' => 'offers', 'as' => 'offers.'], function () {
        Route::get('/{offerCategory}', [OffersController::class, 'index'])->name('index');
        Route::get('/{offerCategory}/create', [OffersController::class, 'create'])->name('create');
        Route::post('/', [OffersController::class, 'store'])->name('store');
        Route::get('/{offerCategory}/{offer}', [OffersController::class, 'edit'])->name('edit')->missing(function (Request $request) {
            return Redirect::route('admin.offers.index')->with('error', 'Offer Category Not Found!');
        });
        Route::patch('/{offer}', [OffersController::class, 'update'])->name('update')->missing(function (Request $request) {
            return Redirect::route('admin.offers.index')->with('error', 'Offer Not Found!');
        });
        Route::delete('/{offer}', [OffersController::class, 'destroy'])->name('destroy')->missing(function (Request $request) {
            return Redirect::route('admin.offers.index')->with('error', 'Offer Not Found!');
        });
        Route::delete('/{offer}/recover', [OffersController::class, 'recover'])->name('recover')->missing(function (Request $request) {
            return Redirect::route('admin.offers.index')->with('error', 'Offer Not Found!');
        });
    });

    // Tags
    Route::group(['prefix' => 'tags', 'as' => 'tags.'], function () {
        Route::get('', [TagsController::class, 'search'])->name('search');
    });

    // Menu
    Route::group(['prefix' => 'menu', 'as' => 'menu.'], function () {
        Route::get('', [MenuController::class, 'index'])->name('index');
        Route::get('create', [MenuController::class, 'create'])->name('create');
        Route::post('menu', [MenuController::class, 'store'])->name('store');
        Route::get('{menu}/edit', [MenuController::class, 'edit'])->name('edit');
        Route::put('{menu}', [MenuController::class, 'update'])->name('update');
        Route::delete('{menu}', [MenuController::class, 'destroy'])->name('destroy');
        Route::delete('{menu}/restore', [MenuController::class, 'restore'])->name('recover');
        Route::post('change', [MenuController::class, 'changeMenu'])->name('change');
        Route::get('reset', [MenuController::class, 'resetMenu'])->name('reset');
    });

    // AI Settings
    Route::group(['prefix' => 'ai-settings', 'as' => 'ai-settings.', 'middleware' => 'admin_super'], function () {
        Route::get('', [AiSettingsController::class, 'index'])->name('index');
        Route::get('{aiSettings}', [AiSettingsController::class, 'edit'])->name('edit');
        Route::patch('{aiSettings}', [AiSettingsController::class, 'update'])->name('update');
        Route::patch('{aiSettings}/defaults', [AiSettingsController::class, 'setDefaults'])->name('set-defaults');
        Route::post('test/live-article', [AiSettingsController::class, 'testLiveArticle'])->name('test-live-article');
        Route::post('test/articles', [AiSettingsController::class, 'testArticles'])->name('test-articles');
        Route::post('test/image', [AiSettingsController::class, 'testImage'])->name('test-image');
    });

    // Code Injections
    Route::group(['prefix' => 'code-injections', 'as' => 'code-injections.', 'middleware' => 'admin_super'], function () {
        Route::get('', [CodeInjectionsController::class, 'edit'])->name('edit');
        Route::patch('', [CodeInjectionsController::class, 'update'])->name('update');
    });

    // Social Settings
    Route::group(['prefix' => 'social-settings', 'as' => 'social-settings.', 'middleware' => 'admin_super'], function () {
        Route::get('', [SocialSettingsController::class, 'edit'])->name('edit');
        Route::patch('', [SocialSettingsController::class, 'update'])->name('update');
    });

    // Topics
    Route::group(['prefix' => 'topics', 'as' => 'topics.'], function () {
        Route::get('', [TopicsController::class, 'index'])->name('index');
        Route::get('/create', [TopicsController::class, 'create'])->name('create');
        Route::post('/', [TopicsController::class, 'store'])->name('store');
        Route::get('/{topic}', [TopicsController::class, 'edit'])->name('edit')->missing(function (Request $request) {
            return Redirect::route('admin.topics.index')->with('error', 'Topic Not Found!');
        });
        Route::patch('/{topic}', [TopicsController::class, 'update'])->name('update')->missing(function (Request $request) {
            return Redirect::route('admin.topics.index')->with('error', 'Topic Category Not Found!');
        });
        Route::delete('/{topic}', [TopicsController::class, 'destroy'])->name('destroy')->missing(function (Request $request) {
            return Redirect::route('admin.topics.index')->with('error', 'Topic Category Not Found!');
        });
        Route::delete('/{topic}/recover', [TopicsController::class, 'recover'])->name('recover')->missing(function (Request $request) {
            return Redirect::route('admin.topics.index')->with('error', 'Topic Category Not Found!');
        });
    });

    // Planner Categories
    Route::group(['prefix' => 'planner_categories', 'as' => 'planner_categories.'], function () {
        Route::get('', [PlannerCategoriesController::class, 'index'])->name('index');
        Route::get('/create', [PlannerCategoriesController::class, 'create'])->name('create');
        Route::post('/', [PlannerCategoriesController::class, 'store'])->name('store');
        Route::get('/{plannerCategory}', [PlannerCategoriesController::class, 'edit'])->name('edit')->missing(function (Request $request) {
            return Redirect::route('admin.planner_categories.index')->with('error', 'Planner Category Not Found!');
        });
        Route::patch('/{plannerCategory}', [PlannerCategoriesController::class, 'update'])->name('update')->missing(function (Request $request) {
            return Redirect::route('admin.planner_categories.index')->with('error', 'Planner Category Not Found!');
        });
        Route::delete('/{plannerCategory}', [PlannerCategoriesController::class, 'destroy'])->name('destroy')->missing(function (Request $request) {
            return Redirect::route('admin.planner_categories.index')->with('error', 'Planner Category Not Found!');
        });
        Route::delete('/{plannerCategory}/recover', [PlannerCategoriesController::class, 'recover'])->name('recover')->missing(function (Request $request) {
            return Redirect::route('admin.planner_categories.index')->with('error', 'Planner Category Not Found!');
        });
        Route::post('reorder', [PlannerCategoriesController::class, 'reorder'])->name('reorder');
    });

    // Planner Sections
    Route::group(['prefix' => 'planner_sections', 'as' => 'planner_sections.'], function () {
        Route::get('/{plannerCategory}/', [PlannerSectionsController::class, 'index'])->name('index');
        Route::get('{plannerCategory}/create', [PlannerSectionsController::class, 'create'])->name('create');
        Route::post('/', [PlannerSectionsController::class, 'store'])->name('store');
        Route::get('/{plannerCategory}/edit/{plannerSection}', [PlannerSectionsController::class, 'edit'])->name('edit')->missing(function (Request $request) {
            return Redirect::route('admin.planner_sections.index')->with('error', 'Planner Section Not Found!');
        });
        Route::patch('/{plannerSection}', [PlannerSectionsController::class, 'update'])->name('update')->missing(function (Request $request) {
            return Redirect::route('admin.planner_sections.index')->with('error', 'Planner Section Not Found!');
        });
        Route::delete('/{plannerSection}', [PlannerSectionsController::class, 'destroy'])->name('destroy')->missing(function (Request $request) {
            return Redirect::route('admin.planner_sections.index')->with('error', 'Planner Section Not Found!');
        });
        Route::delete('/{plannerSection}/recover', [PlannerSectionsController::class, 'recover'])->name('recover')->missing(function (Request $request) {
            return Redirect::route('admin.planner_sections.index')->with('error', 'Planner Section Not Found!');
        });
        Route::post('reorder', [PlannerSectionsController::class, 'reorder'])->name('reorder');
    });

    // Product Categories
    Route::group(['prefix' => 'product_categories', 'as' => 'product_categories.'], function () {
        Route::get('', [ProductCategoriesController::class, 'index'])->name('index');
        Route::get('/create', [ProductCategoriesController::class, 'create'])->name('create');
        Route::post('/', [ProductCategoriesController::class, 'store'])->name('store');
        Route::get('/{productCategory}', [ProductCategoriesController::class, 'edit'])->name('edit')->missing(function (Request $request) {
            return Redirect::route('admin.product_categories.index')->with('error', 'Product Category Not Found!');
        });
        Route::patch('/{productCategory}', [ProductCategoriesController::class, 'update'])->name('update')->missing(function (Request $request) {
            return Redirect::route('admin.product_categories.index')->with('error', 'Product Category Not Found!');
        });
        Route::delete('/{productCategory}', [ProductCategoriesController::class, 'destroy'])->name('destroy')->missing(function (Request $request) {
            return Redirect::route('admin.product_categories.index')->with('error', 'Product Category Not Found!');
        });
        Route::delete('/{productCategory}/recover', [ProductCategoriesController::class, 'recover'])->name('recover')->missing(function (Request $request) {
            return Redirect::route('admin.product_categories.index')->with('error', 'Product Category Not Found!');
        });
    });

    // Product
    Route::group(['prefix' => 'products', 'as' => 'products.'], function () {
        Route::get('', [ProductsController::class, 'index'])->name('index');
        Route::get('/create', [ProductsController::class, 'create'])->name('create');
        Route::post('/', [ProductsController::class, 'store'])->name('store');
        Route::get('/{product}', [ProductsController::class, 'edit'])->name('edit')->missing(function (Request $request) {
            return Redirect::route('admin.products.index')->with('error', 'Product  Not Found!');
        });
        Route::patch('/{product}', [ProductsController::class, 'update'])->name('update')->missing(function (Request $request) {
            return Redirect::route('admin.products.index')->with('error', 'Product  Not Found!');
        });
        Route::delete('/{product}', [ProductsController::class, 'destroy'])->name('destroy')->missing(function (Request $request) {
            return Redirect::route('admin.products.index')->with('error', 'Product  Not Found!');
        });
        Route::delete('/{product}/recover', [ProductsController::class, 'recover'])->name('recover')->missing(function (Request $request) {
            return Redirect::route('admin.products.index')->with('error', 'Product  Not Found!');
        });
    });

    // Product Reviews
    Route::group(['prefix' => 'product-reviews', 'as' => 'product_reviews.'], function () {
        Route::get('', [ProductReviewsController::class, 'index'])->name('index');
        Route::delete('/{productReview}', [ProductReviewsController::class, 'destroy'])->name('destroy')->missing(function (Request $request) {
            return Redirect::route('admin.product_reviews.index')->with('error', 'Product Review  Not Found!');
        });
        Route::delete('/{productReview}/recover', [ProductReviewsController::class, 'recover'])->name('recover')->missing(function (Request $request) {
            return Redirect::route('admin.product_reviews.index')->with('error', 'Product Review  Not Found!');
        });
    });


});
