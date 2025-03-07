<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\NevebounceRule;
use App\Rules\ReCaptcha;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ActivityRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'child_age' => ['required', 'string', 'max:255'],
            'available_time' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'activity_type_preferences' => ['required', 'array', 'min:1'],
            'available_resources' => ['required', 'string', 'max:1000'],
            'special_requests' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
