<?php

namespace App\Http\Middleware;

use App\Http\Resources\Admin\PlannerSection;
use App\Models\Admin;
use App\Models\OfferCategory;
use App\Models\PlannerCategory;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Inertia\Middleware;

class HandleInertiaRequestsAdmin extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app_admin';

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
        $user = null;
        $admin = Auth::guard('admin')->user();
        /**
         * @var $admin Admin
         */
        if (!empty($admin)) {
            $user = $admin->only(['name', 'id', 'role', 'email']);
            $user['avatar'] = $admin->getFirstMediaUrl('avatar', 'thumbnail');
            $user['fullAvatar'] = $admin->getFirstMediaUrl('avatar', 'webp');
            $user['hasAvatar'] = $user['avatar'] === Admin::DEFAULT_AVATAR;
        }
        $currentRouteName = Route::getCurrentRoute()->getName() ?? '';
        $categoryParam = Route::getCurrentRoute()->parameter('offerCategory') ?? '';
        $plannerCategoryParam = Route::getCurrentRoute()->parameter('plannerCategory') ?? '';

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user
            ],
            'app' => [
                'name' => config('app.name'),
                'url' => url(),
                'maxRate' => ProductReview::MAX_RATE
            ],
            'flash' => [
                'message' => fn() => $request->session()->get('message'),
                'error' => fn() => $request->session()->get('error'),
                'warning' => fn() => $request->session()->get('warning'),
                'multipleErrors' => fn() => $request->session()->get('errors'),
            ],
            'sideMenu' => !empty($admin) ? [
                [
                    'label' => 'Team',
                    'url' => \route('admin.team.index'),
                    'icon' => 'fa-solid fa-people-group',
                    'visible' => $admin->is_super,
                    'active' => Str::startsWith($currentRouteName, ['admin.team.'])
                ],
                [
                    'label' => 'Articles',
                    'url' => \route('admin.articles.index'),
                    'icon' => 'fa-solid fa-newspaper',
                    'visible' => true,
                    'active' => Str::startsWith($currentRouteName, ['admin.articles.'])
                ],
                [
                    'label' => 'Topics',
                    'url' => \route('admin.topics.index'),
                    'icon' => 'fa-solid fa-hashtag',
                    'visible' => $admin->can('viewTopics'),
                    'active' => Str::startsWith($currentRouteName, ['admin.topics.'])
                ],

                [
                    'label' => 'Offers',
                    'url' => null,
                    'visible' => true,
                    'icon' => 'fa-solid fa-gift',
                    'active' => Str::startsWith($currentRouteName, ['admin.offer']),
                    'items' => array_merge([
                        [
                            'label' => 'Manage Categories',
                            'url' => \route('admin.offer_categories.index'),
                            'visible' => $admin->can('viewOfferCategories'),
                            'active' => Str::startsWith($currentRouteName, ['admin.offer_categories.']),
                            'class' => 'pb-5 border-b'
                        ],
                    ], OfferCategory::query()->get()->transform(fn(OfferCategory $offerCategory) => [
                        'label' => $offerCategory->title,
                        'url' => \route('admin.offers.index', $offerCategory->slug),
                        'visible' => $admin->can('viewOffers'),
                        'active' => $categoryParam === $offerCategory->slug,
                    ])->toArray())
                ],
                [
                    'label' => 'NB Planner',
                    'url' => null,
                    'visible' => true,
                    'icon' => 'fa-solid fa-baby',
                    'active' => Str::startsWith($currentRouteName, ['admin.planner']),
                    'items' => array_merge([
                        [
                            'label' => 'Manage Categories',
                            'url' => \route('admin.planner_categories.index'),
                            'visible' => $admin->can('viewPlannerCategories'),
                            'active' => Str::startsWith($currentRouteName, ['admin.planner_categories.']),
                            'class' => 'pb-5 border-b'
                        ],
                    ], PlannerCategory::query()->orderBy('order', 'ASC')->get()->transform(fn(PlannerCategory $category) => [
                        'label' => $category->title,
                        'url' => \route('admin.planner_sections.index', $category->id),
                        'visible' => $admin->can('viewPlannerSections'),
                        'active' => $plannerCategoryParam == $category->id,
                    ])->toArray())
                ],
                [
                    'label' => 'Product Reviews',
                    'url' => null,
                    'visible' => true,
                    'icon' => 'fa-solid fa-shop',
                    'active' => Str::startsWith($currentRouteName, ['admin.product']),
                    'items' => [
                        [
                            'label' => 'Categories',
                            'url' => \route('admin.product_categories.index'),
                            'visible' => $admin->can('viewProductCategories'),
                            'active' => Str::startsWith($currentRouteName, ['admin.product_categories.']),
                            'class' => 'pb-5'
                        ],
                        [
                            'label' => 'Products',
                            'url' => \route('admin.products.index'),
                            'visible' => $admin->can('viewProducts'),
                            'active' => Str::startsWith($currentRouteName, ['admin.products.']),
                            'class' => 'pb-5'
                        ],
                        [
                            'label' => 'Reviews',
                            'url' => \route('admin.product_reviews.index'),
                            'visible' => $admin->can('viewProductReviews'),
                            'active' => Str::startsWith($currentRouteName, ['admin.product_reviews.']),
                            'class' => 'pb-5'
                        ],
                    ]
                ],
                [
                    'label' => 'Settings',
                    'url' => null,
                    'visible' => $admin->can('viewSettings'),
                    'icon' => 'fa-solid fa-gear',
                    'active' => Str::startsWith($currentRouteName, ['admin.menu', 'admin.ai-settings.', 'admin.code-injections.', 'admin.social-settings.']),
                    'items' => [
                        [
                            'label' => 'Menu',
                            'url' => \route('admin.menu.index'),
                            'visible' => $admin->can('editMenu'),
                            'active' => Str::startsWith($currentRouteName, ['admin.menu.']),
                        ],
                        [
                            'label' => 'AI Settings',
                            'url' => \route('admin.ai-settings.index'),
                            'visible' => $admin->is_super,
                            'active' => Str::startsWith($currentRouteName, ['admin.ai-settings.']),
                        ],
                        [
                            'label' => 'Code Injections',
                            'url' => \route('admin.code-injections.edit'),
                            'visible' => $admin->is_super,
                            'active' => Str::startsWith($currentRouteName, ['admin.code-injections.']),
                        ],
                        [
                            'label' => 'SEO & Socials',
                            'url' => \route('admin.social-settings.edit'),
                            'visible' => $admin->is_super,
                            'active' => Str::startsWith($currentRouteName, ['admin.social-settings.']),
                        ],
                    ],
                ]
            ] : []
        ];
    }
}
