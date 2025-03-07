<?php

namespace App\Http\Controllers;

use App\Helpers\HtmlHelper;
use App\Http\Requests\ProductReviewRequest;
use App\Http\Resources\ProductReviewPublic;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class ProductReviewController extends Controller
{
    public function submit(string $slug, ProductReviewRequest $request)
    {
        $user = \Auth::user();

        $product = Product::query()->where('slug', $slug)->active()->first();

        if (empty($product)) {
            abort(404);
        }
        $data = $request->only([
            'title',
            'rate',
            'content'
        ]);
        $data['content'] = HtmlHelper::clearContent(($data['content']));
        $data['title'] = HtmlHelper::clearContent($data['title']);
        $data['user_id'] = $user->id;
        $data['uuid'] = Str::uuid();


        $review = $product->reviews()->create($data);
        $review->moderateInBackground();

        return Redirect::back()->with('message', 'Review Submitted!');
    }

    public function feed(string $slug, Request $request)
    {
        try {
            $user = \Auth::user();
            $product = Product::query()->where('slug', $slug)->active()->first();

            if (empty($product)) {
                throw  new \Exception('Product not found');
            }

            $reviews = $product->reviews()->latest()->public($user)->paginate($request->get('perPage', 50));
            $reviews->setCollection(ProductReviewPublic::collection($reviews->getCollection())->collection);

            return response()->json($reviews);
        } catch (\Throwable $exception) {
            Log::error('[Product reviews feed] ' . $exception->getMessage() . '(User: ' . (!empty($user) ? $user->id : $request->get('fingerprint')) . ', Slug: ' . $slug);

            return response()->json([
                'data' => []
            ]);
        }
    }
}
