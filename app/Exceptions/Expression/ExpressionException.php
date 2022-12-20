<?php

namespace App\Exceptions\Expression;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

abstract class ExpressionException extends Exception
{
    public function render(Request $request): Response
    {
        return response([
            "message" => $this->getMessage(),
        ], 422);
    }
}
