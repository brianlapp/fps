<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ProductCategoryRequest;
use App\Models\Admin;
use App\Models\ProductCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use \App\Http\Resources\Admin\ProductCategory as ProductCategoryResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProductCategoriesController
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
        $list = ProductCategory::query()->filter($filters)->latest()->paginate($request->get('per_page', 20));
        $list->setCollection(ProductCategoryResource::collection($list->getCollection())->collection);

        return Inertia::render('Admin/ProductCategory/Index', compact('list', 'filters'));
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        return Inertia::render('Admin/ProductCategory/Edit', []);
    }

    public function store(ProductCategoryRequest $request): RedirectResponse
    {
        if ($this->admin->cannot('createProductCategory')) {
            return \redirect()->route('admin.product_categories.index')->with('error', 'Access Denied');
        }
        $productCategory = new ProductCategory();
        $data = $request->validated();

        $productCategory->fill($data);
        $productCategory->save();
        $productCategory->refresh();

        $productCategory->updateImage($request->get('image'));

        return Redirect::route('admin.product_categories.index')->with('message', 'Product Category Created');
    }

    public function edit(ProductCategory $productCategory, Request $request): RedirectResponse|Response
    {
        if ($this->admin->cannot('update', $productCategory)) {
            return \redirect()->route('admin.product_categories.index')->with('error', 'Access Denied');
        }

        return Inertia::render('Admin/ProductCategory/Edit', [
            'category' => new ProductCategoryResource($productCategory),
        ]);
    }

    public function update(ProductCategory $productCategory, ProductCategoryRequest $request): RedirectResponse
    {
        if ($this->admin->cannot('update', $productCategory)) {
            return \redirect()->route('admin.product_categories.index')->with('error', 'Access Denied');
        }
        $data = $request->validated();

        $productCategory->fill($data);
        $productCategory->save();
        $productCategory->updateImage($request->get('image'));

        return Redirect::route('admin.product_categories.index')->with('message', 'Product Category Updated');
    }

    public function destroy(ProductCategory $productCategory): RedirectResponse
    {
        if ($this->admin->cannot('delete', $productCategory)) {
            return \redirect()->route('admin.product_categories.index')->with('error', 'Access Denied');
        }
        $productCategory->delete();

        return \redirect()->back()->with('message', 'Product Category Deleted');
    }

    public function recover(ProductCategory $productCategory): RedirectResponse
    {
        if ($this->admin->cannot('delete', $productCategory)) {
            return \redirect()->route('admin.product_categories.index')->with('error', 'Access Denied');
        }
        $productCategory->restore();

        return \redirect()->back()->with('message', 'Product Category Recovered');
    }
}
