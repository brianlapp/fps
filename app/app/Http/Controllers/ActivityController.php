<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\FeedTrait;
use App\Http\Requests\ActivityRequest;
use App\Http\Resources\Activity;
use App\Http\Resources\ArticlePublic;
use App\Models\Activity as ActivityModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ActivityController extends Controller
{
    private array $filters = [];

    const SEO = [
        'title' => 'Find Fun, Tailored Activities for You and Your Child | Fresh Ideas for Every Day',
        'headline' => 'Discover amazing, personalized activities for you and your child with our easy-to-use generator. Enter a few details and get fresh, fun ideas perfect for every day!',
        'image' => '/images/activity_seo.png'
    ];

    public function index(Request $request)
    {
        return Inertia::render('Activity/Index', [
            'activities' => $this->getFeed($request),
            'activeFilters' => $this->filters,
            'seo' => $this->getViewData()['meta']
        ])->withViewData($this->getViewData());
    }

    public function new(Request $request)
    {
        return Inertia::render('Activity/New', [
            'seo' => $this->getViewData()['meta']
        ])->withViewData(['title']);
    }

    public function view(string $uuid, Request $request)
    {
        $record = ActivityModel::query()->whereUuid($uuid)->where(function (Builder $q) {
            $q->successful()->orWhere('user_id', Auth::user()?->id);
        })->first();

        if (empty($record)) {
            abort(404);
        }

        return Inertia::render('Activity/View', [
            'record' => new Activity($record),
            'seo' => $this->getViewData()['meta']
        ])->withViewData(['title']);
    }

    public function create(ActivityRequest $request)
    {
        /**
         * @var $user User
         */
        $user = Auth::user();
        $record = new ActivityModel([
            'uuid' => Str::uuid(),
            'user_id' => $user->id
        ]);
        $record->fill($request->validated());
        $record->save();
        $record->generateInBackground();

        return Redirect::route('activity.view', $record->uuid);
    }

    public function status(string $uuid)
    {
        /**
         * @var $user User
         */
        $user = Auth::user();
        $record = $user->activities()->whereUuid($uuid)->first();
        $status = $record?->status_string ?? 'error';
        $result = $record?->result ?? null;

        return response()->json([
            'status' => $status,
            'result' => $result,
        ]);
    }


    public function my(Request $request)
    {
        return Inertia::render('Activity/Index', [
            'activities' => $this->getFeed($request, true),
            'activeFilters' => $this->filters,
            'my' => true,
            'hasItems' => Auth::user()->activities()->exists(),
            'seo' => $this->getViewData()['meta']
        ])->withViewData($this->getViewData());
    }

    public function feed(Request $request)
    {
        return response()->json($this->getFeed($request));
    }

    private function getViewData()
    {
        return [
            'meta' => [
                'title' => self::SEO['title'],
                'headline' => self::SEO['headline'],
                'image' =>  url(self::SEO['image']),
                'link' => route('activity.index'),
                'googleCard' => [
                    '@context' => 'https://schema.org',
                    '@type' => 'Article',
                    'headline' => self::SEO['title'],
                    'image' => !empty(self::SEO['image']) ? [
                        url(self::SEO['image'])
                    ] : null,
                ]
            ]
        ];
    }

    private function getFeed(Request $request, bool $my = false)
    {
        $this->filters = $request->only([
            'child_age',
            'available_time',
            'location',
            'activity_type_preferences',
        ]);
        $query = ActivityModel::query()
            ->filter($this->filters)->orderBy('generated_at', 'DESC');
        if ($my || $request->get('my')) {
            $query->where('user_id', Auth::user()?->id)->notFailed();
        } else {
            $query->successful()->original();
        }
        $result = $query
            ->paginate($request->get('per_page', 50));

        $result->setCollection(Activity::collection($result->getCollection())->collection);
        return $result;
    }
}
