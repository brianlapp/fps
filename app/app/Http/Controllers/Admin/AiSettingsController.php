<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Offer as OfferResource;
use App\Models\AiSettings;
use App\Models\Article;
use App\Models\Offer;
use App\Models\OfferCategory;
use App\Services\Writer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Tags\Tag;

class AiSettingsController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        /**
         * @var $list LengthAwarePaginator
         */
        $list = AiSettings::query()->latest()->paginate($request->get('per_page', 20));

        return Inertia::render('Admin/AiSettings/Index', compact('list'));
    }

    /**
     * @param AiSettings $aiSettings
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(AiSettings $aiSettings, Request $request): RedirectResponse
    {
        $models = in_array($aiSettings->function, ['image_for_article']) ? AiSettings::AVAILABLE_IMAGES_MODELS : AiSettings::AVAILABLE_MODELS;
        $request->validate([
            'temperature' => ['required', 'numeric', 'min:0', 'max:0.9'],
            'model' => ['required', Rule::in($models)],
            'prompt' => ['required', 'string'],
            'instructions' => ['nullable', 'string'],
        ]);

        $aiSettings->fill($request->only(['temperature', 'model', 'instructions', 'prompt']));
        $aiSettings->save();

        return Redirect::back()->with('message', 'Settings Stored');
    }

    /**
     * @param AiSettings $aiSettings
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function edit(AiSettings $aiSettings, Request $request): RedirectResponse|Response
    {
        $models = in_array($aiSettings->function, ['image_for_article']) ? AiSettings::AVAILABLE_IMAGES_MODELS : AiSettings::AVAILABLE_MODELS;

        return Inertia::render('Admin/AiSettings/Edit', [
            'settings' => $aiSettings,
            'models' => $models
        ]);
    }

    /**
     * @param AiSettings $aiSettings
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function setDefaults(AiSettings $aiSettings, Request $request): RedirectResponse|Response
    {
        $aiSettings->setDefaults();

        return Redirect::back()->with('message', 'Settings were set to Defaults');
    }

    public function testLiveArticle(Request $request): JsonResponse
    {
        $payload = [
            'searchQuery' => $request->get('search_query'),
            'previousTitles' => Article::search($request->get('search_query'))
                ->take(100)->get()->pluck('title')->toArray(),
            'token' => config('services.writer.api.token'),
            'instructions' => $request->get('instructions'),
            'prompt' => $request->get('prompt'),
            'model' => $request->get('model'),
            'temperature' => $request->get('temperature'),
        ];
        $json = json_encode($payload);
        $key = config('services.writer.cryptKey');
        $encrypted = $key . strrev($json) . strrev($key);

        return \response()->json([
                'livePayload' => [
                    'pid' => base64_encode($encrypted)
                ],
                'liveUrl' => config('services.writer.api.url') . 't'
            ]
        );
    }

    public function testArticles(Request $request): JsonResponse
    {
        $settings = new AiSettings();
        $settings->fill([
            'instructions' => $request->get('instructions'),
            'prompt' => $request->get('prompt'),
            'model' => $request->get('model'),
            'temperature' => $request->get('temperature'),
        ]);

        $writer = resolve(Writer::class);
        $titles = Article::search($request->get('search_query'))
            ->take(100)->get()->pluck('title')->toArray();
        $response = $writer->articles($request->get('search_query'), 2, $titles, $settings);

        return \response()->json($response);
    }

    public function testImage(Request $request): JsonResponse
    {
        $settings = new AiSettings();
        $settings->fill([
            'instructions' => $request->get('instructions'),
            'prompt' => $request->get('prompt'),
            'model' => $request->get('model'),
            'temperature' => $request->get('temperature'),
        ]);

        $article = new Article();
        $article->fill($request->only(['title', 'content']));

        $writer = resolve(Writer::class);
        $response = $writer->image($article, $settings);

        return \response()->json($response);
    }
}
