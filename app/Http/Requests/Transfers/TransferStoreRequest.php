<?php

declare(strict_types=1);

namespace App\Http\Requests\Transfers;

use App\Rules\UnsignedNumeric;
use Illuminate\Foundation\Http\FormRequest;

class TransferStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'player_id' => ['required', 'exists:players,id', new UnsignedNumeric()],
            'price' => 'required|numeric|min:0',
        ];
    }
}
