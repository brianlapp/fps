<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\OfferRequest;
use App\Http\Resources\Admin\Country;
use App\Models\Admin;
use App\Models\Offer;
use App\Models\OfferCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use \App\Http\Resources\Admin\Offer as OfferResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class OffersController
{
    private Admin $admin;

    public function __construct()
    {
        $this->admin = Auth::guard('admin')->user();
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function index(string $offerCategory, Request $request): Response
    {
        $filters = $request->only(['search', 'trashed', 'isActive', 'countries']);
        $category = OfferCategory::query()->whereSlug($offerCategory)->first();
        if (empty($category)) {
            abort(404);
        }
        $category = new \App\Http\Resources\Admin\OfferCategory($category);
        /**
         * @var $list LengthAwarePaginator
         */
        $list = Offer::query()->filter($filters)->where('offer_category_id', $category->id)->latest()->paginate($request->get('per_page', 20));
        $list->setCollection(OfferResource::collection($list->getCollection())->collection);
        $countries =  Country::collection(\App\Models\Country::query()->active()->get());

        return Inertia::render('Admin/Offer/Index', compact('list', 'filters', 'category', 'countries'));
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function create(string $offerCategory, Request $request): Response
    {
        $category = OfferCategory::query()->whereSlug($offerCategory)->first();
        if (empty($category)) {
            abort(404);
        }
        $category = new \App\Http\Resources\Admin\OfferCategory($category);
        $countries = Country::collection(\App\Models\Country::query()->active()->get());
        return Inertia::render('Admin/Offer/Edit', compact('category', 'countries'));
    }

    public function store(OfferRequest $request): RedirectResponse
    {
        if ($this->admin->cannot('createOffer')) {
            $category = OfferCategory::find($request->get('offer_category_id'));
            return \redirect()->route('admin.offers.index', $category->slug)->with('error', 'Access Denied');
        }
        $offer = new Offer();
        $data = $request->validated();

        $offer->fill($data);
        $offer->save();
        $offer->refresh();

        $offer->updateImage($request->get('image'));
        $offer->countries()->sync(array_column($data['countries'], 'id'));

        return Redirect::route('admin.offers.index', $offer->offerCategory->slug)->with('message', 'Offer Created');
    }

    public function edit(string $offerCategory, Offer $offer, Request $request): RedirectResponse|Response
    {
        if ($this->admin->cannot('update', $offer)) {
            return \redirect()->route('admin.offers.index', $offer->offerCategory->slug)->with('error', 'Access Denied');
        }

        return Inertia::render('Admin/Offer/Edit', [
            'offer' => new OfferResource($offer),
            'category' => new \App\Http\Resources\Admin\OfferCategory($offer->offerCategory),
            'countries' => Country::collection(\App\Models\Country::query()->active()->get())
        ]);
    }

    public function update(Offer $offer, OfferRequest $request): RedirectResponse
    {
        if ($this->admin->cannot('update', $offer)) {
            return \redirect()->route('admin.offers.index', $offer->offerCategory->slug)->with('error', 'Access Denied');
        }
        $data = $request->validated();

        $offer->fill($data);
        $offer->save();
        $offer->updateImage($request->get('image'));
        $offer->countries()->sync(array_column($data['countries'], 'id'));

        return Redirect::route('admin.offers.index', $offer->offerCategory->slug)->with('message', 'Offer Updated');
    }

    public function destroy(Offer $offer): RedirectResponse
    {
        if ($this->admin->cannot('delete', $offer)) {
            return \redirect()->route('admin.offers.index', $offer->offerCategory->slug)->with('error', 'Access Denied');
        }
        $offer->delete();

        return \redirect()->back()->with('message', 'Offer Deleted');
    }

    public function recover(Offer $offer): RedirectResponse
    {
        if ($this->admin->cannot('delete', $offer)) {
            return \redirect()->route('admin.offers.index', $offer->offerCategory->slug)->with('error', 'Access Denied');
        }
        $offer->restore();

        return \redirect()->back()->with('message', 'Offer Recovered');
    }
}
