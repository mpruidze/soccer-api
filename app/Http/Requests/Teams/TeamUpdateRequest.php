<?php

declare(strict_types=1);

namespace App\Http\Requests\Teams;

use Illuminate\Foundation\Http\FormRequest;

class TeamUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'string|max:255',
            'country' => 'string|max:255',
        ];
    }
}
