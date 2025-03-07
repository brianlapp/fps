<?php

namespace App\Http\Requests;

use App\Models\ProductReview;
use App\Rules\ReCaptcha;
use Illuminate\Foundation\Http\FormRequest;

class ProductReviewRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'min:5', 'max:100'],
            'content' => ['required', 'min:50', 'max:500'],
            'rate' => ['required', 'min:1', 'max:' . ProductReview::MAX_RATE],
            'recaptcha_response' => ['required', new ReCaptcha()],
        ];
    }
}
