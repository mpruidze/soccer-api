<?php

declare(strict_types=1);

namespace App\Http\Requests\Transfers;

use App\Enums\TransferAction;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class TransferActionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'action' => ['required', 'string', new Enum(TransferAction::class)],
        ];
    }
}
