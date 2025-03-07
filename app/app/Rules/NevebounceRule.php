<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Log;
use NeverBounce\ApiClient;
use NeverBounce\Auth;
use NeverBounce\Single;
use Throwable;

class NevebounceRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            Auth::setApiKey(config('services.neverbounce.api_key'));
            $result = Single::check($value);

            if (!in_array($result->result, config('services.neverbounce.valid_results'))) {
                $fail($this->message());
            }
        } catch (Throwable $exception) {
            Log::error('[NevebounceRule] ' . $exception->getMessage());
        }

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return __('validation.email');
    }
}
