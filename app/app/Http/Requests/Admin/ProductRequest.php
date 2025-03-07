<?php

namespace App\Http\Requests\Admin;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $product = $this->route('product');
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'long_description' => ['required', 'string'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique(Product::class)->ignore($product->id ?? null)],
            'is_active' => ['required', 'boolean'],
            'deals' => ['nullable', 'string'],
            'price_from' => ['nullable', 'numeric'],
            'price_to' => ['nullable', 'numeric'],
            'price_range_string' => ['nullable', 'string', 'max:255'],
            'affiliate_links' => ['nullable', 'array'],
            'categories' => ['array', 'min:1'],
        ];
    }
}
