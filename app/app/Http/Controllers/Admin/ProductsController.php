<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ProductRequest;
use App\Http\Resources\Admin\ProductCategory as ProductCategoryResource;
use App\Models\Admin;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use \App\Http\Resources\Admin\Product as ProductResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProductsController
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
        $filters = $request->only(['search', 'trashed', 'isActive', 'categories']);
        /**
         * @var $list LengthAwarePaginator
         */
        $list = Product::query()->filter($filters)->latest()->paginate($request->get('per_page', 20));
        $list->setCollection(ProductResource::collection($list->getCollection())->collection);
        $categories = ProductCategoryResource::collection(ProductCategory::query()->active()->get());

        return Inertia::render('Admin/Product/Index', compact('list', 'filters', 'categories'));
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $categories = ProductCategory::query()->active()->get();

        return Inertia::render('Admin/Product/Edit', [
            'categories' => ProductCategoryResource::collection($categories)
        ]);
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        if ($this->admin->cannot('createProduct')) {
            return \redirect()->route('admin.products.index')->with('error', 'Access Denied');
        }
        $product = new Product();
        $data = $request->validated();

        $product->fill($data);
        $product->save();
        $product->refresh();

        $product->updateImage($request->get('image'));
        $product->categories()->sync(array_column($data['categories'], 'id'));

        return Redirect::route('admin.products.index')->with('message', 'Product  Created');
    }

    public function edit(Product $product, Request $request): RedirectResponse|Response
    {
        if ($this->admin->cannot('update', $product)) {
            return \redirect()->route('admin.products.index')->with('error', 'Access Denied');
        }
        $categories = ProductCategory::query()->active()->get();

        return Inertia::render('Admin/Product/Edit', [
            'product' => new ProductResource($product),
            'categories' => ProductCategoryResource::collection($categories)
        ]);
    }

    public function update(Product $product, ProductRequest $request): RedirectResponse
    {
        if ($this->admin->cannot('update', $product)) {
            return \redirect()->route('admin.products.index')->with('error', 'Access Denied');
        }
        $data = $request->validated();

        $product->fill($data);
        $product->save();
        $product->updateImage($request->get('image'));
        $product->categories()->sync(array_column($data['categories'], 'id'));

        return Redirect::route('admin.products.index')->with('message', 'Product  Updated');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($this->admin->cannot('delete', $product)) {
            return \redirect()->route('admin.products.index')->with('error', 'Access Denied');
        }
        $product->delete();

        return \redirect()->back()->with('message', 'Product  Deleted');
    }

    public function recover(Product $product): RedirectResponse
    {
        if ($this->admin->cannot('delete', $product)) {
            return \redirect()->route('admin.products.index')->with('error', 'Access Denied');
        }
        $product->restore();

        return \redirect()->back()->with('message', 'Product  Recovered');
    }
}
