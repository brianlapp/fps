<?php

namespace App\Http\Middleware;

use App\Helpers\HtmlHelper;
use App\Http\Resources\Profile;
use App\Models\Menu;
use App\Models\OfferCategory;
use App\Models\ProductReview;
use App\Models\SiteConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Inertia\Middleware;
use function Symfony\Component\String\u;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $socialSettings = SiteConfig::getCachedSocials();
        $user = $request->user();
        
        // Default values in case the keys don't exist
        $title = $headline = $image = '';
        
        // Check if 'seo' key exists in $socialSettings
        if (isset($socialSettings['seo'])) {
            $title = $socialSettings['seo']['title'] ?? '';
            $headline = $socialSettings['seo']['headline'] ?? '';
            $image = $socialSettings['seo']['image'] ?? '';
        }
        $menu = Menu::getCached();
        if (!Auth::check()) {
            $menu = collect($menu)->where('auth_only', false)->values()->toArray();
        }
        $country = $user?->country ?? \App\Helpers\GeoHelper::getGeoPayload()['country'];

        return [
            ...parent::share($request),
            'auth' => [
                'user' => !empty($user) ? new Profile($user) : null,
            ],
            'app' => [
                'name' => config('app.name'),
                'url' => url(''),
                'seo' => [
                    'title' => $title,
                    'headline' => $headline,
                    'image' => $image,
                    'link' => url(''),
                    'googleCard' => [
                        "@context" => "https://schema.org",
                        "@type" => "Article",
                        "headline" => $title,
                        "image" => [
                            $image,
                        ],
                    ]
                ],
                'trackingParams' => HtmlHelper::TRACKING_PARAMS,
                'maxRate' => ProductReview::MAX_RATE
            ],
            'flash' => [
                'message' => fn() => $request->session()->get('message'),
                'error' => fn() => $request->session()->get('error'),
                'warning' => fn() => $request->session()->get('warning'),
                'multipleErrors' => fn() => $request->session()->get('errors'),
            ],
            'query' => $request->query(),
            'menu' => $menu,
            'social_networks' => $socialSettings['networks'] ?? [],
            'country' => $country
        ];
    }
}
