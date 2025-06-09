<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UnsignedNumeric implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (preg_match('/^\d+$/', (string) $value) && (int) $value > 0) {
            return;
        }

        $fail($this->message());
    }

    public function message(): string
    {
        return __('validation.unsigned_numeric');
    }
}
