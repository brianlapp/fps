<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\NevebounceRule;
use App\Rules\ReCaptcha;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SearchRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'query' => ['required', 'string', 'max:100'],
            'recaptcha_response' => ['required', new ReCaptcha()],
        ];
    }
}
