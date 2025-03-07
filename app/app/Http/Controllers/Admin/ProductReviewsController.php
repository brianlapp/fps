<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\ProductReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use \App\Http\Resources\Admin\ProductReview as ProductReviewResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ProductReviewsController
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
        $filters = $request->only(['search', 'trashed', 'product', 'rate', 'status']);
        /**
         * @var $list LengthAwarePaginator
         */
        $list = ProductReview::query()->filter($filters)->latest()->paginate($request->get('per_page', 20));
        $list->setCollection(ProductReviewResource::collection($list->getCollection())->collection);
        $statuses = [];
        foreach (ProductReview::STATUSES as $key => $value) {
            $statuses[] = [
                'value' => (string) $value,
                'label' => $key
            ];
        }

        return Inertia::render('Admin/ProductReview/Index', compact('list', 'filters', 'statuses'));
    }

    public function destroy(ProductReview $productReview): RedirectResponse
    {
        if ($this->admin->cannot('delete', $productReview)) {
            return \redirect()->route('admin.product_reviews.index')->with('error', 'Access Denied');
        }
        $productReview->delete();
        $productReview->product->calculateRating();

        return \redirect()->back()->with('message', 'Product Review  Deleted');
    }

    public function recover(ProductReview $productReview): RedirectResponse
    {
        if ($this->admin->cannot('delete', $productReview)) {
            return \redirect()->route('admin.product_reviews.index')->with('error', 'Access Denied');
        }
        $productReview->restore();
        $productReview->product->calculateRating();

        return \redirect()->back()->with('message', 'Product Review Recovered');
    }
}
