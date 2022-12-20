<?php

namespace App\Exceptions\Expression;


class DivisionByZeroException extends ExpressionException
{
    public function __construct()
    {
        parent::__construct("You may not divide numbers by zero");
    }
}
