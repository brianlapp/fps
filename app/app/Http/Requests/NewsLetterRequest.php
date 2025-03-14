<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\NevebounceRule;
use App\Rules\ReCaptcha;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NewsLetterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255',  new NevebounceRule()],
            'recaptcha_response' => ['required', new ReCaptcha()],
        ];
    }
}
