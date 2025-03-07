<?php

use App\Http\Middleware\ClientIdentifier;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\HandleInertiaRequestsAdmin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::prefix('admin')
                ->middleware([
                    AddQueuedCookiesToResponse::class,
                    StartSession::class,
                    ShareErrorsFromSession::class,
                    HandleInertiaRequestsAdmin::class,
                    AddLinkHeadersForPreloadedAssets::class,
                    SubstituteBindings::class
                ])
                ->as('admin.')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            ClientIdentifier::class,
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ])->redirectGuestsTo(fn () => route('signup'));;
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminAuthenticated::class,
            'admin_guest' => \App\Http\Middleware\AdminGuest::class,
            'admin_super' => \App\Http\Middleware\AdminSuper::class,
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
