<?php

namespace App\Http\Requests\Admin;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ArticleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $article = $this->route('article');
        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique(Article::class)->ignore($article->id ?? null)],
            'created_by' => ['nullable', 'numeric'],
            'tags' => ['nullable', 'array'],
            'status' => ['string', Rule::in(array_keys(Article::STATUSES))],
            'is_page' => ['required', 'boolean'],
            'image_caption' => ['nullable', 'string', 'max:255'],
            'topics' => ['nullable', 'array'],
            'was_improved' => ['required', 'boolean'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_headline' => ['nullable', 'string', 'max:1000'],
            'is_featured' => ['required', 'boolean'],
        ];
    }
}
