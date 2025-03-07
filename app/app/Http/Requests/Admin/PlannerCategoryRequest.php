<?php

namespace App\Http\Requests\Admin;

use App\Models\PlannerCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PlannerCategoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $category = $this->route('plannerCategory');
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique(PlannerCategory::class)->ignore($category->id ?? null)],
            'color' => ['nullable', 'string'],
            'links' => ['nullable', 'string'],
            'as_article' => ['required', 'boolean'],
            'show_image' => ['required', 'boolean'],
        ];
    }
}
