<?php

namespace App\Http\Requests\Admin;

use App\Models\Article;
use App\Models\OfferCategory;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TopicRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $topic = $this->route('topic');
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique(Topic::class)->ignore($topic->id ?? null)],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
