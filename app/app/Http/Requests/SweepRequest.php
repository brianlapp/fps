<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\NevebounceRule;
use App\Rules\ReCaptcha;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SweepRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' =>['required', 'string', 'lowercase', 'email', 'max:255', new NevebounceRule()],
            'lang' => ['required'],
            'recaptcha_response' => ['required', new ReCaptcha()],
            'disclaimer' => Auth::check() ? ['nullable'] : ['accepted'],
            'child_age' => ['required', 'string'],
            'postcode' => ['required', 'string'],
            'rules' => ['accepted'],
        ];
    }
}
