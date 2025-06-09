<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HttpException extends Exception
{
    public function render(Request $request): Response
    {
        return $request->wantsJson()
            ? response()->json(['message' => $this->getMessage()], $this->getCode())
            : response($this->getMessage(), $this->getCode());

    }
}
