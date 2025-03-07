<?php

namespace App\Http\Requests\Admin;

use App\Models\ProductCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductCategoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $category = $this->route('productCategory');
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique(ProductCategory::class)->ignore($category->id ?? null)],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
