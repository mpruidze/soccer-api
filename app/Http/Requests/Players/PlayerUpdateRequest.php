<?php

declare(strict_types=1);

namespace App\Http\Requests\Players;

use Illuminate\Foundation\Http\FormRequest;

class PlayerUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'string|max:255',
            'last_name' => 'string|max:255',
            'country' => 'string|max:255',
        ];
    }
}
