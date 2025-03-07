<?php

namespace App\Http\Controllers;

use App\Helpers\GeoHelper;
use App\Http\Resources\Admin\Country;
use App\Http\Resources\OfferCategoryPublic;
use App\Http\Resources\ArticlePublic;
use App\Http\Resources\OfferPublic;
use App\Models\Article;
use App\Models\ArticleRequest;
use App\Models\Offer;
use App\Models\OfferCategory;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Inertia;

class OffersController
{
    public function show(string $slug, Request $request)
    {
        $offerCategoryModel = OfferCategory::query()->where('slug', $slug)->active()->first();
        $filters = $request->only(['country']);

        if (empty($offerCategoryModel)) {
            abort(404);
        }
        $countriesList = \App\Models\Country::query()->active()->get();
        $countries = Country::collection($countriesList);
        if (!isset($filters['country'])) {
            $geo = GeoHelper::getGeoPayload();
            if ($countriesList->where('code', $geo['country'])->count() > 0) {
                $filters['country'] = $geo['country'];
            } else {
                $filters['country'] = 'all';
            }
        }


        $offerCategory = new OfferCategoryPublic($offerCategoryModel);
        /**
         * @var $offers LengthAwarePaginator
         */
        $offers = $offerCategoryModel->offers()->active()->filter($filters)->latest()->paginate($request->get('per_page', 21));
        $offers->setCollection(OfferPublic::collection($offers->getCollection())->collection);

        return Inertia::render('Offers', compact('offers', 'offerCategory', 'countries', 'filters'))->withViewData(['meta' => [
            'title' => $offerCategoryModel->title,
            'headline' => $offerCategoryModel->headline,
            'link' => route('offer_categories.show', $offerCategoryModel->slug),
            'image' => $offerCategoryModel->getFirstMediaUrl('image', 'webp'),
            'googleCard' => [
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'headline' => $offerCategoryModel->title,
                'image' => [
                    $offerCategoryModel->getFirstMediaUrl('image', 'webp')
                ],
                'datePublished' => $offerCategoryModel->updated_at->toIsoString(),
            ]
        ]]);
    }
}
