<?php

namespace App\Http\Requests\Admin;

use App\Models\Article;
use App\Models\OfferCategory;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OfferCategoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $category = $this->route('offerCategory');
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique(OfferCategory::class)->ignore($category->id ?? null)],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
