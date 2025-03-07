<?php

namespace App\Http\Requests\Admin;

use App\Models\Article;
use App\Models\Offer;
use App\Models\OfferCategory;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OfferRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $offer = $this->route('offer');
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'link' => ['required', 'url:http,https', 'max:255'],
            'offer_category_id' => ['required', 'numeric', 'exists:' . OfferCategory::class . ',id'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique(Offer::class)->ignore($offer->id ?? null)],
            'is_active' => ['required', 'boolean'],
            'countries' => ['array', 'min:1'],
        ];
    }
}
