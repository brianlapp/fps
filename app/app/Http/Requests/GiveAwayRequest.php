<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\NevebounceRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class GiveAwayRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $user = Auth::user();
        $emailRules = ['required', 'string', 'lowercase', 'email', 'max:255', new NevebounceRule()];
        if (empty($user)) {
            $emailRules[] = Rule::unique(User::class);
        } else {
            $emailRules[] = Rule::unique(User::class)->ignore($user->id);
        }
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => $emailRules,
            'lang' => ['required'],
            'postcode' => ['required'],
            'phone' => ['required', 'regex:/^\(\d{3}\) \d{3}-\d{4}$/'],
            'disclaimer' => ['accepted'],
            'cst_consent' => ['nullable'],
            'embark_consent' => ['nullable'],
            'child_dob' => ['required'],
        ];
    }
}
