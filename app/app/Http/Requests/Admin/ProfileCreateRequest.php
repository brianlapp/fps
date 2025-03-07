<?php

namespace App\Http\Requests\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileCreateRequest extends FormRequest
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
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(Admin::class)],
            'role' => ['required', 'int', Rule::in(array_keys(Admin::ROLES))],
            'title' => ['required', 'string'],
            'short_bio' => ['nullable', 'string', 'max:255'],
            'long_bio' => ['nullable', 'string'],
            'password' => ['required', Password::defaults(), 'confirmed']
        ];
    }
}
