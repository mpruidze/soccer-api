<?php

declare(strict_types=1);

namespace App\Http\Requests\Transfers;

use Illuminate\Foundation\Http\FormRequest;

class TransferIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'filters' => 'sometimes|array',
            'filters.teamId' => 'sometimes|int',
            'filters.keyword' => 'sometimes|string',
            'page' => 'sometimes|int',
            'perPage' => 'sometimes|int',
        ];
    }
}
