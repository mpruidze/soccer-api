<?php

declare(strict_types=1);

namespace App\Http\Requests\Transfers;

use Illuminate\Foundation\Http\FormRequest;

class TransferUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'price' => 'required|numeric|min:0',
        ];
    }
}
