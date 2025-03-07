<?php

namespace App\Http\Requests\Admin;

use App\Models\PlannerCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PlannerSectionRequest extends FormRequest
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
            'description' => ['nullable', 'string'],
            'planner_category_id' => ['required', 'integer'],
            'color' => ['nullable', 'string'],
            'items' => ['array', 'min:1'],
            'items.*.id' => 'nullable',
            'items.*.title' => 'required|string',
            'items.*.description' => 'string|nullable',
            'items.*.label' => 'string|nullable',
            'items.*.color' => 'string|nullable',
            'items.*.uuid' => 'string|required',
            'links' => ['nullable', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'items.*.title.required' => 'Each item must have a title.',
            'items.*.title.string' => 'The title for each item must be a valid string.',
        ];
    }
}
