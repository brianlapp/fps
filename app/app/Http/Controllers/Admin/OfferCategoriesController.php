<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\OfferCategoryRequest;
use App\Models\Admin;
use App\Models\OfferCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use \App\Http\Resources\Admin\OfferCategory as OfferCategoryResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class OfferCategoriesController
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
    public function index(Request $request): Response
    {
        $filters = $request->only(['search', 'trashed', 'isActive']);
        /**
         * @var $list LengthAwarePaginator
         */
        $list = OfferCategory::query()->filter($filters)->latest()->paginate($request->get('per_page', 20));
        $list->setCollection(OfferCategoryResource::collection($list->getCollection())->collection);

        return Inertia::render('Admin/OfferCategory/Index', compact('list', 'filters'));
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        return Inertia::render('Admin/OfferCategory/Edit', []);
    }

    public function store(OfferCategoryRequest $request): RedirectResponse
    {
        if ($this->admin->cannot('createOfferCategory')) {
            return \redirect()->route('admin.offer_categories.index')->with('error', 'Access Denied');
        }
        $offerCategory = new OfferCategory();
        $data = $request->validated();

        $offerCategory->fill($data);
        $offerCategory->save();
        $offerCategory->refresh();

        $offerCategory->updateImage($request->get('image'));

        return Redirect::route('admin.offer_categories.index')->with('message', 'Offer Category Created');
    }

    public function edit(OfferCategory $offerCategory, Request $request): RedirectResponse|Response
    {
        if ($this->admin->cannot('update', $offerCategory)) {
            return \redirect()->route('admin.offer_categories.index')->with('error', 'Access Denied');
        }

        return Inertia::render('Admin/OfferCategory/Edit', [
            'category' => new OfferCategoryResource($offerCategory),
        ]);
    }

    public function update(OfferCategory $offerCategory, OfferCategoryRequest $request): RedirectResponse
    {
        if ($this->admin->cannot('update', $offerCategory)) {
            return \redirect()->route('admin.offer_categories.index')->with('error', 'Access Denied');
        }
        $data = $request->validated();

        $offerCategory->fill($data);
        $offerCategory->save();
        $offerCategory->updateImage($request->get('image'));

        return Redirect::route('admin.offer_categories.index')->with('message', 'Offer Category Updated');
    }

    public function destroy(OfferCategory $offerCategory): RedirectResponse
    {
        if ($this->admin->cannot('delete', $offerCategory)) {
            return \redirect()->route('admin.offer_categories.index')->with('error', 'Access Denied');
        }
        $offerCategory->delete();

        return \redirect()->back()->with('message', 'Offer Category Deleted');
    }

    public function recover(OfferCategory $offerCategory): RedirectResponse
    {
        if ($this->admin->cannot('delete', $offerCategory)) {
            return \redirect()->route('admin.offer_categories.index')->with('error', 'Access Denied');
        }
        $offerCategory->restore();

        return \redirect()->back()->with('message', 'Offer Category Recovered');
    }
}
